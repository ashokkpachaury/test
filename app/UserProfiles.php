<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\User;
class UserProfiles extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'user_profiles';
    protected $fillable = ['title'];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getprofileinfo($id)
    {
        return \App\UserProfiles::find($id);
    }

    public static function getProfileFullname($id)
    {
        $profileinfo = UserProfiles::find($id);

        if ($profileinfo) {
            return $profileinfo->title;
        } else {
            return  '';
        }
    }
    protected $dates = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function profile_images()
    {
        return $this->hasOne('App\ProfileImages', 'id');
    }
}
