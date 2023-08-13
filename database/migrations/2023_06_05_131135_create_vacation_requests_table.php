<?php

use App\Statuses\PaymentType;
use App\Statuses\VacationRequestStatus;
use App\Statuses\VacationRequestTypes;
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
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('status')->default(VacationRequestStatus::PENDING);
            $table->tinyInteger('type')->default(VacationRequestTypes::HOURLY);
            $table->tinyInteger('payment_type')->default(PaymentType::UNPAYMENT);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->text('reason');
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacation_requests');
    }
};
