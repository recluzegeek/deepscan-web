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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('isAdmin')->default(false);
            $table->boolean('isSubscribed')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('filename');
            $table->string('video_path');
            $table->string('video_status');
            $table->string('error_message')->nullable();
            $table->string('predicted_class')->nullable();
            $table->float('prediction_probability')->nullable();
            $table->timestamps();
            $table->foreignUuid('user_id')->nullable()->index()->constrained('users')->onDelete('cascade');
        });

//        Schema::create('video_results', function (Blueprint $table){
//            $table->uuid('id')->primary();
//            $table->string('predicted_class');
//            $table->float('prediction_probability');
//            $table->foreignUuid('video_id')->nullable()->index()->constrained('uploaded_videos');
//        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index()->constrained('users');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('video_results');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('users');
    }
};
