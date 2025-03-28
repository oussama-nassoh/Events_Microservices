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
        $indexes = DB::select("SHOW INDEX FROM users");
        $existingIndexes = [];
        foreach ($indexes as $index) {
            $existingIndexes[] = $index->Key_name;
        }

        Schema::table('users', function (Blueprint $table) use ($existingIndexes) {
            // Only add indexes if they don't exist
            if (!in_array('users_name_index', $existingIndexes)) {
                $table->index('name');
            }
            if (!in_array('users_created_at_index', $existingIndexes)) {
                $table->index('created_at');
            }
            if (!in_array('users_name_email_index', $existingIndexes)) {
                $table->index(['name', 'email']);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes if they exist
            if (Schema::hasIndex('users', 'users_name_index')) {
                $table->dropIndex(['name']);
            }
            if (Schema::hasIndex('users', 'users_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
            if (Schema::hasIndex('users', 'users_name_email_index')) {
                $table->dropIndex(['name', 'email']);
            }
        });
    }
};
