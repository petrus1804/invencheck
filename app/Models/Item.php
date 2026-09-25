<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'category_id', 'warehouse_id',
        'stock', 'minimum_stock', 'unit',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    // Accessor otomatis: status "Aman" / "Menipis" / "Habis"
    public function getStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'habis';
        }

        if ($this->stock <= $this->minimum_stock) {
            return 'menipis';
        }

        return 'aman';
    }
}