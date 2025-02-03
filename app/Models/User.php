<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Filament\Resources\InventarisPermasalahanKepegawaianResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
        'password' => 'hashed',
    ];

    public function usulanPenerbitanAjj()
    {
        return $this->hasMany(InventarisAJJ::class);
    }

    public function inventarisirPermasalahanKepegawaian(){
        return $this->hasMany(InventarisPermasalahanKepegawaianResource::class);
    }

    public function usulanPermohonanCuti(){
        return $this->hasMany(PermohonanCuti::class);
    }

    public function usulanPermohonanPensiun()
    {
        return $this->hasMany(UsulanPermohonanPensiun::class);
    }

    public function usulanRevisiSkPangkat(){
        return $this->hasMany(UsulanRevisiSkPangkat::class);
    }

    public function usulanPemberhentianSementara()
    {
        return $this->hasMany(UsulanSkPemberhentianSementara::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $role = Role::where('name', 'user')->first();
            if ($role) {
                $user->assignRole($role);
            }
        });
    }


}
