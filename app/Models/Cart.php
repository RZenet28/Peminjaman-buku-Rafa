<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all cart items for this cart
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get total number of items in cart
     */
    public function getItemCountAttribute(): int
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Check if a book is already in cart
     */
    public function hasBook($bookId): bool
    {
        return $this->items()->where('book_id', $bookId)->exists();
    }

    /**
     * Add a book to cart
     */
    public function addBook($bookId, $quantity = 1): CartItem
    {
        $item = $this->items()->where('book_id', $bookId)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
            return $item;
        }

        return $this->items()->create([
            'book_id' => $bookId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Remove a book from cart
     */
    public function removeBook($bookId): bool
    {
        return $this->items()->where('book_id', $bookId)->delete();
    }

    /**
     * Clear entire cart
     */
    public function clear(): void
    {
        $this->items()->delete();
    }
}
