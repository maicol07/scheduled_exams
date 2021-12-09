<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nette\Utils\Json;

class CreateClassroomsStudentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms_students', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_admin');
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Migration from v1
        $tables = ['users', 'students', 'admins'];
        if (Schema::hasColumns('classrooms', $tables)) {
            $classrooms = DB::table('classrooms')->get(array_merge(['ID'], $tables));

            $classrooms_students_table = DB::table('classrooms_students');
            foreach ($classrooms as $data) {
                $students = Json::decode($data->students);
                $admins = Json::decode($data->admins);

                foreach ($students as $student) {
                    $student_model = null;
                    $user = User::find($student->user_id);

                    if ($user === null) {
                        $student_model = new Student();
                        $student_model->name = $student->name;
                        $student_model->save();
                    }

                    $classrooms_students_table->insert([
                        'classroom_id' => $data->ID,
                        'student_id' => $student_model?->id,
                        'user_id' => $user?->id,
                        'is_admin' => in_array($user?->id, $admins, true)
                    ]);
                }
            }

            Schema::table('classrooms', function (Blueprint $table) {
                $table->removeColumn('users');
                $table->removeColumn('admins');
                $table->removeColumn('students');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms_students');
    }
}
