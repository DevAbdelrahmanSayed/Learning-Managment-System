<?php

namespace Modules\Student\Entities;


use Modules\Auth\Entities\Otp;
use Laravel\Sanctum\HasApiTokens;
use Modules\Course\Entities\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Modules\Favourite\Entities\FavouriteCourse;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable implements JWTSubject

{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'about',
        'profile',

    ];
    /**
     * The attributes that should be hidde                                                                                                                                                                          8n for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Set the hashed password attribute.
     *
     * @param  array  $attributes
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function otp () :MorphMany
    {
        return $this->morphMany(Otp::class,'otpable');
    }
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentFactory::new();
    }

    public function favouriteCourses(){
        return $this->belongsToMany(Course::class, 'student_favourtie_courses' , 'student_id' , 'course_id');
    }
}
