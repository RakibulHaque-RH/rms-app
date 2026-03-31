<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description', 'price', 'image', 'is_available'];

    protected $casts = ['price' => 'decimal:2', 'is_available' => 'boolean'];

    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function scopeAvailable($query) { return $query->where('is_available', true); }
    public function scopeByCategory($query, $category) { return $query->where('category', $category); }
}
