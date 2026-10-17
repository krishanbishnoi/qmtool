<?php

namespace App;

use App\Entrust\Traits\EntrustUserTrait;
use App\Scopes\UsersDeletedScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'mobile',
        'qa_target',
        'is_first_time_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
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
        'password'          => 'hashed',
    ];

    protected $guarded = ['_token'];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope(new UsersDeletedScope);
    // }

    public function is_email_verified(): int
    {
        return $this->email_verified_at ? 1 : 0;
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Company::class);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            \App\Role::class,
            config('entrust.role_user_table', 'role_user')
        );
    }

    // user masters
    // process==1 / region==2 / language==3

    public function my_processes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\UsersMaster::class, 'user_id', 'id')
                    ->where('master_type', 1);
    }

    public function my_regions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\UsersMaster::class, 'user_id', 'id')
                    ->where('master_type', 2);
    }

    public function my_languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\UsersMaster::class, 'user_id', 'id')
                    ->where('master_type', 3);
    }

    public function client_detail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\ClientAdmin::class);
    }

    public function partner_admin_detail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Partner::class, 'admin_user_id');
    }

    public function spoc_detail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\PartnersProcessSpoc::class);
    }

    public function token(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Token::class);
    }
}
