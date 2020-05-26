<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'frontpage_template',
        'phone',
        'address',
        'email',
        'longitude',
        'latitude',
        'language_id',
        'contract_check',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'frontpage_template' => 'integer',
        'language_id' => 'integer'
    ];
}
