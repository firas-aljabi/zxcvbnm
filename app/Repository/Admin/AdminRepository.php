<?php

namespace App\Repository\Admin;

use App\Filter\Attendance\AttendanceFilter;
use App\Filter\Employees\EmployeeFilter;
use App\Filter\Nationalalities\NationalFilter;
use App\Filter\Salary\SalaryFilter;
use App\Models\Attendance;
use App\Models\Contract;
use App\Models\Deposit;
use App\Models\EmployeeAvailableTime;
use App\Models\Holiday;
use App\Models\Nationalitie;
use App\Models\Salary;
use App\Models\Shift;
use App\Models\User;
use App\Notifications\TowFactor;
use App\Repository\BaseRepositoryImplementation;
use App\Statuses\AdversariesType;
use App\Statuses\DepositStatus;
use App\Statuses\HolidayTypes;
use App\Statuses\RewardsType;
use App\Statuses\UserTypes;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminRepository extends BaseRepositoryImplementation
{
    public function getFilterItems($filter)
    {
        $records = User::query()->where('type', UserTypes::EMPLOYEE)->where('company_id', auth()->user()->company_id);
        if ($filter instanceof EmployeeFilter) {

            $records->when(isset($filter->name), function ($records) use ($filter) {
                $records->where('name', 'LIKE', '%' . $filter->getName() . '%');
            });

            $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                $records->orderBy($filter->getOrderBy(), $filter->getOrder());
            });


            return $records->paginate($filter->per_page);
        }
        return $records->paginate($filter->per_page);
    }

    public function getSalaryFilterItems($filter)
    {
        if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type == UserTypes::HR) {
            $records = Salary::query()->where('company_id', auth()->user()->company_id);
            if ($filter instanceof SalaryFilter) {

                $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                    $records->orderBy($filter->getOrderBy(), $filter->getOrder());
                });

                $salaries = $records->with('user')->paginate($filter->per_page);
                return ['success' => true, 'data' => $salaries];
            }
            $salaries = $records->with('user')->paginate($filter->per_page);
            return ['success' => true, 'data' => $salaries];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function list_of_nationalities($filter)
    {
        if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type == UserTypes::HR) {
            $records = Nationalitie::query();
            return $records->paginate(50);
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }




    public function employees_attendances($filter)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {

            $records = User::query()->where('type', UserTypes::HR)->where('company_id', auth()->user()->company_id);
            return $records->paginate();
            $records = Attendance::query()->whereMonth('date', Carbon::now()->month);
            if ($filter instanceof AttendanceFilter) {

                $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                    $records->orderBy($filter->getOrderBy(), $filter->getOrder());
                });

                $attendances = $records->with('user')->paginate($filter->per_page);
                return ['success' => true, 'data' => $attendances];
            }
            $attendances = $records->with('user')->paginate($filter->per_page);
            return ['success' => true, 'data' => $attendances];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function getHrsList()
    {
        $records = User::query()->where('type', UserTypes::HR)->where('company_id', auth()->user()->company_id);
        return $records->paginate();
    }

    public function create_employee($data)
    {
        DB::beginTransaction();

        try {

            if (auth()->user()->type == UserTypes::HR || auth()->user()->type == UserTypes::ADMIN) {
                $user = new User();
                if (Arr::has($data, 'image')) {
                    $file = Arr::get($data, 'image');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->image = $base64_image;
                }
                if (Arr::has($data, 'id_photo')) {
                    $file = Arr::get($data, 'id_photo');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->id_photo = $base64_image;
                }
                if (Arr::has($data, 'biography')) {
                    $file = Arr::get($data, 'biography');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->biography = $base64_image;
                }
                if (Arr::has($data, 'visa')) {
                    $file = Arr::get($data, 'visa');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->visa = $base64_image;
                }
                if (Arr::has($data, 'municipal_card')) {
                    $file = Arr::get($data, 'municipal_card');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->municipal_card = $base64_image;
                }
                if (Arr::has($data, 'health_insurance')) {
                    $file = Arr::get($data, 'health_insurance');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->health_insurance = $base64_image;
                }
                if (Arr::has($data, 'passport')) {
                    $file = Arr::get($data, 'passport');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->passport = $base64_image;
                }
                if (Arr::has($data, 'employee_sponsorship')) {
                    $file = Arr::get($data, 'employee_sponsorship');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);

                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->employee_sponsorship = $base64_image;
                }
                if (Arr::has($data, 'employee_residence')) {
                    $file = Arr::get($data, 'employee_residence');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->employee_residence = $base64_image;
                }

                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                $user->departement = $data['departement'];
                $user->skills = $data['skills'];
                $user->gender = $data['gender'];
                $user->status = $data['status'];
                $user->phone = $data['phone'];
                $user->company_id = auth()->user()->company_id;
                $user->serial_number = $data['serial_number'];
                $user->work_email = $data['work_email'];
                $user->mobile = $data['mobile'];
                $user->nationalitie_id = $data['nationalitie_id'];
                $user->birthday_date = $data['birthday_date'];
                $user->marital_status = $data['marital_status'];
                $user->address = $data['address'];
                $user->guarantor = $data['guarantor'];
                $user->branch = $data['branch'];
                $user->start_job_contract = $data['start_job_contract'];
                $user->end_job_contract = $data['end_job_contract'];

                if (isset($data['end_visa'])) {
                    $user->end_visa = $data['end_visa'];
                }

                if (isset($data['end_passport'])) {
                    $user->end_passport = $data['end_passport'];
                }

                if (isset($data['end_employee_sponsorship'])) {
                    $user->end_employee_sponsorship = $data['end_employee_sponsorship'];
                }

                if (isset($data['end_municipal_card'])) {
                    $user->end_municipal_card = $data['end_municipal_card'];
                }

                if (isset($data['end_health_insurance'])) {
                    $user->end_health_insurance = $data['end_health_insurance'];
                }

                if (isset($data['end_employee_residence'])) {
                    $user->end_employee_residence = $data['end_employee_residence'];
                }
                $user->basic_salary = $data['basic_salary'];
                $user->type = UserTypes::EMPLOYEE;
                $user->permission_to_leave = $data['permission_to_leave'];
                $user->permission_to_entry = $data['permission_to_entry'];
                $user->save();
                Salary::create([
                    'user_id' => $user->id,
                    'salary' => $user->basic_salary,
                    'rewards' => 0,
                    'adversaries' => 0,
                    'housing_allowance' => 0,
                    'transportation_allowance' => 0,
                    'company_id' => auth()->user()->company_id,
                    'date' => date('Y-m-d'),
                ]);
                Contract::create([
                    'user_id' => $user->id,
                    'start_contract_date' => $data['start_job_contract'],
                    'end_contract_date' =>  $data['end_job_contract'],

                ]);
                if ((isset($data['number_of_shifts'])) && !empty($data['shifts'])) {
                    foreach ($data['shifts'] as $shift) {
                        Shift::create([
                            'user_id' => $user->id,
                            'start_time' => $shift['start_time'],
                            'end_time' => $shift['end_time'],
                            'start_break_hour' => $shift['start_break_hour'],
                            'end_break_hour' => $shift['end_break_hour'],
                        ]);
                    }
                }

                // $user->generate_code();
                // $user->notify(new TowFactor());
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }

            DB::commit();

            if ($user === null) {
                return ['success' => false, 'message' => "User was not created"];
            }

            return ['success' => true, 'data' => $user->load('nationalitie', 'shifts')];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function create_hr($data)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::ADMIN) {
                $user = new User();
                if (Arr::has($data, 'image')) {
                    $file = Arr::get($data, 'image');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->image = $base64_image;
                }
                if (Arr::has($data, 'id_photo')) {
                    $file = Arr::get($data, 'id_photo');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->id_photo = $base64_image;
                }
                if (Arr::has($data, 'biography')) {
                    $file = Arr::get($data, 'biography');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->biography = $base64_image;
                }
                if (Arr::has($data, 'visa')) {
                    $file = Arr::get($data, 'visa');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->visa = $base64_image;
                }
                if (Arr::has($data, 'municipal_card')) {
                    $file = Arr::get($data, 'municipal_card');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->municipal_card = $base64_image;
                }
                if (Arr::has($data, 'health_insurance')) {
                    $file = Arr::get($data, 'health_insurance');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->health_insurance = $base64_image;
                }
                if (Arr::has($data, 'passport')) {
                    $file = Arr::get($data, 'passport');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->passport = $base64_image;
                }
                if (Arr::has($data, 'employee_sponsorship')) {
                    $file = Arr::get($data, 'employee_sponsorship');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->employee_sponsorship = $base64_image;
                }
                if (Arr::has($data, 'employee_residence')) {
                    $file = Arr::get($data, 'employee_residence');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->employee_residence = $base64_image;
                }
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                $user->departement = $data['departement'];
                $user->skills = $data['skills'];
                $user->gender = $data['gender'];
                $user->status = $data['status'];
                $user->phone = $data['phone'];
                $user->serial_number = $data['serial_number'];
                $user->work_email = $data['work_email'];
                $user->mobile = $data['mobile'];
                $user->nationalitie_id = $data['nationalitie_id'];
                $user->birthday_date = $data['birthday_date'];
                $user->marital_status = $data['marital_status'];
                $user->address = $data['address'];
                $user->guarantor = $data['guarantor'];
                $user->branch = $data['branch'];
                $user->start_job_contract = $data['start_job_contract'];
                $user->end_job_contract = $data['end_job_contract'];

                if (isset($data['end_visa'])) {
                    $user->end_visa = $data['end_visa'];
                }

                if (isset($data['end_passport'])) {
                    $user->end_passport = $data['end_passport'];
                }

                if (isset($data['end_employee_sponsorship'])) {
                    $user->end_employee_sponsorship = $data['end_employee_sponsorship'];
                }

                if (isset($data['end_municipal_card'])) {
                    $user->end_municipal_card = $data['end_municipal_card'];
                }

                if (isset($data['end_health_insurance'])) {
                    $user->end_health_insurance = $data['end_health_insurance'];
                }

                if (isset($data['end_employee_residence'])) {
                    $user->end_employee_residence = $data['end_employee_residence'];
                }
                $user->basic_salary = $data['basic_salary'];
                $user->company_id = auth()->user()->company_id;
                $user->type = UserTypes::HR;
                $user->save();
                Salary::create([
                    'user_id' => $user->id,
                    'salary' => $user->basic_salary,
                    'rewards' => 0,
                    'adversaries' => 0,
                    'housing_allowance' => 0,
                    'transportation_allowance' => 0,
                    'company_id' => auth()->user()->company_id,
                    'date' => date('Y-m-d'),
                ]);
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }

            DB::commit();

            if ($user === null) {
                return ['success' => false, 'message' => "User was not created"];
            }

            return ['success' => true, 'data' => $user];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function create_admin($data)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::SUPER_ADMIN) {
                $user = new User();
                if (Arr::has($data, 'image')) {
                    $file = Arr::get($data, 'image');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->image = $base64_image;
                }
                if (Arr::has($data, 'id_photo')) {
                    $file = Arr::get($data, 'id_photo');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->id_photo = $base64_image;
                }
                if (Arr::has($data, 'biography')) {
                    $file = Arr::get($data, 'biography');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $user->biography = $base64_image;
                }
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                $user->gender = $data['gender'];
                $user->phone = $data['phone'];
                $user->company_id = $data['company_id'];
                $user->serial_number = $data['serial_number'];
                $user->work_email = $data['work_email'];
                $user->mobile = $data['mobile'];
                $user->nationalitie_id = $data['nationalitie_id'];
                $user->birthday_date = $data['birthday_date'];
                $user->marital_status = $data['marital_status'];
                $user->address = $data['address'];
                $user->branch = $data['branch'];
                $user->type = UserTypes::ADMIN;
                $user->save();
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }

            DB::commit();

            if ($user === null) {
                return ['success' => false, 'message' => "User was not created"];
            }

            return ['success' => true, 'data' => $user];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function update_salary($data)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($data['user_id']);
            if (auth()->user()->type == UserTypes::HR || auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $user->company_id) {

                $user->update([
                    'basic_salary' => $data['new_salary'],
                ]);
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }

            DB::commit();

            return ['success' => true, 'data' => $user];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function update_employment_contract($data)
    {
        DB::beginTransaction();
        $user = User::findOrFail($data['user_id']);
        $contract = Contract::where('user_id', $data['user_id'])->first();
        try {

            if (auth()->user()->type == UserTypes::HR || auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $user->company_id) {

                $date = \Carbon\Carbon::create($user->end_job_contract);
                $newDate = $date->copy()->addMonths(6)->format('Y-m-d');

                if (isset($data['new_date'])) {
                    $user->update([
                        'end_job_contract' => $data['new_date'],
                    ]);
                    $contract->update([
                        'end_contract_date' => $data['new_date'],
                    ]);
                } else {
                    $user->update([
                        'end_job_contract' => $newDate,
                    ]);
                    $contract->update([
                        'end_job_contract' => $newDate,
                    ]);
                }
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }

            DB::commit();

            return ['success' => true, 'data' => $user];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function termination_employees_contract($data)
    {
        $user = User::findOrFail($data['user_id']);
        $contract = Contract::where('user_id', $data['user_id'])->first();

        $hasUnpaidDeposits = $user->deposits()->where('status', DepositStatus::UN_PAID)->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->exists();

        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::HR || auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $user->company_id) {

                if (!$hasUnpaidDeposits) {

                    $date = \Carbon\Carbon::create($user->end_job_contract);
                    $newDateSixMonth = $date->copy()->addMonths(6)->format('Y-m-d');
                    $newDateSixMonthString = \Carbon\Carbon::createFromFormat('Y-m-d', $newDateSixMonth);

                    $newDateYear = $date->copy()->addMonths(12)->format('Y-m-d');
                    $newDateYearString = \Carbon\Carbon::createFromFormat('Y-m-d', $newDateYear);

                    $diffMonths = $newDateSixMonthString->diff($date)->format('%m months, %d days');
                    $diffYears = $newDateYearString->diff($date)->format('%y years,%m months, %d days');

                    if (isset($data['contract_termination_period']) && $data['contract_termination_period'] == 1) {
                        $contract->update([
                            'end_contract_date' => $newDateSixMonth,
                            "contract_termination_date" => date('Y-m-d'),
                            "contract_termination_period" => $diffMonths,
                            "contract_termination_reason" => $data['contract_termination_reason'],
                        ]);
                    } elseif (isset($data['contract_termination_period']) && $data['contract_termination_period'] == 2) {
                        $contract->update([
                            'end_contract_date' => $newDateYear,
                            "contract_termination_date" => date('Y-m-d'),
                            "contract_termination_period" => $diffYears,
                            "contract_termination_reason" => $data['contract_termination_reason'],
                        ]);
                    } else {
                        $contract->update([
                            'end_contract_date' => date('Y-m-d'),
                            "contract_termination_date" => date('Y-m-d'),
                            "contract_termination_period" => 'Undefined',
                            "contract_termination_reason" => $data['contract_termination_reason'],
                        ]);
                    }
                } else {
                    return ['success' => false, 'message' => "You Cannot Terminate This Employee Because This Employee Have Deposit Unpaided."];
                }
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
            DB::commit();
            return ['success' => true, 'data' => $contract->load('user')];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function check_in_attendance($data)
    {
        DB::beginTransaction();

        try {
            $userId = auth()->user()->id;
            $date = date('Y-m-d');
            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('date', $date)
                ->where('login_time', '!=', null)
                ->where('logout_time', '=', null)
                ->first();
            if ($existingAttendance) {
                return ['success' => false, 'message' => 'Attendance already exists'];
            }

            $holidays = Holiday::where('date', $date)
                ->orWhere(function ($query) use ($date) {
                    $query->where('start_date', '<=', $date)
                        ->where('end_date', '>=', $date)
                        ->orWhere('start_date', '=', $date)
                        ->orWhere('end_date', '=', $date);
                })->get();

            foreach ($holidays as  $holiday) {
                if ($holiday && $holiday->type == HolidayTypes::WEEKLY) {
                    // Today is a weekly holiday, do not allow attendance
                    return ['success' => false, 'message' => 'Today is a weekly holiday'];
                } elseif ($holiday && $holiday->type == HolidayTypes::ANNUL) {
                    // Today is an annual holiday, check if it falls within the start and end dates
                    $startDate = Carbon::parse($holiday->start_date);
                    $endDate = Carbon::parse($holiday->end_date);
                    if ($date >= $startDate && $date <= $endDate) {
                        // Today is an annual holiday, do not allow attendance
                        return ['success' => false, 'message' => 'Today is an annual holiday'];
                    }
                }
            }

            $shifts = auth()->user()->shifts;
            $shiftMatched = false; // variable to keep track of whether any shift matched the current time
            foreach ($shifts as $shift) {
                $start_time = Carbon::parse(date('Y-m-d ') . $shift['start_time'], 'Asia/Damascus')->timezone('Asia/Damascus');
                $end_time = Carbon::createFromFormat('H:i:s', $shift['end_time'])->timezone('Asia/Damascus');
                $current_time = Carbon::now();

                if ($current_time->between($start_time, $end_time)) {
                    $attendance  = new Attendance();
                    $attendance->user_id = $userId;
                    $attendance->date = $date;
                    $attendance->status = $data['check_in'];
                    $attendance->login_time = $current_time->format('H:i:s');
                    $attendance->save();
                    $shiftMatched = true; // set the variable to true if a shift matched the current time
                }
            }

            if (!$shiftMatched) {
                return ['success' => false, 'message' => 'Current time is outside your shifts'];
            }

            DB::commit();

            return ['success' => true, 'data' => $attendance->load('user')];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function check_out_attendance($data)
    {
        DB::beginTransaction();

        try {

            $userId = auth()->user()->id;
            $date = date('Y-m-d');

            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $date)
                ->whereNotNull('login_time')
                ->latest('login_time')
                ->first();

            $attendance->update([
                'logout_time' => Carbon::now()->format('H:i:s'),
            ]);

            DB::commit();

            return ['success' => true, 'data' => $attendance->load('user')];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function determine_working_hours($data)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::SUPER_ADMIN || auth()->user()->type == UserTypes::ADMIN) {
                $employeeAvailableTime = new EmployeeAvailableTime();
                $employeeAvailableTime->user_id = $data['user_id'];
                $employeeAvailableTime->hours_daily = $data['hours_daily'];
                $employeeAvailableTime->days_annual = $data['days_annual'];
                $employeeAvailableTime->company_id = auth()->user()->company_id;

                $employeeAvailableTime->save();
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
            DB::commit();

            if ($employeeAvailableTime === null) {
                return ['success' => false, 'message' => "User was not created"];
            }

            return ['success' => true, 'data' => $employeeAvailableTime];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function reward_adversaries_salary($data)
    {
        $salary = Salary::where('user_id', $data['user_id'])->first();
        if (isset($data['rewards_type']) && $data['rewards_type'] == RewardsType::NUMBER) {
            $salary->update([
                'rewards' => $data['rewards'],
                'salary' =>  $salary->salary + $data['rewards'],
                'date' => date('Y-m-d'),
            ]);
        } elseif (isset($data['rewards_type']) && $data['rewards_type'] == RewardsType::RATE) {

            $oldSalary = $salary->salary;
            $rewardPercentage = $data['rewards'];
            $rewardAmount = ($oldSalary * $rewardPercentage) / 100;
            $totalSalary = $oldSalary + $rewardAmount;

            $salary->update([
                'rewards' => $data['rewards'],
                'salary' => $totalSalary,
                'date' => date('Y-m-d'),

            ]);
        }
        if (isset($data['adversaries_type']) && $data['adversaries_type'] == AdversariesType::NUMBER) {
            $salary->update([
                'adversaries' => $data['adversaries'],
                'salary' =>  $salary->salary - $data['adversaries'],
                'date' => date('Y-m-d'),
            ]);
        } elseif (isset($data['adversaries_type']) && $data['adversaries_type'] == AdversariesType::RATE) {
            $oldSalary = $salary->salary;
            $adversariesPercentage = $data['adversaries'];
            $adversariesAmount = ($oldSalary * $adversariesPercentage) / 100;
            $totalSalary = $oldSalary - $adversariesAmount;

            $salary->update([
                'adversaries' => $data['adversaries'],
                'salary' => $totalSalary,
                'date' => date('Y-m-d'),
            ]);
        } elseif (isset($data['housing_allowance'])) {
            $salary->update([
                'housing_allowance' => $data['housing_allowance'],
                'salary' =>  $salary->salary + $data['housing_allowance'],
                'date' => date('Y-m-d'),
            ]);
        } elseif (isset($data['transportation_allowance'])) {
            $salary->update([
                'transportation_allowance' => $data['transportation_allowance'],
                'salary' =>  $salary->salary + $data['transportation_allowance'],
                'date' => date('Y-m-d'),
            ]);
        }


        return ['success' => true, 'data' => $salary->load('user')];
    }

    public function profile()
    {
        return User::where('id', Auth::id())->with(['salaries', 'availableTime', 'vacationRequestsApproved', 'attendancesMonthly'])->first();
    }

    public function model()
    {
        return User::class;
    }
}
