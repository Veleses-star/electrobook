<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('remember_token');
            $table->string('frame_class')->nullable()->after('avatar_path');
            $table->boolean('can_upload_avatar')->default(false)->after('frame_class');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_path', 'frame_class', 'can_upload_avatar']);
        });
    }
};