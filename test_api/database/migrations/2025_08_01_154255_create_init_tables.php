<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('address', 255);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('building_id')
                ->constrained('buildings')
                ->restrictOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
            $table->index('name');
        });

        Schema::create('company_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->restrictOnDelete();
            $table->string('phone_number', 50);
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('activities')
                ->restrictOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->index('name');
        });

        Schema::create('company_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['company_id', 'activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_activities');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('company_phones');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('buildings');
    }
}
