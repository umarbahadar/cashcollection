<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $fillable = ['name'];

    // Account can have many ledger entries
    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }
}
