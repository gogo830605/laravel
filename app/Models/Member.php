<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MemberResetPassword;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Authenticatable
{
    use Notifiable;
    // use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'nickname', 'subscription', 'recommended_code', 'email', 'password', 'launch', 'locale'];
    protected $hidden = ['password', 'remember_token', ];

    public function memberSocials() {
        return $this->hasMany(MemberSocial::class);
    }


    public function address()
    {
        return $this->hasOne('App\Models\MemberAddress');
    }
}
