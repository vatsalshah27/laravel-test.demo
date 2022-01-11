<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\User;
use Carbon;

class Post extends Model
{
    use HasFactory;
    use sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'slug', 'description', 'image_path', 'user_id', 'is_published'];

    protected $dates = [
        'published_at',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    ##Mutator : This method automatically save date Y-m-d format in database
    public function setDobAttribute($published_at)
    {
            $this->attributes['published_at'] = Carbon::createFromFormat('d-m-Y', $published_at)->format('Y-m-d H:i:s');
    }
    
    ##Accessor : This method automatically fetch date d-m-Y format from database
    public function getDobAttribute($published_at)
    {
       return Carbon::createFromFormat('Y-m-d H:i:s', $published_at)->format('d-m-Y');
    }

    public function user() {
    	
	    return $this->belongsTo(User::class);
	}

}
