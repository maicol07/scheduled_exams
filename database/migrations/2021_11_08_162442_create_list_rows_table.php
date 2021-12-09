<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListRowsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('lists_rows')) {
            Schema::create('lists_rows', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('list_id');
                $table->bigInteger('student_id')->nullable();
                $table->bigInteger('user_id')->nullable();
                $table->date('date')->nullable();
                $table->integer('order')->nullable();
                $table->timestamps();

                $table->foreign('list_id')->references('id')->on('lists');
                $table->foreign('student_id')->references('id')->on('students');
                $table->foreign('user_id')->references('id')->on('user_id');
            });
        } else {
            Schema::table('lists_rows', function (Blueprint $table) {
                $table->renameColumn('ID', 'id');
                $table->bigInteger('list_id')->change();
                $table->bigInteger('student_id')->nullable()->change();
                $table->bigInteger('user_id')->nullable()->change();
                $table->date('date')->nullable();
                $table->integer('order')->change();
                $table->timestamps();

                $table->dropForeign('FK_lists_rows_lists');
                $table->foreign('list_id')->references('id')->on('lists');
                $table->foreign('student_id')->references('id')->on('students');
                $table->foreign('user_id')->references('id')->on('user_id');
            });

            Schema::table('lists_rows', function (Blueprint $table) {
                $table->id()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('list_rows');
    }
}
