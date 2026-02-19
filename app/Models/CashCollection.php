<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashCollection extends Model
{
     use HasFactory;
     use SoftDeletes;

    protected $fillable = ['agent_id', 'collection_date', 'amount', 'notes'];

    // The agent who submitted the collection
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // All ledger entries related to this collection
    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }


    
}
