<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Get existing indexes
        $indexes = DB::select("SHOW INDEX FROM tickets");
        $existingIndexes = [];
        foreach ($indexes as $index) {
            $existingIndexes[] = $index->Key_name;
        }

        Schema::table('tickets', function (Blueprint $table) use ($existingIndexes) {
            // Only add indexes if they don't exist
            if (!in_array('tickets_user_id_index', $existingIndexes)) {
                $table->index('user_id');
            }
            if (!in_array('tickets_event_id_index', $existingIndexes)) {
                $table->index('event_id');
            }
            if (!in_array('tickets_status_index', $existingIndexes)) {
                $table->index('status');
            }
            if (!in_array('tickets_ticket_number_index', $existingIndexes)) {
                $table->index('ticket_number');
            }
            if (!in_array('tickets_purchase_date_index', $existingIndexes)) {
                $table->index('purchase_date');
            }
            if (!in_array('tickets_event_id_status_index', $existingIndexes)) {
                $table->index(['event_id', 'status']);
            }
            if (!in_array('tickets_user_id_status_index', $existingIndexes)) {
                $table->index(['user_id', 'status']);
            }
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop indexes if they exist
            if (Schema::hasIndex('tickets', 'tickets_user_id_index')) {
                $table->dropIndex(['user_id']);
            }
            if (Schema::hasIndex('tickets', 'tickets_event_id_index')) {
                $table->dropIndex(['event_id']);
            }
            if (Schema::hasIndex('tickets', 'tickets_status_index')) {
                $table->dropIndex(['status']);
            }
            if (Schema::hasIndex('tickets', 'tickets_ticket_number_index')) {
                $table->dropIndex(['ticket_number']);
            }
            if (Schema::hasIndex('tickets', 'tickets_purchase_date_index')) {
                $table->dropIndex(['purchase_date']);
            }
            if (Schema::hasIndex('tickets', 'tickets_event_id_status_index')) {
                $table->dropIndex(['event_id', 'status']);
            }
            if (Schema::hasIndex('tickets', 'tickets_user_id_status_index')) {
                $table->dropIndex(['user_id', 'status']);
            }
        });
    }
};
