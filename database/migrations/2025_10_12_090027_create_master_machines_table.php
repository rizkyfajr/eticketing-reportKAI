<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('master_machines', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');         
      $table->string('type')->nullable();
      $table->string('location')->nullable();
      $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
      $table->timestamps();
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('master_machines');
  }
};
