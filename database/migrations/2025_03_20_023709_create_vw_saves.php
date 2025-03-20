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
        $query = 'create or replace view vw_saves as
            select s.*,
                    sgl.game_id,
                    supul.user_id
                from cms.saves s
                join cms.saves_game_lnk sgl on sgl.save_id = s.id
                join cms.saves_users_permissions_user_lnk supul on supul.save_id = s.id';
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vw_saves');
    }
};
