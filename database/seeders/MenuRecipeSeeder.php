<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Menu;
use App\Models\MenuIngredient;
use Illuminate\Database\Seeder;

class MenuRecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            'Bruschetta' => [
                ['item' => 'Tomatoes', 'qty' => 0.08, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
                ['item' => 'Bread', 'qty' => 0.10, 'unit' => 'kg'],
            ],
            'Caesar Salad' => [
                ['item' => 'Lettuce', 'qty' => 0.10, 'unit' => 'kg'],
                ['item' => 'Parmesan Cheese', 'qty' => 0.03, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Calamari Fritti' => [
                ['item' => 'Calamari', 'qty' => 0.18, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.02, 'unit' => 'L'],
                ['item' => 'Tomatoes', 'qty' => 0.04, 'unit' => 'kg'],
            ],
            'Soup of the Day' => [
                ['item' => 'Tomatoes', 'qty' => 0.10, 'unit' => 'kg'],
                ['item' => 'Lettuce', 'qty' => 0.04, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Spring Rolls' => [
                ['item' => 'Spring Roll Wrapper', 'qty' => 0.08, 'unit' => 'kg'],
                ['item' => 'Lettuce', 'qty' => 0.05, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Grilled Salmon' => [
                ['item' => 'Salmon Fillet', 'qty' => 0.25, 'unit' => 'kg'],
                ['item' => 'Lemons', 'qty' => 0.03, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Ribeye Steak' => [
                ['item' => 'Ribeye Steak', 'qty' => 0.35, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
                ['item' => 'Potatoes', 'qty' => 0.20, 'unit' => 'kg'],
            ],
            'Chicken Parmesan' => [
                ['item' => 'Chicken Breast', 'qty' => 0.25, 'unit' => 'kg'],
                ['item' => 'Mozzarella', 'qty' => 0.06, 'unit' => 'kg'],
                ['item' => 'Tomatoes', 'qty' => 0.08, 'unit' => 'kg'],
                ['item' => 'Pasta (Linguine)', 'qty' => 0.12, 'unit' => 'kg'],
            ],
            'Seafood Pasta' => [
                ['item' => 'Pasta (Linguine)', 'qty' => 0.14, 'unit' => 'kg'],
                ['item' => 'Calamari', 'qty' => 0.06, 'unit' => 'kg'],
                ['item' => 'Shrimp', 'qty' => 0.10, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Lamb Chops' => [
                ['item' => 'Lamb Rack', 'qty' => 0.30, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Vegetable Stir Fry' => [
                ['item' => 'Lettuce', 'qty' => 0.06, 'unit' => 'kg'],
                ['item' => 'Tomatoes', 'qty' => 0.06, 'unit' => 'kg'],
                ['item' => 'Olive Oil', 'qty' => 0.01, 'unit' => 'L'],
            ],
            'Beef Burger' => [
                ['item' => 'Ground Beef', 'qty' => 0.20, 'unit' => 'kg'],
                ['item' => 'Lettuce', 'qty' => 0.03, 'unit' => 'kg'],
                ['item' => 'Tomatoes', 'qty' => 0.03, 'unit' => 'kg'],
                ['item' => 'Burger Buns', 'qty' => 0.08, 'unit' => 'kg'],
            ],
            'Tiramisu' => [
                ['item' => 'Heavy Cream', 'qty' => 0.05, 'unit' => 'L'],
                ['item' => 'Coffee Beans', 'qty' => 0.01, 'unit' => 'kg'],
            ],
            'Chocolate Lava Cake' => [
                ['item' => 'Dark Chocolate', 'qty' => 0.08, 'unit' => 'kg'],
                ['item' => 'Heavy Cream', 'qty' => 0.02, 'unit' => 'L'],
            ],
            'Crème Brûlée' => [
                ['item' => 'Heavy Cream', 'qty' => 0.06, 'unit' => 'L'],
                ['item' => 'Sugar', 'qty' => 0.03, 'unit' => 'kg'],
            ],
            'Cheesecake' => [
                ['item' => 'Cream Cheese', 'qty' => 0.10, 'unit' => 'kg'],
                ['item' => 'Heavy Cream', 'qty' => 0.02, 'unit' => 'L'],
            ],
            'Fresh Lemonade' => [
                ['item' => 'Lemons', 'qty' => 0.08, 'unit' => 'kg'],
                ['item' => 'Sugar', 'qty' => 0.02, 'unit' => 'kg'],
            ],
            'Iced Tea' => [
                ['item' => 'Tea Leaves', 'qty' => 0.01, 'unit' => 'kg'],
                ['item' => 'Sugar', 'qty' => 0.01, 'unit' => 'kg'],
            ],
            'Espresso' => [
                ['item' => 'Coffee Beans', 'qty' => 0.02, 'unit' => 'kg'],
            ],
            'Cappuccino' => [
                ['item' => 'Coffee Beans', 'qty' => 0.02, 'unit' => 'kg'],
                ['item' => 'Milk', 'qty' => 0.10, 'unit' => 'L'],
            ],
            'Fresh Juice' => [
                ['item' => 'Lemons', 'qty' => 0.04, 'unit' => 'kg'],
                ['item' => 'Tomatoes', 'qty' => 0.02, 'unit' => 'kg'],
            ],
            'Smoothie' => [
                ['item' => 'Milk', 'qty' => 0.12, 'unit' => 'L'],
                ['item' => 'Sugar', 'qty' => 0.01, 'unit' => 'kg'],
            ],
        ];

        foreach ($recipes as $menuName => $ingredients) {
            $menu = Menu::where('name', $menuName)->first();
            if (!$menu) {
                continue;
            }

            MenuIngredient::where('menu_id', $menu->id)->delete();

            foreach ($ingredients as $row) {
                $inventory = Inventory::firstOrCreate(
                    ['item_name' => $row['item']],
                    [
                        'category' => 'Pantry',
                        'quantity' => 100,
                        'unit' => $row['unit'],
                        'min_quantity' => 10,
                        'cost_per_unit' => 1,
                        'supplier' => 'Auto Added',
                        'notes' => 'Auto-created for menu recipe mapping',
                    ]
                );

                MenuIngredient::create([
                    'menu_id' => $menu->id,
                    'inventory_id' => $inventory->id,
                    'quantity_per_dish' => $row['qty'],
                ]);
            }
        }
    }
}
