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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('entity_id');
            $table->string('category_name');
            $table->string('sku');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price');
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->string('brand')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->string('caffeine_type')->nullable();
            $table->integer('count')->default(0);
            $table->string('flavored')->nullable();
            $table->string('seasonal')->nullable();
            $table->boolean('in_stock')->default(0);
            $table->string('facebook')->nullable();
            $table->boolean('is_kcup')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
