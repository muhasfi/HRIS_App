<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('transactions.leave_balances', function (Blueprint $table) {
    //         $table->id();

    //         $table->foreignId('employee_id')->constrained('master.employees')->cascadeOnDelete();

    //         $table->foreignId('leave_type_id')->constrained('transactions.leave_types')->cascadeOnDelete();

    //         $table->year('year');

    //         $table->integer('total_days');
    //         $table->integer('used_days')->default(0);
    //         $table->integer('remaining_days');

    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('leave_balances');
    // }
};
