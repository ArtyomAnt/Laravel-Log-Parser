<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consent extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'request_id',
        'apiKey',
        'action',
        'visitor_id',
        'url',
        'config_version',
        'granular_metadata',
        'headers',
    ];
}
