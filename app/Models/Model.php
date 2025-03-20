<?php

namespace App\Models;

use DBarbieri\Aws\S3;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as CoreModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class Model extends CoreModel
{
    use HasFactory;

    protected S3 $s3;

    protected static function booted()
    {
        static::retrieved(function ($file) {
            $file->s3 = app(S3::class);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Schema::hasColumn($model->table, "created_by_id")) {
                $model->created_by_id = Auth::id();
            }
            if (Schema::hasColumn($model->table, "updated_by_id")) {
                $model->updated_by_id = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Schema::hasColumn($model->table, "updated_by_id")) {
                $model->updated_by_id = Auth::id();
            }
        });

        static::addGlobalScope('published_at', function ($builder) {
            $model = $builder->getModel();

            if (explode('.', $model->table)[0] == 'cms') {
                $connection = Schema::connection('mysql_cms');
                if ($connection->hasColumn(str_replace(env("DB_DATABASE_CMS") . ".", "", $model->table), "published_at")) {
                    $builder->whereDate('published_at', "<=", date("Y-m-d H:i:s"));
                }
            } else {
                if (Schema::hasColumn($model->table, 'published_at')) {
                    $builder->whereDate('published_at', "<=", date("Y-m-d H:i:s"));
                }
            }
        });
    }
}
