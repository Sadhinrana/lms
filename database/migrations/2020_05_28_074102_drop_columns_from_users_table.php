<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lesson_mode', 'lesson_hour', 'study_mode', 'student_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lesson_mode')->nullable()->after('role');
            $table->string('lesson_hour')->nullable()->after('lesson_mode');
            $table->string('study_mode')->nullable()->after('lesson_hour');
            $table->unsignedInteger('student_group_id')->after('study_mode')->default(99);
        });
    }
}
