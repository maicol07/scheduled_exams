<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id();
                $table->tinyText('name');
                $table->text('description')->nullable();
                $table->ipAddress('image')->nullable();
                $table->char('code', 5)->unique();
                $table->timestamps();
            });
        } else {
            Schema::table('classrooms', static function (Blueprint $table) {
                $table->renameColumn('ID', 'id');
                $table->id()->change();
                $table->tinyText('name')->change();
                $table->text('description')->nullable()->change();
                $table->ipAddress('image')->nullable()->change();
                $table->char('code', 5)->unique();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
}
