<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RssFeed extends Model
{
    protected $fillable = [
        'info_id',
        'url',
        'title',
        'description',
        'image',
        'tag',
        'pubDate',
        'created_at',
        'updated_at'
    ];
}
