<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company_name');
            $table->string('CEO');
            $table->string('portfo_status');
            $table->string('flow_level');
            $table->integer('progress_percentage');
            $table->string('activity_status');
            $table->date('start_date')->nullable();
            $table->integer('amount_request_accept')->nullable();
            $table->integer('amount_deposited')->nullable();
            $table->integer('amount_commitment_first_stage')->nullable();
            $table->integer('first_stage_payment')->nullable();
            $table->integer('amount_commitment_second_stage')->nullable();
            $table->integer('second_stage_payment')->nullable();
            $table->integer('amount_commitment_third_stage')->nullable();
            $table->integer('third_stage_payment')->nullable();
            $table->integer('amount_commitment_fourth_stage')->nullable();
            $table->integer('fourth_stage_payment')->nullable();
            $table->integer('amount_commitment_fifth_stage')->nullable();
            $table->integer('fifth_stage_payment')->nullable();
            $table->integer('commitment_balance')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
