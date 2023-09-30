<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'expire_at'
    ];


    public function otpable() : MorphTo
    {
        return $this->morphTo();
    }
    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\OtpFactory::new();
    }
}
