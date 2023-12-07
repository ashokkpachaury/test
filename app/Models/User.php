<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'user_image', 'mobile', 'remember_token'];
    protected $appends = ['isPremium'];

    public function getIsPremiumAttribute()
    {
        // Assuming 'exp_date' is a field in the users table.
        // You should adjust this logic based on your database schema.
        $expDateTimestamp = (int)$this->attributes['exp_date'];

        if ($expDateTimestamp > time()) {
            // Convert the timestamp to a Carbon instance
            $expDate = Carbon::createFromTimestamp($expDateTimestamp);

            // Check if the expiration date is in the future
            return $expDate->isFuture();
        }

        return false; // User is not premium if 'exp_date' is not in the future.
    }

    /**
     * The attributes that should be hidden for serialization.
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

    public static function getUserInfo($id)
    {
        return \App\User::find($id);
    }

    public static function getUserFullname($id)
    {
        $userinfo = User::find($id);

        if ($userinfo) {
            return $userinfo->name;
        } else {
            return  '';
        }
    }

    public function sendPasswordResetNotification($token)
    {

        $this->notify(new \App\CustomPassword($token));
    }
    protected $dates = ['deleted_at'];
    public function profiles()
    {
        return $this->hasMany('App\UserProfiles', 'user_id');
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = url('password/reset/' . $this->token);
        $site_logo = getcong('site_logo');
        $site_name = getcong('site_name');
        $site_email = getcong('site_email');

        return (new MailMessage)
            ->subject('Reset Password')
            ->from(getcong('site_email'), getcong('site_name'))
            /*->line('We are sending this email because we recieved a forgot password request.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');*/
            ->view('emails.password', ['url' => $url, 'site_logo' => $site_logo, 'site_name' => $site_name, 'site_email' => $site_email]);
    }
}
