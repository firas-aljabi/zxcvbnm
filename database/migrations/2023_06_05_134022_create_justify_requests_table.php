<?php

use App\Models\JustifyRequest;
use App\Statuses\JustifyRequestStatus;
use App\Statuses\JustifyStatus;
use App\Statuses\JustifyTypes;
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
        Schema::create('justify_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('type')->default(JustifyTypes::OTHERS);
            $table->tinyInteger('status')->default(JustifyStatus::PENDING);
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->text('reason');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->longText('medical_report_file')->nullable();
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
        Schema::dropIfExists('justify_requests');
    }
};
