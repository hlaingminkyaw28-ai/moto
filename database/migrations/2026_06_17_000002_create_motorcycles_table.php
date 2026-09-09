<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->string('color')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('frame_number')->nullable();
            $table->unsignedInteger('kilometer')->nullable();
            $table->string('wheel_type')->nullable();
            $table->string('cover_condition')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
