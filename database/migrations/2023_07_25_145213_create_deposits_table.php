<?php

use App\Statuses\DepositStatus;
use App\Statuses\DepositType;
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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('status')->default(DepositStatus::PENDING)->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('car_number')->nullable();
            $table->string('car_model')->nullable();
            $table->string('manufacturing_year')->nullable();
            $table->string('Mechanic_card_number')->nullable();
            $table->longText('car_image')->nullable();
            $table->string('laptop_type')->nullable();
            $table->string('serial_laptop_number')->nullable();
            $table->string('laptop_color')->nullable();
            $table->longText('laptop_image')->nullable();
            $table->string('serial_mobile_number')->nullable();
            $table->string('mobile_color')->nullable();
            $table->longText('mobile_image')->nullable();
            $table->text('reason_reject')->nullable();
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
        Schema::dropIfExists('deposits');
    }
};
