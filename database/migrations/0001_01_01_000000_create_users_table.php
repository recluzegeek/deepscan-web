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
//            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('uploaded_videos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('filename');
            $table->string('video_storage_path');
            $table->string('status');
            $table->string('upload_date_time');
            $table->foreignUuid('user_id')->nullable()->index()->constrained('users');
        });

        Schema::create('video_results', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->integer('predicted_class');
            $table->float('prediction_probability');
            $table->foreignUuid('video_id')->nullable()->index()->constrained('uploaded_videos');
        });

        Schema::create('video_result_reports', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->string('report_storage_path');
            $table->foreignUuid('video_result_id')->nullable()->index()->constrained('video_results');
        });

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
        Schema::dropIfExists('video_result_reports');
        Schema::dropIfExists('video_results');
        Schema::dropIfExists('uploaded_videos');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
