<?php

namespace Database\Seeders;

use App\Models\Payment\PaymentProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentProvider::create([
            'id' => 1,
            'label' => 'Cash on delivery',
            'name' => 'cod',
            'description' => 'Goods must be paid for at the time of delivery',
            'status' => 1,
        ]);
        PaymentProvider::create([
            'id' => 2,
            'label' => 'Stripe',
            'name' => 'stripe',
            'status' => 1,
        ]);
        PaymentProvider::create([
            'id' => 3,
            'label' => 'SSL Commerz',
            'name' => 'sslcommerz',
            'status' => 1,
        ]);
    }
}
