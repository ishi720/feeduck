<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RssInfo extends Model
{
    protected $fillable = [
        'url',
        'title',
        'link',
        'description',
        'rss_version',
        'tag',
        'manual_tags',
        'crawl_flag',
        'access',
        'created_at',
        'updated_at'
    ];
}
