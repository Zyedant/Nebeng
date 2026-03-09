<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('balance')->after('user_id');
        });

        Schema::table('partner_posts', function (Blueprint $table) {
            $table->string('balance')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('balance');
        });

        Schema::table('partner_posts', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
