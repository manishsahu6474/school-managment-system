<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableForLinking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            //
            $table->dropColumn(['name','email']);
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            //new coloumn enter acadmic 
            $table->string('roll_no')->nullable()->unique()->after('user_id');
            $table->string('father_name')->nullable()->after('class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'roll_no', 'father_name']);
            
            // Wapas purani fields add karna (Rollback ke liye)
            $table->string('name')->nullable();
            $table->string('email')->nullable();
        });
    }
}
