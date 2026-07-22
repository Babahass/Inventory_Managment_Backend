<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'product_id', 'type', 'transaction_type',
        'quantity', 'notes', 'user_id', 'reference_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referencedTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'reference_id');
    }
}
