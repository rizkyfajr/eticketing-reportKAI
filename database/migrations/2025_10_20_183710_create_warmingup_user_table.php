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
    Schema::create('warmingup_user', function (Blueprint $table) {
      $table->id();
      $table->foreignId('warming_up_id')
            ->constrained('warming_up')
            ->cascadeOnDelete();
      $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();
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
    Schema::dropIfExists('warmingup_user');
  }
};
