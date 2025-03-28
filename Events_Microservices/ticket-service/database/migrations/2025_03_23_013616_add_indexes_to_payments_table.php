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
        $indexes = DB::select("SHOW INDEX FROM payments");
        $existingIndexes = [];
        foreach ($indexes as $index) {
            $existingIndexes[] = $index->Key_name;
        }

        Schema::table('payments', function (Blueprint $table) use ($existingIndexes) {
            // Only add indexes if they don't exist
            if (!in_array('payments_ticket_id_index', $existingIndexes)) {
                $table->index('ticket_id');
            }
            if (!in_array('payments_transaction_id_index', $existingIndexes)) {
                $table->index('transaction_id');
            }
            if (!in_array('payments_status_index', $existingIndexes)) {
                $table->index('status');
            }
            if (!in_array('payments_payment_method_index', $existingIndexes)) {
                $table->index('payment_method');
            }
            if (!in_array('payments_paid_at_index', $existingIndexes)) {
                $table->index('paid_at');
            }
            if (!in_array('payments_status_paid_at_index', $existingIndexes)) {
                $table->index(['status', 'paid_at']);
            }
            if (!in_array('payments_ticket_id_status_index', $existingIndexes)) {
                $table->index(['ticket_id', 'status']);
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop indexes if they exist
            if (Schema::hasIndex('payments', 'payments_ticket_id_index')) {
                $table->dropIndex(['ticket_id']);
            }
            if (Schema::hasIndex('payments', 'payments_transaction_id_index')) {
                $table->dropIndex(['transaction_id']);
            }
            if (Schema::hasIndex('payments', 'payments_status_index')) {
                $table->dropIndex(['status']);
            }
            if (Schema::hasIndex('payments', 'payments_payment_method_index')) {
                $table->dropIndex(['payment_method']);
            }
            if (Schema::hasIndex('payments', 'payments_paid_at_index')) {
                $table->dropIndex(['paid_at']);
            }
            if (Schema::hasIndex('payments', 'payments_status_paid_at_index')) {
                $table->dropIndex(['status', 'paid_at']);
            }
            if (Schema::hasIndex('payments', 'payments_ticket_id_status_index')) {
                $table->dropIndex(['ticket_id', 'status']);
            }
        });
    }
};
