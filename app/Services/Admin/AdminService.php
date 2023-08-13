<?php

namespace App\Services\Admin;

use App\Filter\Attendance\AttendanceFilter;
use App\Filter\Employees\EmployeeFilter;
use App\Filter\Nationalalities\NationalFilter;
use App\Filter\Salary\SalaryFilter;
use App\Interfaces\Admin\AdminServiceInterface;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Salary;
use App\Models\User;
use App\Query\Admin\AdminDashboardQuery;
use App\Repository\Admin\AdminRepository;
use App\Statuses\UserTypes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminService implements AdminServiceInterface
{


    public function __construct(private AdminRepository $adminRepository, private AdminDashboardQuery $adminDashboardQuery)
    {
    }

    public function create_employee($data)
    {
        return $this->adminRepository->create_employee($data);
    }
    public function create_hr($data)
    {
        return $this->adminRepository->create_hr($data);
    }

    public function create_admin($data)
    {
        return $this->adminRepository->create_admin($data);
    }

    public function check_in_attendance($data)
    {
        return $this->adminRepository->check_in_attendance($data);
    }

    public function check_out_attendance($data)
    {
        return $this->adminRepository->check_out_attendance($data);
    }


    public function determine_working_hours($data)
    {
        return $this->adminRepository->determine_working_hours($data);
    }


    public function reward_adversaries_salary($data)
    {
        return $this->adminRepository->reward_adversaries_salary($data);
    }

    public function update_salary($data)
    {
        return $this->adminRepository->update_salary($data);
    }

    public function update_employment_contract($data)
    {
        return $this->adminRepository->update_employment_contract($data);
    }

    public function termination_employees_contract($data)
    {
        return $this->adminRepository->termination_employees_contract($data);
    }


    public function deleteEmployee(int $id)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {
            return  User::where('id', $id)->forceDelete();
        } else {
            return "Unauthorized";
        }
    }

    public function getDashboardData()
    {
        return $this->adminDashboardQuery->getDashboardData();
    }
    public function getHrsList()
    {
        return $this->adminRepository->getHrsList();
    }


    public function getEmployees(EmployeeFilter $employeeFilter = null)
    {
        if ($employeeFilter != null)
            return $this->adminRepository->getFilterItems($employeeFilter);
        else
            return $this->adminRepository->paginate();
    }

    public function employees_salaries(SalaryFilter $salaryFilter = null)
    {
        if ($salaryFilter != null)
            return $this->adminRepository->getSalaryFilterItems($salaryFilter);
        else
            return $this->adminRepository->paginate();
    }

    public function employees_attendances(AttendanceFilter $attendanceFilter = null)
    {
        if ($attendanceFilter != null)
            return $this->adminRepository->employees_attendances($attendanceFilter);
        else
            return $this->adminRepository->paginate();
    }

    public function list_of_nationalities(NationalFilter $nationalFilter = null)
    {
        if ($nationalFilter != null)
            return $this->adminRepository->list_of_nationalities($nationalFilter);
        else
            return $this->adminRepository->paginate();
    }



    public function showEmployee(int $id)
    {
        return $this->adminRepository->getById($id)->load(['salaries', 'availableTime', 'vacationRequestsApproved', 'attendancesMonthly', 'nationalitie']);
        // $currentMonth = date('m');
        // $currentYear = date('Y');
        // $currentDay = date('d');



        // $userSalary = User::findOrFail($id)->basic_salary;

        // $holidays = Holiday::where(function ($query) use ($currentMonth, $currentYear) {
        //     $query->whereMonth('start_date', $currentMonth)
        //         ->whereYear('start_date', $currentYear);

        //     $query->orWhere(function ($query) use ($currentMonth, $currentYear) {
        //         $query->whereMonth('end_date', $currentMonth)
        //             ->whereYear('end_date', $currentYear);
        //     });

        //     $query->orWhere(function ($query) use ($currentMonth, $currentYear) {
        //         $query->whereMonth('date', $currentMonth)
        //             ->whereYear('date', $currentYear);
        //     });
        // })->get();



        // // dd($holidays);

        // $workingDays = 0;
        // $period = CarbonPeriod::create($currentYear . '-' . $currentMonth . '-01', '1 day', $currentYear . '-' . $currentMonth . '-31');
        // foreach ($period as $date) {
        //     if ($date->day <= $currentDay) {
        //         $isHoliday = $holidays->contains(function ($holiday, $key) use ($date) {
        //             return $holiday->date == $date->toDateString();
        //         });
        //         if (!$isHoliday) {
        //             $workingDays++;
        //         }
        //     }
        // }

        // dd($workingDays);

        // $dailySalary = $userSalary / $workingDays;

        // $daysWorked = 0;

        // $attendances = Attendance::whereYear('date', $currentYear)
        //     ->whereMonth('date', $currentMonth)
        //     ->where('status', 1)
        //     ->orderBy('date')
        //     ->orderBy('login_time')
        //     ->get();

        // foreach ($attendances as $attendance) {
        //     $firstAttendance = Attendance::where('user_id', $attendance->user_id)
        //         ->whereDate('date', $attendance->date)
        //         ->orderBy('login_time')
        //         ->first();

        //     if ($attendance->id == $firstAttendance->id && $attendance->date <= $currentDay) {  // consider only days until the current day
        //         $daysWorked++;
        //     }
        // }

        // $salaryAmount = ceil($daysWorked * $dailySalary);

        // dd($salaryAmount);

        // $salary = Salary::firstOrCreate([
        //     'user_id' => $id,
        //     'month' => $currentMonth,
        //     'year' => $currentYear,
        // ]);

        // $salary->amount = $salaryAmount;
        // $salary->save();

        // return $salary;
    }


    public static function careteAttendance()
    {

        $today = Carbon::today()->format('Y-m-d');
        $userId = Auth::id();

        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->exists();

        // If an attendance record does not exist, create a new one
        if (!$existingAttendance) {
            Attendance::create([
                'user_id' => $userId,
                'date' => $today,
                'status' => 1
            ]);
        }
    }

    public static function AttendancePercentage($id)
    {

        $startDate = date('Y-m-01');

        $endDate = date('Y-m-d');

        $totalDays = date_diff(date_create($startDate), date_create($endDate))->format('%a');

        $attendanceDays = DB::table('attendances')
            ->where('user_id', $id)
            ->where('status', 1)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
        if ($totalDays != 0) {
            $percentage = ($attendanceDays / $totalDays) * 100;
            return number_format($percentage);
        } else {
            return 0;
        }
    }


    public function profile()
    {
        return $this->adminRepository->profile();
    }
}
