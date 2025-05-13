<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone_number');
            $table->string('stage_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('nationality');
            $table->string('country');
            $table->string('region');
            $table->string('address');
            $table->string('nin');
            $table->text('bio');
            $table->json('national_id_photos');
            $table->string('profile_photo');
            $table->json('social_media_links');
            $table->json('music_links');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
