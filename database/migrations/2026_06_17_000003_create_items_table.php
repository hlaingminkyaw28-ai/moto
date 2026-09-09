<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('item_type');
            $table->string('category')->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->decimal('sale_price', 12, 2);
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
