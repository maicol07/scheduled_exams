<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lists')) {
            Schema::create('lists', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('classroom_id');
                $table->tinyText('name');
                $table->text('description')->nullable();
                $table->ipAddress('image')->nullable();
                $table->char('code', 5);
                $table->enum('type', ['AUTO', 'FROM_START_DATE', 'MANUAL'])->default('MANUAL');
                $table->date('start_date')->nullable();
                $table->json('weekdays');
                $table->integer('students_at_once')->nullable();
                $table->timestamps();

                $table->foreign('classroom_id')->references('id')->on('classrooms');
            });
        } else {
            Schema::table('lists', function (Blueprint $table) {
                $table->renameColumn('ID', 'id');
                $table->id()->change();
                $table->bigInteger('classroom_id')->change();
                $table->tinyText('name')->change();
                $table->text('description')->nullable()->change();
                $table->ipAddress('image')->nullable()->change();
                $table->char('code', 5)->after('image')->change();
                $table->json('weekdays')->change();
                $table->renameColumn('quantity', 'students_at_once');
                $table->timestamps();

                $table->foreign('classroom_id')->references('id')->on('classrooms');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lists');
    }
}
