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
        $indexes = DB::select("SHOW INDEX FROM events");
        $existingIndexes = [];
        foreach ($indexes as $index) {
            $existingIndexes[] = $index->Key_name;
        }

        Schema::table('events', function (Blueprint $table) use ($existingIndexes) {
            // Only add indexes if they don't exist
            if (!in_array('events_creator_id_index', $existingIndexes)) {
                $table->index('creator_id');
            }
            if (!in_array('events_status_index', $existingIndexes)) {
                $table->index('status');
            }
            if (!in_array('events_date_index', $existingIndexes)) {
                $table->index('date');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop indexes if they exist
            if (Schema::hasIndex('events', 'events_creator_id_index')) {
                $table->dropIndex(['creator_id']);
            }
            if (Schema::hasIndex('events', 'events_status_index')) {
                $table->dropIndex(['status']);
            }
            if (Schema::hasIndex('events', 'events_date_index')) {
                $table->dropIndex(['date']);
            }
        });
    }
};
