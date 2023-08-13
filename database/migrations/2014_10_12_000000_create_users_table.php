<?php

use App\Statuses\EmployeeStatus;
use App\Statuses\PermissionType;
use App\Statuses\UserTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('work_email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('serial_number')->unique();
            $table->bigInteger('nationalitie_id')->unsigned();
            $table->foreign('nationalitie_id')->references('id')->on('nationalities')->onDelete('cascade');
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('birthday_date')->nullable();
            $table->enum('marital_status', ['single', 'married'])->nullable();
            $table->string('address')->nullable();
            $table->string('guarantor')->nullable();
            $table->string('branch')->nullable();
            $table->string('departement')->nullable();
            $table->string('position')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->tinyInteger('type')->default(UserTypes::EMPLOYEE);
            $table->tinyInteger('status')->default(EmployeeStatus::ON_DUTY);
            $table->longText('skills')->nullable();
            $table->date('start_job_contract')->nullable();
            $table->date('end_job_contract')->nullable();
            $table->longText('image')->nullable();
            $table->longText('id_photo')->nullable();
            $table->longText('biography')->nullable();
            $table->longText('employee_sponsorship')->nullable();
            $table->date('end_employee_sponsorship')->nullable();
            $table->longText('employee_residence')->nullable();
            $table->date('end_employee_residence')->nullable();
            $table->longText('visa')->nullable();
            $table->date('end_visa')->nullable();
            $table->longText('passport')->nullable();
            $table->date('end_passport')->nullable();
            $table->longText('municipal_card')->nullable();
            $table->date('end_municipal_card')->nullable();
            $table->longText('health_insurance')->nullable();
            $table->date('end_health_insurance')->nullable();
            $table->decimal('basic_salary', 8, 2)->default(0);
            $table->tinyInteger('permission_to_entry')->default(PermissionType::FALSE);
            $table->tinyInteger('permission_to_leave')->default(PermissionType::FALSE);
            $table->string('code')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
