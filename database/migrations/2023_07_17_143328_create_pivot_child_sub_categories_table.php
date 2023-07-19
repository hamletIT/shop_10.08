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
        Schema::create('pivot_child_sub_categories', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->bigInteger('product_id')->unsigned()->nullable();;       
            $table->bigInteger('child_sub_category_id')->unsigned()->nullable();
            $table->bigInteger('sub_category_id')->unsigned()->nullable();;     
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');   
            $table->foreign('child_sub_category_id')->references('id')->on('child_sub_categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_child_sub_categories');
    }
};
