<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact',
        'status',
        'campany_id', // Added for local bus owners
        'email_verified_at',
        'verification_code',
        'verification_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'verification_expires_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isBusCampany()
    {
        return $this->role === 'bus_campany';
    }

    public function isTraveler()
    {
        return $this->role === 'traveler';
    }

    public function isVender()
    {
        return $this->role === 'vender';
    }

    public function isLocalAdmin()
    {
        return $this->role === 'local_admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isLocalBusOwner()
    {
        return $this->role === 'local_bus_owner';
    }

    /**
     * Get the company associated with the user (if the user is a bus company admin).
     */
    public function campany()
    {
        if($this->role === 'local_bus_owner') {
            return $this->hasOne(Campany::class, 'id', 'campany_id');
        }
        return $this->hasOne(Campany::class, 'user_id');
    }

    /**
     * Get the company for a local bus owner.
     */
    public function localBusOwnerCompany()
    {
        return $this->belongsTo(Campany::class, 'company_id');
    }

    /**
     * Get the company ID for the authenticated user.
     * This handles both 'bus_company' and 'local_bus_owner' roles.
     */
    public function getCompanyId(): ?int
    {
        if ($this->isBusCompany() && $this->company) {
            return $this->company->id;
        }

        if ($this->isLocalBusOwner() && $this->localBusOwnerCompany) {
            return $this->localBusOwnerCompany->id;
        }

        return null;
    }

    public function VenderBalances()
    {
        return $this->hasOne(VenderBalance::class);
    }

    public function VenderAccount()
    {
        return $this->hasOne(VenderAccount::class, 'user_id', 'id');
    }

    public function access()
    {
        return $this->hasMany(Access::class, 'user_id', 'id');
    }

    public function hasAccessTo($link)
    {
        return $this->isBusCampany() || (
            $this->isLocalbusOwner() &&
            $this->access()->where('link', $link)->where('status', 'active')->exists()
        );
    }

    public function hasAccess($link)
    {
        return $this->isEmail() || (
            $this->isAdmin() && 
            $this->access()->where('link', $link)->where('status', 'active')->exists()
        );
    }

    // Helper method to check if user is active
    public function isActive()
    {
        return $this->status === 'accept' || $this->status === '';
    }

    public function isEmail()
    {
        return $this->email === 'admin@gmail.com';
    }

    public function temp_wallets()
    {
        return $this->hasOne(TempWallet::class, 'user_id', 'id');
    }

    /**
     * Generate a verification code for email verification
     */
    public function generateVerificationCode()
    {
        // Generate a 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Set expiration time to 15 minutes from now
        $expiresAt = now()->addMinutes(15);
        
        // Update the user with the verification code and expiration time
        $this->update([
            'verification_code' => $verificationCode,
            'verification_expires_at' => $expiresAt,
        ]);
        
        return $verificationCode;
    }

    /**
     * Check if the verification code is valid and not expired
     */
    public function isVerificationCodeValid($code)
    {
        return $this->verification_code === $code && 
               $this->verification_expires_at && 
               $this->verification_expires_at->isFuture();
    }

    /**
     * Clear the verification code after successful verification
     */
    public function clearVerificationCode()
    {
        $this->update([
            'verification_code' => null,
            'verification_expires_at' => null,
        ]);
    }

    /**
     * Mark the user's email as verified
     */
    public function markEmailAsVerified()
    {
        $this->update([
            'email_verified_at' => now(),
        ]);
        
        // Don't clear verification code since customers verify on every login
        // The verification code will be regenerated on next login
    }

    /**
     * Determine if the user has verified their email address
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }
}
