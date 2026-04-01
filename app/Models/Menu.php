<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description', 'price', 'image', 'is_available'];

    protected $casts = ['price' => 'decimal:2', 'is_available' => 'boolean'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function menuIngredients()
    {
        return $this->hasMany(MenuIngredient::class);
    }
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function estimateIngredients(float $dishCount = 1): array
    {
        return $this->menuIngredients
            ->map(function ($ingredient) use ($dishCount) {
                return [
                    'inventory_id' => $ingredient->inventory_id,
                    'item_name' => $ingredient->inventory->item_name ?? 'Unknown Item',
                    'unit' => $ingredient->inventory->unit ?? 'unit',
                    'quantity' => (float) $ingredient->quantity_per_dish * $dishCount,
                ];
            })
            ->toArray();
    }
}
