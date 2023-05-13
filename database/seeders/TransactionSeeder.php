<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $date = array();
        for ($i=0; $i < 10; $i++) { 
            array_push($date, $faker->dateTimeBetween('-1 week', '+1 week'));
        }
        $date_sort = sort($date);
        for ($i=0; $i < 10; $i++) {
            $transaction = new Transaction;
            $transaction->cashier_id = 1;
            $transaction->payment_via = (rand(0, 1)) ? "cash" : "credit card";
            $transaction->created_at = $faker->dateTimeBetween('-1 week', '+1 week');
            $transaction->save();
        };
        for ($i=0; $i < 20; $i++) { 
            $transaction_detail = new TransactionDetail;
            $transaction_detail->transaction_id = rand(1, 10);
            $transaction_detail->product_id = rand(1, 30);
            $transaction_detail->qty = rand(1, 5);
            $transaction_detail->save();
        }
    }
}
