<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('tel');
            $table->string('kid');
            $table->integer('age');
            $table->foreignIdFor(\App\Models\Region::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Organization::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Problem::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('consultant_id');
            $table->foreign('consultant_id')->references('id')->on('users')->cascadeOnDelete();
            $table->dateTime('date');
            $table->string('status')->default('TAKEN');
            $table->integer('code');
            $table->integer('rating')->nullable();
            $table->string('rejection')->nullable();
            $table->dateTime('consDate')->nullable();
            $table->string('result')->nullable();
            $table->string('advice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
