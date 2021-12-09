<?php

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
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
                $table->char('name');
                $table->text('description')->nullable();
                $table->ipAddress('image')->nullable();
                $table->char('code', 5)->unique();
                $table->timestamps();
            });
        } else {
            if (!Type::hasType('char')) {
                Type::addType('char', StringType::class);
            }

            if (Schema::hasTable('lists')) {
                Schema::table('lists', static function (Blueprint $table) {
                    $table->dropForeign('FK_lists_classrooms');
                });
            }

            Schema::table('classrooms', static function (Blueprint $table) {
                $table->renameColumn('ID', 'id');
                $table->char('name')->change();
                $table->text('description')->nullable()->change();
                $table->string('image')->nullable()->change();
                $table->timestamps();
            });

            Schema::table('classrooms', static function (Blueprint $table) {
                $table->id()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
}
