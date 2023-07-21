<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name');
            $table->string('productNumber');
            $table->string('rating')->nullable();
            $table->string('color');
            $table->string('type');
            $table->string('description');
            $table->string('photoFileName');
            $table->string('photoFilePath');
            $table->string('size');
            $table->string('status');
            $table->integer('standardCost');
            $table->integer('listprice');
            $table->integer('totalPrice');
            $table->integer('weight');
            $table->integer('totalQty');
            $table->date('sellStartDate');
            $table->date('sellEndDate');
            $table->bigInteger('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');   
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
