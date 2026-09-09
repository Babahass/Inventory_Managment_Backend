<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // Full administrative access (CRUD items, manage users, full dashboard)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Staff access (View stocks & record sales/purchases transactions)
    public function isStaff(): bool
    {
        return $this->role === 'staff' || $this->role === 'admin'; 
        // Note: Admin gets true here too so admins aren't blocked from staff features!
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}




// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable
// {
//     use HasApiTokens, HasFactory, Notifiable;

//     protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

//     protected $hidden = ['password', 'remember_token'];

//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password'          => 'hashed',
//             'is_active'         => 'boolean',
//         ];
//     }

//     public function isAdmin(): bool    { return $this->role === 'admin'; }
//     public function isManager(): bool  { return in_array($this->role, ['admin', 'manager']); }
//     public function isStaff(): bool    { return true; } // all roles can read

//     public function transactions()
//     {
//         return $this->hasMany(Transaction::class);
//     }
// }
