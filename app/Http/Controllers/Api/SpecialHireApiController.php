<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coaster;
use App\Models\SpecialHireOrder;
use App\Models\SpecialHirePricing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SpecialHireApiController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $data = [
            'total_coasters' => Coaster::byUser($user->id)->count(),
            'available_coasters' => Coaster::byUser($user->id)->available()->count(),
            'on_hire_coasters' => Coaster::byUser($user->id)->where('status', 'on_hire')->count(),
            'total_orders' => SpecialHireOrder::byUser($user->id)->count(),
            'pending_orders' => SpecialHireOrder::byUser($user->id)->pending()->count(),
            'completed_orders' => SpecialHireOrder::byUser($user->id)->completed()->count(),
            'today_orders' => SpecialHireOrder::byUser($user->id)->today()->count(),
            'total_revenue' => SpecialHireOrder::byUser($user->id)->paid()->sum('total_amount'),
            'today_revenue' => SpecialHireOrder::byUser($user->id)->paid()->today()->sum('total_amount'),
            'month_revenue' => SpecialHireOrder::byUser($user->id)
                ->paid()
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get earnings summary.
     */
    public function earnings(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'month');

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $data = [
            'period' => $period,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_earnings' => SpecialHireOrder::byUser($user->id)
                ->paid()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),
            'total_orders' => SpecialHireOrder::byUser($user->id)
                ->paid()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'total_distance_km' => SpecialHireOrder::byUser($user->id)
                ->paid()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('distance_km'),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // ==================== COASTERS API ====================

    /**
     * List all coasters.
     */
    public function indexCoasters(Request $request)
    {
        $query = Coaster::byUser(Auth::id())->with('pricing');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $coasters = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $coasters,
        ]);
    }

    /**
     * Get single coaster.
     */
    public function showCoaster($id)
    {
        $coaster = Coaster::byUser(Auth::id())->with('pricing')->find($id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $coaster,
        ]);
    }

    /**
     * Create new coaster.
     */
    public function storeCoaster(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:coasters,plate_number',
            'capacity' => 'required|integer|min:1|max:100',
            'model' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:100',
            'driver_contact' => 'nullable|string|max:20',
            'features' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'price_per_km' => 'required|numeric|min:0',
            'min_km' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $coaster = Coaster::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'plate_number' => $request->plate_number,
            'capacity' => $request->capacity,
            'model' => $request->model,
            'color' => $request->color,
            'driver_name' => $request->driver_name,
            'driver_contact' => $request->driver_contact,
            'features' => $request->features,
            'status' => 'available',
        ]);

        SpecialHirePricing::create([
            'coaster_id' => $coaster->id,
            'base_price' => $request->base_price,
            'price_per_km' => $request->price_per_km,
            'min_km' => $request->min_km,
            'weekend_surcharge_percent' => $request->weekend_surcharge_percent ?? 15,
            'night_surcharge_percent' => $request->night_surcharge_percent ?? 20,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coaster created successfully',
            'data' => $coaster->load('pricing'),
        ], 201);
    }

    /**
     * Update coaster.
     */
    public function updateCoaster(Request $request, $id)
    {
        $coaster = Coaster::byUser(Auth::id())->find($id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'plate_number' => 'sometimes|string|max:20|unique:coasters,plate_number,' . $id,
            'capacity' => 'sometimes|integer|min:1|max:100',
            'model' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'status' => 'sometimes|in:available,on_hire,maintenance',
            'driver_name' => 'nullable|string|max:100',
            'driver_contact' => 'nullable|string|max:20',
            'features' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $coaster->update($request->only([
            'name', 'plate_number', 'capacity', 'model', 'color',
            'status', 'driver_name', 'driver_contact', 'features'
        ]));

        // Update pricing if provided
        if ($request->has('base_price') || $request->has('price_per_km')) {
            $coaster->pricing()->updateOrCreate(
                ['coaster_id' => $coaster->id],
                $request->only(['base_price', 'price_per_km', 'min_km', 'weekend_surcharge_percent', 'night_surcharge_percent'])
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Coaster updated successfully',
            'data' => $coaster->fresh()->load('pricing'),
        ]);
    }

    /**
     * Delete coaster.
     */
    public function destroyCoaster($id)
    {
        $coaster = Coaster::byUser(Auth::id())->find($id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        if ($coaster->image) {
            Storage::disk('public')->delete($coaster->image);
        }

        $coaster->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coaster deleted successfully',
        ]);
    }

    // ==================== LOCATION API ====================

    /**
     * Get all coasters locations.
     */
    public function allLocations()
    {
        $coasters = Coaster::byUser(Auth::id())
            ->select('id', 'name', 'plate_number', 'latitude', 'longitude', 'status', 'driver_name', 'driver_contact', 'last_location_update')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $coasters,
        ]);
    }

    /**
     * Get single coaster location.
     */
    public function getLocation($id)
    {
        $coaster = Coaster::byUser(Auth::id())
            ->select('id', 'name', 'plate_number', 'latitude', 'longitude', 'status', 'driver_name', 'driver_contact', 'last_location_update')
            ->find($id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $coaster,
        ]);
    }

    /**
     * Update coaster location.
     */
    public function updateLocation(Request $request, $id)
    {
        $coaster = Coaster::byUser(Auth::id())->find($id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $coaster->updateLocation($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'coaster_id' => $coaster->id,
                'latitude' => $coaster->latitude,
                'longitude' => $coaster->longitude,
                'last_updated' => $coaster->last_location_update,
            ],
        ]);
    }

    // ==================== ORDERS API ====================

    /**
     * List all orders.
     */
    public function indexOrders(Request $request)
    {
        $query = SpecialHireOrder::byUser(Auth::id())->with('coaster');

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date')) {
            $query->whereDate('hire_date', $request->date);
        }

        if ($request->filled('coaster_id')) {
            $query->where('coaster_id', $request->coaster_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Get single order.
     */
    public function showOrder($id)
    {
        $order = SpecialHireOrder::byUser(Auth::id())->with('coaster')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Create new order.
     */
    public function storeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coaster_id' => 'required|exists:coasters,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
            'pickup_location' => 'required|string|max:255',
            'pickup_latitude' => 'nullable|numeric',
            'pickup_longitude' => 'nullable|numeric',
            'dropoff_location' => 'required|string|max:255',
            'dropoff_latitude' => 'nullable|numeric',
            'dropoff_longitude' => 'nullable|numeric',
            'hire_date' => 'required|date|after_or_equal:today',
            'hire_time' => 'required',
            'return_date' => 'nullable|date|after_or_equal:hire_date',
            'return_time' => 'nullable',
            'passengers_count' => 'required|integer|min:1',
            'purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify coaster belongs to user
        $coaster = Coaster::byUser(Auth::id())->with('pricing')->find($request->coaster_id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        if (!$coaster->pricing) {
            return response()->json([
                'success' => false,
                'message' => 'Pricing not set for this coaster',
            ], 400);
        }

        // Calculate distance if coordinates provided
        $distanceKm = 0;
        if ($request->pickup_latitude && $request->pickup_longitude && 
            $request->dropoff_latitude && $request->dropoff_longitude) {
            $distanceKm = SpecialHirePricing::calculateDistance(
                $request->pickup_latitude,
                $request->pickup_longitude,
                $request->dropoff_latitude,
                $request->dropoff_longitude
            );
        } else {
            $distanceKm = $request->get('distance_km', $coaster->pricing->min_km);
        }

        // Calculate price
        $priceData = $coaster->pricing->calculatePrice(
            $distanceKm,
            $request->hire_date,
            $request->hire_time
        );

        // Create order
        $order = SpecialHireOrder::create([
            'user_id' => Auth::id(),
            'coaster_id' => $coaster->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'pickup_location' => $request->pickup_location,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'dropoff_location' => $request->dropoff_location,
            'dropoff_latitude' => $request->dropoff_latitude,
            'dropoff_longitude' => $request->dropoff_longitude,
            'hire_date' => $request->hire_date,
            'hire_time' => $request->hire_time,
            'return_date' => $request->return_date,
            'return_time' => $request->return_time,
            'passengers_count' => $request->passengers_count,
            'purpose' => $request->purpose,
            'notes' => $request->notes,
            'distance_km' => $priceData['billable_km'],
            'base_price' => $priceData['base_price'],
            'price_per_km' => $priceData['price_per_km'],
            'km_amount' => $priceData['km_amount'],
            'surcharge_percent' => $priceData['surcharge_percent'],
            'surcharge_amount' => $priceData['surcharge_amount'],
            'total_amount' => $priceData['total_amount'],
            'order_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order->load('coaster'),
        ], 201);
    }

    /**
     * Update order.
     */
    public function updateOrder(Request $request, $id)
    {
        $order = SpecialHireOrder::byUser(Auth::id())->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'order_status' => 'sometimes|in:pending,confirmed,in_progress,completed,cancelled',
            'payment_status' => 'sometimes|in:pending,paid,refunded',
            'payment_method' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateData = $request->only(['order_status', 'payment_status', 'payment_method']);
        $order->update($updateData);

        // Update coaster status based on order status
        if ($request->has('order_status')) {
            if ($request->order_status === 'in_progress') {
                $order->coaster->update(['status' => 'on_hire']);
            } elseif (in_array($request->order_status, ['completed', 'cancelled'])) {
                $order->coaster->update(['status' => 'available']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order->fresh()->load('coaster'),
        ]);
    }

    /**
     * Delete/Cancel order.
     */
    public function destroyOrder($id)
    {
        $order = SpecialHireOrder::byUser(Auth::id())->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        // Only allow deletion of pending orders
        if ($order->order_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be deleted',
            ], 400);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully',
        ]);
    }

    // ==================== PRICE CALCULATION API ====================

    /**
     * Calculate price for a trip.
     */
    public function calculatePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coaster_id' => 'required|exists:coasters,id',
            'pickup_latitude' => 'required_with:pickup_longitude|numeric',
            'pickup_longitude' => 'required_with:pickup_latitude|numeric',
            'dropoff_latitude' => 'required_with:dropoff_longitude|numeric',
            'dropoff_longitude' => 'required_with:dropoff_latitude|numeric',
            'distance_km' => 'required_without_all:pickup_latitude,dropoff_latitude|numeric|min:0',
            'hire_date' => 'required|date',
            'hire_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $coaster = Coaster::byUser(Auth::id())->with('pricing')->find($request->coaster_id);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        if (!$coaster->pricing) {
            return response()->json([
                'success' => false,
                'message' => 'Pricing not set for this coaster',
            ], 400);
        }

        // Calculate distance
        $distanceKm = $request->distance_km;
        if ($request->pickup_latitude && $request->dropoff_latitude) {
            $distanceKm = SpecialHirePricing::calculateDistance(
                $request->pickup_latitude,
                $request->pickup_longitude,
                $request->dropoff_latitude,
                $request->dropoff_longitude
            );
        }

        // Calculate price
        $priceData = $coaster->pricing->calculatePrice(
            $distanceKm,
            $request->hire_date,
            $request->hire_time
        );

        return response()->json([
            'success' => true,
            'data' => [
                'coaster' => [
                    'id' => $coaster->id,
                    'name' => $coaster->name,
                    'capacity' => $coaster->capacity,
                ],
                'distance_km' => $priceData['distance_km'],
                'billable_km' => $priceData['billable_km'],
                'breakdown' => [
                    'base_price' => $priceData['base_price'],
                    'price_per_km' => $priceData['price_per_km'],
                    'km_amount' => $priceData['km_amount'],
                    'surcharge_percent' => $priceData['surcharge_percent'],
                    'surcharge_labels' => $priceData['surcharge_labels'],
                    'surcharge_amount' => $priceData['surcharge_amount'],
                ],
                'total_amount' => $priceData['total_amount'],
                'currency' => 'TZS',
            ],
        ]);
    }

    /**
     * Get pricing for a coaster.
     */
    public function getPricing($coasterId)
    {
        $coaster = Coaster::byUser(Auth::id())->with('pricing')->find($coasterId);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $coaster->pricing,
        ]);
    }

    /**
     * Update pricing for a coaster.
     */
    public function updatePricing(Request $request, $coasterId)
    {
        $coaster = Coaster::byUser(Auth::id())->find($coasterId);

        if (!$coaster) {
            return response()->json([
                'success' => false,
                'message' => 'Coaster not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'base_price' => 'required|numeric|min:0',
            'price_per_km' => 'required|numeric|min:0',
            'min_km' => 'required|integer|min:1',
            'weekend_surcharge_percent' => 'required|numeric|min:0|max:100',
            'night_surcharge_percent' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $pricing = SpecialHirePricing::updateOrCreate(
            ['coaster_id' => $coaster->id],
            $request->only(['base_price', 'price_per_km', 'min_km', 'weekend_surcharge_percent', 'night_surcharge_percent'])
        );

        return response()->json([
            'success' => true,
            'message' => 'Pricing updated successfully',
            'data' => $pricing,
        ]);
    }
}

