<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Driver;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 10 Customers
        $names = ['Indofood', 'Unilever', 'Pertamina', 'Telkomsel', 'Gojek', 'Grab', 'Tokopedia', 'Shopee', 'Sinar Mas', 'Astra'];
        foreach ($names as $name) {
            Customer::updateOrCreate(
                ['name' => $name],
                [
                    'email' => strtolower($name) . '@example.com',
                    'phone' => '08' . rand(100000000, 999999999),
                    'address' => 'Jl. Merdeka No. ' . rand(1, 100),
                ]
            );
        }

        // 10 Units
        for ($i = 1; $i <= 10; $i++) {
            $code = 'UNIT-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            Unit::updateOrCreate(
                ['unit_code' => $code],
                [
                    'name' => 'Fuso Canter ' . $i,
                    'nopol' => 'B ' . rand(1000, 9999) . ' XYZ',
                    'chassis_number' => 'MNH' . str_pad($i, 10, '0', STR_PAD_LEFT),
                    'year' => 2020 + rand(0, 4),
                ]
            );
        }

        // 10 Drivers
        for ($i = 1; $i <= 10; $i++) {
            $code = 'DRV-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            Driver::updateOrCreate(
                ['driver_code' => $code],
                [
                    'name' => 'Driver ' . $i,
                    'address' => 'Kp. Durian Runtuh No. ' . $i,
                    'age' => rand(25, 50),
                ]
            );
        }
    }
}
