<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('twitter_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('twitter_id')->nullable()->unique();
            $table->string('nickname')->nullable();
            $table->string('avatar')->nullable();
            $table->string('token')->nullable();
            $table->string('token_secret')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('twitter_profiles');
    }
};
