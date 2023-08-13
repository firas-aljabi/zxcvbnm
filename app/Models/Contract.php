<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'start_contract_date', 'end_contract_date',
        'contract_termination_date', 'contract_termination_period', 'contract_termination_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
