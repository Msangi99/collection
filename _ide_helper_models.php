<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $link
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Access newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Access newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Access query()
 * @method static \Illuminate\Database\Eloquent\Builder|Access whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Access whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Access whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Access whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Access whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Access whereUserId($value)
 */
	class Access extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $trans_ref_id
 * @property string $amount
 * @property int $payment_number
 * @property string $payment_method
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction whereTransRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminTransaction whereUpdatedAt($value)
 */
	class AdminTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $service_balance
 * @property string $commision_balance
 * @property string|null $balance
 * @property string $vat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereCommisionBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereServiceBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWallet whereVat($value)
 */
	class AdminWallet extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $booking_id
 * @property string $start_date
 * @property string $end_date
 * @property string $amount
 * @property string $bima_vat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @method static \Illuminate\Database\Eloquent\Builder|Bima newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bima newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bima query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereBimaVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bima whereUpdatedAt($value)
 */
	class Bima extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id
 * @property string|null $booking_code
 * @property int|null $campany_id
 * @property int|null $bus_id
 * @property int|null $route_id
 * @property string|null $customer_phone
 * @property string|null $customer_email
 * @property string|null $customer_name
 * @property string|null $pickup_point
 * @property string|null $dropping_point
 * @property string|null $travel_date
 * @property string|null $seat
 * @property string|null $amount
 * @property string|null $payment_status
 * @property string|null $trans_status
 * @property string|null $transaction_ref_id
 * @property string|null $external_ref_id
 * @property string|null $mfs_id
 * @property string|null $verification_code
 * @property int|null $bima
 * @property int|null $bima_amount
 * @property string|null $insuranceDate
 * @property string|null $vender_id
 * @property string $fee
 * @property string $service
 * @property string $vender_fee
 * @property string $vender_service
 * @property string $vat
 * @property string|null $discount
 * @property string $discount_amount
 * @property int $distance
 * @property string $busFee
 * @property string $fee_vat
 * @property string|null $service_vat
 * @property string $bima_vat
 * @property-read \App\Models\bus|null $bus
 * @property-read \App\Models\Campany|null $campany
 * @property-read \App\Models\Discount|null $discounta
 * @property-read \App\Models\route|null $route
 * @property-read \App\Models\route|null $route_name
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User|null $vender
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBimaAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBimaVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBusFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereDroppingPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereExternalRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereFeeVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereInsuranceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereMfsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking wherePickupPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereSeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereServiceVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTransStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTransactionRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTravelDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereVenderFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereVenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereVenderService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereVerificationCode($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $campany_id
 * @property string|null $registration_number
 * @property string|null $tin
 * @property string|null $vrn
 * @property string|null $office_number
 * @property string|null $box
 * @property string|null $street
 * @property string|null $town
 * @property string|null $city
 * @property string|null $region
 * @property string|null $country
 * @property string|null $bank_number
 * @property string|null $bank_name
 * @property string|null $whatsapp_number
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereBankNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereOfficeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereVrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusOwnerAccount whereWhatsappNumber($value)
 */
	class BusOwnerAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $user_id
 * @property int|null $payment_number
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $status
 * @property int|null $percentage
 * @property-read \App\Models\balance|null $balance
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\bus> $bus
 * @property-read int|null $bus_count
 * @property-read \App\Models\BusOwnerAccount|null $busOwnerAccount
 * @property-read \App\Models\bus|null $buses
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Campany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campany query()
 * @method static \Illuminate\Database\Eloquent\Builder|Campany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campany whereUserId($value)
 */
	class Campany extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $used
 * @property int|null $percentage
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $booking
 * @property-read int|null $booking_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUsed($value)
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $campany_id
 * @property int|null $amount
 * @property string|null $booking_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campany|null $campany
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentFees whereUpdatedAt($value)
 */
	class PaymentFees extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $bus_id
 * @property int|null $route_id
 * @property int|null $point_mode
 * @property string|null $point
 * @property int|null $amount
 * @property string $state
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\bus|null $bus
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\route|null $route
 * @method static \Illuminate\Database\Eloquent\Builder|Point newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Point newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Point query()
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereBusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point wherePointMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereUpdatedAt($value)
 */
	class Point extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $bus_id
 * @property int $route_id
 * @property string|null $from
 * @property string|null $to
 * @property string|null $schedule_date
 * @property string|null $start
 * @property string|null $end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\bus|null $bus
 * @property-read Schedule|null $childSchedule
 * @property-read Schedule|null $parentSchedule
 * @property-read \App\Models\route|null $route
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereBusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereScheduleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereUpdatedAt($value)
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $international
 * @property string $local
 * @property string|null $service
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereInternational($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereLocal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $bus_id
 * @property string|null $from
 * @property string|null $to
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\bus|null $bus
 * @method static \Illuminate\Database\Eloquent\Builder|Station newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Station newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Station query()
 * @method static \Illuminate\Database\Eloquent\Builder|Station whereBusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Station whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Station whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Station whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Station whereTo($value)
 */
	class Station extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $campany_id
 * @property int|null $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campany|null $campany
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemBalance whereUpdatedAt($value)
 */
	class SystemBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $campany_id
 * @property int|null $user_id
 * @property string|null $payment_method
 * @property int $amount
 * @property int|null $payment_number
 * @property string|null $status
 * @property string|null $reference_number
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $vender_id
 * @property-read \App\Models\Campany|null $campany
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\VenderBalance|null $vender
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereVenderId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionController newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionController newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionController query()
 */
	class TransactionController extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $contact
 * @property string $status
 * @property-read \App\Models\VenderAccount|null $VenderAccount
 * @property-read \App\Models\VenderBalance|null $VenderBalances
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Access> $access
 * @property-read int|null $access_count
 * @property-read \App\Models\Campany|null $campany
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $tin
 * @property string|null $house_number
 * @property string|null $street
 * @property string|null $town
 * @property string|null $city
 * @property string|null $province
 * @property string|null $country
 * @property string|null $altenative_number
 * @property string|null $bank_name
 * @property string|null $bank_number
 * @property int|null $percentage
 * @property string|null $work
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereAltenativeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereBankNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereHouseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderAccount whereWork($value)
 */
	class VenderAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id Primary Key
 * @property int|null $user_id
 * @property int|null $amount
 * @property \Illuminate\Support\Carbon|null $created_at Create Time
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $fees
 * @property int|null $payment_number
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance whereFees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenderBalance whereUserId($value)
 */
	class VenderBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $bus_id
 * @property int|null $route_id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\route|null $route
 * @method static \Illuminate\Database\Eloquent\Builder|Via newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Via newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Via query()
 * @method static \Illuminate\Database\Eloquent\Builder|Via whereBusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Via whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Via whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Via whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Via whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Via whereUpdatedAt($value)
 */
	class Via extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $campany_id
 * @property int $amount
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $fees
 * @method static \Illuminate\Database\Eloquent\Builder|balance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|balance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|balance query()
 * @method static \Illuminate\Database\Eloquent\Builder|balance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|balance whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|balance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|balance whereFees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|balance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|balance whereUpdatedAt($value)
 */
	class balance extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $campany_id
 * @property string $bus_number
 * @property int|null $route_id
 * @property string|null $bus_features
 * @property int $bus_type
 * @property int $total_seats
 * @property string $conductor
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $booking
 * @property-read int|null $booking_count
 * @property-read \App\Models\Campany|null $busname
 * @property-read \App\Models\Campany|null $campany
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Point> $point
 * @property-read int|null $point_count
 * @property-read \App\Models\route|null $rout
 * @property-read \App\Models\route|null $route
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\route> $routes
 * @property-read int|null $routes_count
 * @property-read \App\Models\Schedule|null $schedule
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \App\Models\Station|null $stend
 * @method static \Illuminate\Database\Eloquent\Builder|bus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|bus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|bus query()
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereBusFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereBusNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereBusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereCampanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereConductor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereTotalSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|bus whereUpdatedAt($value)
 */
	class bus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $bus_id
 * @property string $from
 * @property string $to
 * @property string|null $route_start
 * @property string|null $route_end
 * @property int $price
 * @property int|null $distance
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\bus|null $bus
 * @property-read \App\Models\bus|null $campany
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Point> $points
 * @property-read int|null $points_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \App\Models\Via|null $via
 * @method static \Illuminate\Database\Eloquent\Builder|route newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|route newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|route query()
 * @method static \Illuminate\Database\Eloquent\Builder|route whereBusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereRouteEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereRouteStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|route whereUpdatedAt($value)
 */
	class route extends \Eloquent {}
}

