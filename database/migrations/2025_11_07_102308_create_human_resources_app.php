<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        // Schema::create('master.departments', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->unique();
        //     $table->text('description')->nullable();
        //     $table->string('status');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('master.shifts', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name'); // Contoh: "Shift Pagi", "Shift Malam"
        //     $table->time('start_time'); // Jam mulai shift
        //     $table->time('end_time');   // Jam selesai shift
        //     $table->integer('late_tolerance')->default(0); // Toleransi telat dalam menit
        //     $table->timestamps();
        // });
        
        // Schema::create('master.roles', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title')->unique();
        //     $table->text('description')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('master.employees', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')
        //             ->unique() // supaya benar-benar 1 user = 1 employee
        //             ->constrained('auth.users')
        //             ->cascadeOnDelete(); 
        // $table->foreignId('shift_id')
                // ->nullable()
                // ->constrained('master.shifts')
                // ->onDelete('set null');
        //     $table->string('fullname');
        //     // $table->string('email')->unique();
        //     $table->string('phone_number');
        //     $table->string('address');
        //     $table->date('birth_date');
        //     $table->date('hire_date');

        //     $table->foreignId('department_id')
        //           ->constrained('master.departments')
        //           ->cascadeOnDelete();

        //     $table->foreignId('role_id')
        //           ->constrained('master.roles')
        //           ->restrictOnDelete();

        //     $table->string('status');
        //     $table->decimal('salary', 12, 2);

        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('master.employee_schedules', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('employee_id')->constrained('master.employees')->onDelete('cascade');
        //     $table->tinyInteger('day_of_week'); // 0=Sunday, 1=Monday, ..., 6=Saturday
        //     $table->time('start_time');
        //     $table->time('end_time');
        //     $table->timestamps();
        // });
        
        // Schema::create('transactions.tasks', function (Blueprint $table) {
        //     $table->id();

        //     $table->string('title');
        //     $table->text('description')->nullable();

        //     $table->foreignId('assigned_to')
        //           ->constrained('master.employees')
        //           ->cascadeOnDelete();

        //     $table->date('due_date');
        //     $table->string('status');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('transactions.payrolls', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('employee_id')
        //           ->constrained('master.employees')
        //           ->cascadeOnDelete();

        //     $table->decimal('salary', 12, 2);
        //     $table->decimal('bonuses', 12, 2)->default(0);
        //     $table->decimal('deductions', 12, 2)->default(0);
        //     $table->decimal('net_salary', 12, 2);

            // $table->integer('total_alpha')->default(0);
            // $table->integer('total_late_minutes')->default(0);

            // $table->decimal('deduction_alpha', 15, 2)->default(0);
            // $table->decimal('deduction_late', 15, 2)->default(0);

        //     $table->date('pay_date');

        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        
        // Schema::create('transactions.presences', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('employee_id')
        //           ->constrained('master.employees')
        //           ->cascadeOnDelete();

        //     $table->date('date');
        //     $table->dateTime('check_in')->nullable();
        //     $table->dateTime('check_out')->nullable();
        //     $table->decimal('check_in_lat')->nullable();
        //     $table->decimal('check_in_long')->nullable();
        //     $table->decimal('check_out_lat')->nullable();
        //     $table->decimal('check_out_long')->nullable();
        //     $table->string('status');
        //     $table->integer('late_minutes')->default(0);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('transactions.leave_requests', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('employee_id')
        //           ->constrained('master.employees')
        //           ->cascadeOnDelete();

        //     $table->string('leave_type');
        //     $table->date('start_date');
        //     $table->date('end_date');
        //     $table->string('status');
        //     $table->text('reason')->nullable();

        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('transactions.leave_requests');
        // Schema::dropIfExists('transactions.presences');
        // Schema::dropIfExists('transactions.payrolls');
        // Schema::dropIfExists('transactions.tasks');

        // Schema::dropIfExists('master.employees');
        // Schema::dropIfExists('master.roles');
        // Schema::dropIfExists('master.departments');
    }
};
