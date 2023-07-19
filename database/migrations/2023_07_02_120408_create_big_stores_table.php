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
        Schema::create('big_stores', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->string('name');
            $table->string('info');
            $table->string('status');
            $table->string('photoFileName')->nullable();
            $table->string('photoFilePath')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('big_stores');
    }
};
