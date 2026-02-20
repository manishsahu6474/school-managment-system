<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('teachers', function (Blueprint $table) {
        // 1. Primary Key
        $table->id();

        // 2. Link to Users Table (Foreign Key)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // 3. Personal Details
        $table->string('phone')->unique();
        $table->enum('gender', ['male', 'female', 'other']);
        $table->text('address')->nullable();

        // 4. Professional Details
        $table->string('subject');
        $table->string('qualification');
        $table->integer('experience')->default(0)->comment('In years');
        $table->decimal('salary', 10, 2)->default(0);
        $table->date('joining_date');

        // 5. System Status
        $table->boolean('status')->default(1)->comment('1: Active, 0: Inactive');
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
        Schema::dropIfExists('teachers');
    }
}
