<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email', 'password','user_image','mobile','remember_token'];

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

    public static function getUserInfo($id)
    {
        return User::find($id);
    }

    public static function getUserFullname($id)
    {
        $userinfo=User::find($id);

        if($userinfo)
        {
            return $userinfo->name;
        }
        else
        {
            return  '';
        }

    }

    public function sendPasswordResetNotification($token)
    {

        $this->notify(new CustomPassword($token));
    }
    public function profiles()
    {
        return $this->hasMany('App\UserProfiles', 'user_id');
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url=url('password/reset/'.$this->token);
        $site_logo = getcong('site_logo');
        $site_name = getcong('site_name');
        $site_email = getcong('site_email');

        return (new MailMessage)
            ->subject('Reset Password')
            ->from(getcong('site_email'), getcong('site_name'))
            /*->line('We are sending this email because we recieved a forgot password request.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');*/
            ->view('emails.password',['url'=>$url,'site_logo'=>$site_logo,'site_name'=>$site_name,'site_email'=>$site_email]);
    }
}
