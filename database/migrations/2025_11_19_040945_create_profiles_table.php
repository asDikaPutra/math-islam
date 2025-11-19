<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('phone')->nullable();
            $table->text('bio')->nullable();

            $table->string('fakultas')->nullable();
            $table->string('jurusan')->nullable();
            $table->integer('angkatan')->nullable();

            $table->string('avatar_path')->nullable();

            $table->boolean('is_first_login')->default(true);

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('profiles');
    }
};
