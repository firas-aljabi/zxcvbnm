<?php

namespace App\Models;

use App\Statuses\VacationRequestStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password', 'work_email', 'mobile', 'phone', 'serial_number', 'nationalitie_id',
        'birthday_date', 'marital_status', 'address', 'guarantor', 'position', 'branch', 'departement', 'gender', 'type', 'status', 'skills',
        'start_job_contract', 'end_job_contract', 'image', 'id_photo', 'biography',
        'employee_sponsorship', 'end_employee_sponsorship', 'visa', 'end_visa',
        'passport', 'end_passport', 'municipal_card', 'end_municipal_card', 'health_insurance', 'end_health_insurance',
        'basic_salary', 'company_id', 'permission_to_entry', 'permission_to_leave', 'employee_residence', 'end_employee_residence',
        'code', 'expired_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function attendancesMonthly()
    {
        $currentMonth = Carbon::now()->month;

        return $this->hasMany(Attendance::class)
            ->whereMonth('date', $currentMonth);
    }
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class);
    }

    public function justifyRequests()
    {
        return $this->hasMany(JustifyRequest::class);
    }


    public function availableTime()
    {
        return $this->hasOne(EmployeeAvailableTime::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function vacationRequestsApproved()
    {
        return $this->hasMany(VacationRequest::class)->where('status', VacationRequestStatus::APPROVED);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function nationalitie()
    {
        return $this->belongsTo(Nationalitie::class, 'nationalitie_id');
    }


    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function employeesRequests()
    {
        return $this->hasMany(EmployeeRequest::class);
    }

    public function generate_code()
    {
        $this->timestamps = false;
        $this->code = rand(1000, 9999);
        $this->expired_at = now()->addMinute(10);
        $this->save();
    }
    public function reset_code()
    {
        $this->timestamps = false;
        $this->code = null;
        $this->expired_at = null;
        $this->save();
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
