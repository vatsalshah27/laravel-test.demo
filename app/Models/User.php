<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Post;
use Carbon;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'birth_date',
        'address',
        'profile_photo_path',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'birth_date',
    ];
    
    ##Mutator : This method automatically save date Y-m-d format in database
    public function setDobAttribute($birth_date)
    {
            $this->attributes['birth_date'] = Carbon::createFromFormat('d-m-Y', $birth_date)->format('Y-m-d');
    }
    
    ##Accessor : This method automatically fetch date d-m-Y format from database
    public function getDobAttribute($birth_date)
    {
       return Carbon::createFromFormat('Y-m-d', $birth_date)->format('d-m-Y');
    }

    # Has Many Relationship: User has so many posts
    public function blogs() {
        return $this->hasMany(Post::class, 'user_id');
    }


}
