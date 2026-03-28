<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Branch;
use App\Models\User;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Driver;
use App\Models\JobOrder;
use App\Models\JobOrderPayment;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Haus Dulu (Truncate kawan)
        Schema::disableForeignKeyConstraints();
        JobOrderPayment::truncate();
        JobOrder::truncate();
        Customer::truncate();
        Unit::truncate();
        Driver::truncate();
        User::truncate();
        Branch::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Create Super Admin kawan
        User::create([
            'name' => 'Arul',
            'email' => 'arul',
            'password' => Hash::make('arul'),
            'role' => 'super admin',
        ]);

        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang', 'Makassar', 'Palembang', 'Yogyakarta', 'Denpasar', 'Banjarmasin'];

        for ($i = 1; $i <= 10; $i++) {
            // 3. Create Branch kawan
            $cityName = $cities[$i-1];
            $branch = Branch::create([
                'name' => 'Rental ' . $cityName . ' kawan',
                'code' => 'SPJ-' . strtoupper(substr($cityName, 0, 3)) . '-' . $i,
                'address' => 'Jl. Merdeka No. ' . $i . ', ' . $cityName,
                'phone' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'header_color' => '#' . substr(md5($cityName), 0, 6),
            ]);

            // 4. Create Admin Cabang kawan
            $admin = User::create([
                'name' => 'Admin ' . $cityName,
                'email' => 'admin' . $i . '@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin cabang',
                'branch_id' => $branch->id,
            ]);

            // 5. Create Staff User kawan
            $staff = User::create([
                'name' => 'Staff ' . $cityName,
                'email' => 'user' . $i . '@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'branch_id' => $branch->id,
            ]);

            // Seed 2@gmail.com for the user's specific test kawan
            if ($i == 2) {
                $staff->update(['email' => '2@gmail.com']);
            }

            // 6. Create Supporting Data per Branch kawan
            $customer = Customer::create([
                'name' => 'PT. Sejahtera ' . $cityName,
                'phone' => '021' . str_pad($i, 7, '1', STR_PAD_LEFT),
                'branch_id' => $branch->id,
            ]);

            $unit = Unit::create([
                'unit_code' => 'UNI-' . $i,
                'name' => 'Toyota Hiace ' . $i,
                'nopol' => 'B ' . rand(1000, 9999) . ' ' . strtoupper(substr($cityName, 0, 2)),
                'chassis_number' => 'CHS' . rand(100000, 999999),
                'year' => rand(2020, 2024),
                'branch_id' => $branch->id,
            ]);

            $driver = Driver::create([
                'driver_code' => 'DRV-' . $i,
                'name' => 'Supir ' . $i . ' ' . $cityName,
                'address' => 'Alamat Supir ' . $i,
                'age' => rand(25, 50),
                'branch_id' => $branch->id,
            ]);

            // 7. Create 10 Job Orders per Staff User kawan
            for ($j = 1; $j <= 10; $j++) {
                $days = rand(1, 5);
                $pricePerDay = rand(5, 15) * 100000;
                $totalPrice = $days * $pricePerDay;
                
                // Variasi status pembayaran kawan
                $paymentStatus = ($j % 2 == 0) ? 'Lunas' : 'DP';
                $isClosed = ($j <= 3); // 3 orders closed kawan

                $jobOrder = JobOrder::create([
                    'spj_number' => 'SPJ-' . $branch->id . '-' . $staff->id . '-' . $j,
                    'customer_id' => $customer->id,
                    'unit_id' => $unit->id,
                    'driver_id' => $driver->id,
                    'user_id' => $staff->id,
                    'branch_id' => $branch->id,
                    'destination' => 'Tujuan ' . $j . ' ke ' . $cityName,
                    'departure_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                    'departure_time' => '08:00',
                    'return_date' => Carbon::now()->subDays(rand(1, 10))->format('Y-m-d'),
                    'duration' => $days,
                    'days_count' => $days,
                    'price_per_day' => $pricePerDay,
                    'total_price' => $totalPrice,
                    'payment_status' => $paymentStatus,
                    'is_closed' => $isClosed,
                    'closing_date' => $isClosed ? Carbon::now()->format('Y-m-d') : null,
                    'closing_number' => $isClosed ? 'CLS-' . $j . '-' . rand(100, 999) : null,
                ]);

                // Create random payments kawan
                if ($paymentStatus == 'Lunas') {
                    JobOrderPayment::create([
                        'job_order_id' => $jobOrder->id,
                        'amount' => $totalPrice,
                        'method' => 'Transfer',
                        'payment_date' => $jobOrder->departure_date,
                        'user_id' => $staff->id,
                    ]);
                } else {
                    JobOrderPayment::create([
                        'job_order_id' => $jobOrder->id,
                        'amount' => $totalPrice * 0.5,
                        'method' => 'Cash',
                        'payment_date' => $jobOrder->departure_date,
                        'user_id' => $staff->id,
                    ]);
                }
            }
        }
    }
}
