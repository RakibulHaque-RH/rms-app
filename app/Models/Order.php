<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'table_id',
        'user_id',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'total_amount',
        'paid_amount',
        'paid_at',
        'notes'
    ];
    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotal()
    {
        $this->total_amount = $this->items->sum('subtotal');
        $this->save();
        return $this->total_amount;
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Ymd') . '-';
        $lastOrder = static::where('order_number', 'like', $prefix . '%')->orderBy('order_number', 'desc')->first();
        $num = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
