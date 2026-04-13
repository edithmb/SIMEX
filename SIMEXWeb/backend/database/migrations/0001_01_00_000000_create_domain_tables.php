<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description')->nullable();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });

        Schema::create('container_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name', 50);
        });

        Schema::create('tracking_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('incoterm_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignId('country_id')->constrained('countries');
        });

        Schema::create('incoterms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoterm_type_id')->constrained('incoterm_types');
            $table->foreignId('tracking_step_id')->constrained('tracking_steps');
            $table->integer('order_num')->default(0);
        });

        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignId('city_id')->constrained('cities');
        });

        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('name', 120);
            $table->foreignId('city_id')->constrained('cities');
        });

        Schema::create('shipping_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignId('city_id')->constrained('cities');
        });

        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignId('city_id')->constrained('cities');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carriers');
        Schema::dropIfExists('shipping_lines');
        Schema::dropIfExists('airports');
        Schema::dropIfExists('ports');
        Schema::dropIfExists('incoterms');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('incoterm_types');
        Schema::dropIfExists('tracking_steps');
        Schema::dropIfExists('container_types');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('roles');
    }
};
