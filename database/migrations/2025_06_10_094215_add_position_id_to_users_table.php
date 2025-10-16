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
      Schema::table('users', function (Blueprint $table) {
          $table->unsignedBigInteger('position_id')->nullable()->after('username');
          $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
      });
  }

  public function down()
  {
      Schema::table('users', function (Blueprint $table) {
          $table->dropForeign(['position_id']);
          $table->dropColumn('position_id');
      });
  }

};
