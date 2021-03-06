<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function relationships()
    {
        return $this->hasMany('App\Relationship');
    }

    public function lessons()
    {
        return $this->hasMany('App\Lesson');
    }

    public function followings()
    {
        return $this->belongsToMany('App\User', 'relationships', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany('App\User', 'relationships', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function is_following($id)
    {
        if ($this->followings()->where('followed_id', $id)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function has_image()
    {
        $has_image = false;
        if (Storage::disk('local')->exists('public/profile_images/' . $this->id . '.jpg')) {
            $has_image = true;
        }
        return $has_image;
    }
}
