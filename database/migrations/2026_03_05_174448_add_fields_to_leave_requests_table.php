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
    //     Schema::table('transactions.leave_requests', function (Blueprint $table) {
    //         $table->integer('total_days')->nullable();
    //         $table->unsignedBigInteger('approved_by')->nullable();
    //         $table->timestamp('approved_at')->nullable();
    //         $table->text('rejected_reason')->nullable();
    //         $table->foreign('approved_by')
    //               ->references('id')
    //               ->on('master.employees')
    //               ->nullOnDelete();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::table('transactions.leave_requests', function (Blueprint $table) {
    //         $table->dropForeign(['approved_by']);

    //         $table->dropColumn([
    //             'total_days',
    //             'approved_by',
    //             'approved_at',
    //             'rejected_reason'
    //         ]);
    //     });
    // }
};
