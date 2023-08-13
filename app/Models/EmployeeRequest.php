<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    use HasFactory;
    protected $table = 'employee_requests';
    protected $fillable = ['user_id', 'type', 'date', 'reason', 'attachments', 'status', 'company_id', 'reject_reason'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
