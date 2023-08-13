<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'type', 'status', 'reason', 'start_time', 'end_time', 'start_date', 'company_id', 'end_date', 'payment_type', 'reject_reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
