<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ✅ Tambahkan ini
    public function canAccessPanel(Panel $panel): bool
    {
        // Untuk sementara izinkan semua user:
        return true;

        // Atau batasi berdasarkan role:
        // return $this->hasRole('admin');
    }

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
