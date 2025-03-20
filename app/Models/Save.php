<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Save extends Model
{
    use HasFactory;

    protected $table = 'vw_saves';


    public function file()
    {
        return $this
            ->hasOne(File::class, "related_id", "id")
            ->where("related_type", "api::save.save")
            ->where("field", "file")
            ->orderBy("order");
    }

}
