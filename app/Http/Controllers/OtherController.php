<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Models\Access;
use App\Models\Booking;

class OtherController extends Controller
{
    public function local_admin()
    {
        $users = User::where('role', 'admin')
            ->orderBy('id', 'asc')
            ->skip(1)
            ->take(PHP_INT_MAX) // or use a large number instead of PHP_INT_MAX
            ->get();
        return view('system.local_admin', compact('users'));
    }

    public function local_admin_form()
    {
        return view('system.local_admin_create');
    }

    public function local_admin_store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:bus_company,admin,vendor,local_admin'], // Fixed typo from 'vender' to 'vendor'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'contact' => $request->contact,
                'status' => 'accept', // Assuming you want to set the status to active
            ]);

            // You might want to add event or notification here
            // event(new UserCreated($user));

            return redirect()->route('system.local_admin')
                ->with('success', 'Local admin created successfully!');
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error creating local admin: ' . $th->getMessage());

            return back()->withInput()
                ->with('error', 'Error creating local admin: ' . $th->getMessage());
        }
    }

    public function local_admin_edit($id)
    {
        $user = User::findOrFail($id);
        return view('system.local_admin_edit', compact('user'));
    }
    public function local_admin_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:bus_company,admin,vendor,local_admin'], // Fixed typo from 'vender' to 'vendor'
        ]);

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->contact = $request->contact;
            $user->status = 'accept'; // Assuming you want to set the status to active
            $user->save();

            return redirect()->route('local_admin.index')
                ->with('success', 'Local admin updated successfully!');
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error updating local admin: ' . $th->getMessage());

            return back()->withInput()
                ->with('error', 'Error updating local admin: ' . $th->getMessage());
        }
    }
    public function local_admin_destroy($id)
    {
        $user = User::findOrFail($id);
        try {
            $user->delete();
            return redirect()->route('system.local_admin')
                ->with('success', 'Local admin deleted successfully!');
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error deleting local admin: ' . $th->getMessage());

            return back()->with('error', 'Error deleting local admin: ' . $th->getMessage());
        }
    }

    public function update_role(Request $request)
    {
        $user = User::findOrFail($request->id);


        try {
            $data = Access::updateOrcreate(
                [
                    'user_id' => $request->id,
                    'link' => $request->link,
                ],
                [
                    'status' => $request->status,
                ]
            );
            if ($data->wasRecentlyCreated) {
                $message = 'Access created successfully!';
            } else {
                $message = 'Access updated successfully!';
            }

            return back()
                ->with('success', $message);
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error updating user role: ' . $th->getMessage());

            return back()->withInput()
                ->with('error', 'Error updating user role: ' . $th->getMessage());
        }
    }

    // for Bus owner

    public function local_bus_owners($id)
    {
        $user = User::findOrFail($id);
        return view('controller.owner_permissions_edit', compact('user'));
    }

    public function local_bus_update(Request $request)
    {
        $user = User::findOrFail($request->id);


        try {
            $data = Access::updateOrcreate(
                [
                    'user_id' => $request->id,
                    'link' => $request->link,
                ],
                [
                    'status' => $request->status,
                ]
            );
            if ($data->wasRecentlyCreated) {
                $message = 'Access created successfully!';
            } else {
                $message = 'Access updated successfully!';
            }

            return back()
                ->with('success', $message);
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error updating user role: ' . $th->getMessage());

            return back()->withInput()
                ->with('error', 'Error updating user role: ' . $th->getMessage());
        }
    }
}
