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
        for ($i=0; $i < 10; $i++) {
            $via = ['cash', 'credit card'];
            $via = $via[array_rand($via)];
            $transaction = new Transaction;
            $transaction->cashier_id = 1;
            $transaction->buyer = $faker->name;
            $transaction->payment_via = $via;
            $transaction->card = ($via == "cash") ? "-" : $faker->creditCardNumber;
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
