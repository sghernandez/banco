<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ac_transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'amount',        
        'type',
    ];    
}
