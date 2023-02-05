<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ["cashier_id", "buyer", "payment_via", "card"];

    public function cashier()
    {
        return $this->belongsTo(User::class, "cashier_id");
    }

    public function detail()
    {
        return $this->hasMany(TransactionDetail::class, "transaction_id");
    }
}
