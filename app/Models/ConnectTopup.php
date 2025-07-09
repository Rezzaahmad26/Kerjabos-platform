<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectTopup extends Model
{
    protected $fillable = [
        'user_id',
        'connect_amount',
        'price',
        'is_paid',
        'payment_proof',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'connect_topup_id');
    }

}
