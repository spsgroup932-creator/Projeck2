<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'whatsapp_number',
        'email',
        'website',
        'logo',
        'watermark',
        'description',
        'header_color',
        'nib',
        'npwp',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'qris_image',
        'receipt_footer',
        'watermark_text',
        'header_color',
        'font_family',
        'subscription_amount',
        'subscription_due_at',
        'accessible_menus',
    ];

    protected $casts = [
        'accessible_menus' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin cabang');
    }

    public function regularUsers()
    {
        return $this->hasMany(User::class)->where('role', 'user');
    }

    public function onlineUsers()
    {
        return $this->hasMany(User::class)->where('last_seen_at', '>=', now()->subMinutes(5));
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }
}
