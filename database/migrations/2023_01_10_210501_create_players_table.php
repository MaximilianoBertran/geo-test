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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('credential_code');
            $table->foreignId('gender_id')->constrained('genders');
            $table->integer('ability')->default(1);
            $table->integer('streng')->default(1);
            $table->integer('speed')->default(1);
            $table->integer('reaction')->default(1);
            $table->integer('won_games')->default(0);
            $table->integer('lost_games')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
};
