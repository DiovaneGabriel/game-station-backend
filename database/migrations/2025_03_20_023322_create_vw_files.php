<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $query = 'create or replace view vw_files as
            SELECT f.*,
                frm.related_id,
                frm.related_type,
                frm.field,
                frm.order
            FROM cms.files_related_mph frm
            join cms.files f on f.id = frm.file_id';
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vw_files');
    }
};
