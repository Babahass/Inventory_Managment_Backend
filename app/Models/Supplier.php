<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'address',
        'website', 'contact_name', 'notes',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
