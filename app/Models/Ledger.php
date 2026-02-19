<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{


    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cash_collection_id',
        'account_id',
        'type',
        'amount',
        'description',
    ];

    // The cash collection this ledger belongs to (optional)
    public function cashCollection()
    {
        return $this->belongsTo(CashCollection::class);
    }

    // The account this ledger entry belongs to
    public function account()
    {
        return $this->belongsTo(Account::class);
    }


    
}
