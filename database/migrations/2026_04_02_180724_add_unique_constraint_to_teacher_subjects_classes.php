<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToTeacherSubjectsClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_subjects_classes', function (Blueprint $table) {
            $table->unique(['subject_id', 'class_id'], 'subject_class_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_subjects_classes', function (Blueprint $table) {
            $table->dropUnique('subject_class_unique');
        });
    }
}
