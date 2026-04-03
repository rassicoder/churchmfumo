<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use UsesUuid;

    protected $keyType = 'string';
    public $incrementing = false;
}