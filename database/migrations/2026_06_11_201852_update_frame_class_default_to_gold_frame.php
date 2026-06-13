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
        Schema::table('users', function (Blueprint $table) {
            $table->string('frame_class', 255)->nullable()->default('gold-frame')->change();
        });
    }

    public function down()
    {
        DB::table('shop_items')->where('type', 'frame')->update(['image' => null]);
        Schema::table('users', function (Blueprint $table) {
            $table->string('frame_class')->default(null)->change();
        });
    }
};
