<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $table = 'vw_files';

    protected $appends = ['signed_urls'];

    public function getSignedUrlsAttribute()
    {
        $formats = json_decode($this->attributes['formats']);

        $return = [];

        $return['default'] = $this->s3->getSignedUri($this->attributes['hash'] . $this->attributes['ext']);

        if ($formats) {
            foreach ($formats as $key => $format) {
                $return[$key] = $this->s3->getSignedUri($format->hash . $format->ext);
            }
        }

        return $return;
    }
}
