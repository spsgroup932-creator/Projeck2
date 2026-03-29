<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToBranch;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, BelongsToBranch, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'password_plain',
        'role',
        'accessible_menus',
        'branch_id',
        'last_seen_at',
        'font_size',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'accessible_menus' => 'array',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Cek apakah user berhak mengakses menu berdasarkan key-nya.
     * Super admin selalu true.
     */
    public function canAccessMenu($menuKey)
    {
        // Bypass untuk super admin kawan
        if (strtolower($this->role) === 'super admin' || $menuKey === 'settings' || $menuKey === 'profile') {
            return true;
        }

        // 1. Cek izin di level Branch (Rental) kawan
        // Jika rental tidak punya akses ke fitur ini, maka staff-nya juga tidak bisa kawan.
        if ($this->branch) {
            $branchAllowed = is_array($this->branch->accessible_menus) ? $this->branch->accessible_menus : [];
            if (!in_array($menuKey, $branchAllowed)) {
                return false;
            }
        }

        $menuConfig = config('menus.' . $menuKey);
        
        // Jika menu memiliki restriksi role kawan
        if (isset($menuConfig['role'])) {
            if (strtolower($this->role) !== strtolower($menuConfig['role'])) {
                return false;
            }
        }

        // 2. Cek izin di level User kawan
        $accessible = is_array($this->accessible_menus) ? $this->accessible_menus : [];
        
        return in_array($menuKey, $accessible);
    }
}
