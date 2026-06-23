<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('message_responses', function (Blueprint $table) {
            $table->boolean('is_read_by_admin')->default(false)->after('response_attachments');
            $table->timestamp('read_by_admin_at')->nullable()->after('is_read_by_admin');
            $table->index(['message_id', 'is_read_by_admin']);
        });

        // Existing responses were already handled before this feature shipped.
        \Illuminate\Support\Facades\DB::table('message_responses')->update([
            'is_read_by_admin' => true,
            'read_by_admin_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('message_responses', function (Blueprint $table) {
            $table->dropIndex(['message_id', 'is_read_by_admin']);
            $table->dropColumn(['is_read_by_admin', 'read_by_admin_at']);
        });
    }
};
