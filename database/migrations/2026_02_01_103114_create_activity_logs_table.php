<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // pelaku
            $table->string('actor_name')->nullable(); // cadangan kalau user_id null
            $table->string('type')->default('info');  // mitra|update|cancel|refund|report|info
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('page')->nullable();        // contoh: "mitra", "pesanan"
            $table->string('ref_type')->nullable();    // contoh: Order, Refund, Report
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index(['is_read', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
