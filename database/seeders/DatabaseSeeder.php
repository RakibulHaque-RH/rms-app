<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '555-0100',
            'is_active' => true,
        ]);

        $manager = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '555-0101',
            'is_active' => true,
        ]);

        $waiter1 = User::create([
            'name' => 'James Wilson',
            'email' => 'james@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'phone' => '555-0102',
            'is_active' => true,
        ]);

        $waiter2 = User::create([
            'name' => 'Emily Davis',
            'email' => 'emily@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'phone' => '555-0103',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Chef Marco',
            'email' => 'marco@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'chef',
            'phone' => '555-0104',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Lisa Chen',
            'email' => 'lisa@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'phone' => '555-0105',
            'is_active' => true,
        ]);

        // ===== MENU ITEMS =====
        $appetizers = [
            ['name' => 'Bruschetta', 'description' => 'Toasted bread topped with fresh tomatoes, basil, and balsamic glaze', 'price' => 8.99],
            ['name' => 'Caesar Salad', 'description' => 'Crisp romaine, parmesan, croutons with house-made Caesar dressing', 'price' => 10.99],
            ['name' => 'Calamari Fritti', 'description' => 'Golden fried calamari with marinara and lemon aioli', 'price' => 12.99],
            ['name' => 'Soup of the Day', 'description' => 'Chef\'s daily selection of fresh soup', 'price' => 7.99],
            ['name' => 'Spring Rolls', 'description' => 'Crispy rolls with vegetables and sweet chili dipping sauce', 'price' => 9.49],
        ];

        $mainCourse = [
            ['name' => 'Grilled Salmon', 'description' => 'Atlantic salmon with lemon butter sauce, asparagus and rice pilaf', 'price' => 24.99],
            ['name' => 'Ribeye Steak', 'description' => '12oz prime ribeye with garlic mashed potatoes and seasonal vegetables', 'price' => 32.99],
            ['name' => 'Chicken Parmesan', 'description' => 'Breaded chicken breast with marinara, mozzarella and spaghetti', 'price' => 18.99],
            ['name' => 'Seafood Pasta', 'description' => 'Shrimp, mussels, and calamari in white wine garlic sauce over linguine', 'price' => 22.99],
            ['name' => 'Lamb Chops', 'description' => 'New Zealand lamb chops with rosemary jus and roasted potatoes', 'price' => 28.99],
            ['name' => 'Vegetable Stir Fry', 'description' => 'Fresh seasonal vegetables in teriyaki sauce with steamed jasmine rice', 'price' => 15.99],
            ['name' => 'Beef Burger', 'description' => 'Angus beef patty with cheddar, lettuce, tomato, and house fries', 'price' => 16.99],
        ];

        $desserts = [
            ['name' => 'Tiramisu', 'description' => 'Classic Italian coffee-flavored layered dessert', 'price' => 9.99],
            ['name' => 'Chocolate Lava Cake', 'description' => 'Warm molten chocolate cake with vanilla ice cream', 'price' => 11.99],
            ['name' => 'Crème Brûlée', 'description' => 'Vanilla custard with caramelized sugar crust', 'price' => 8.99],
            ['name' => 'Cheesecake', 'description' => 'New York style with seasonal berry compote', 'price' => 9.49],
        ];

        $beverages = [
            ['name' => 'Fresh Lemonade', 'description' => 'House-made with fresh lemons and mint', 'price' => 4.99],
            ['name' => 'Iced Tea', 'description' => 'Freshly brewed with choice of sweetened or unsweetened', 'price' => 3.99],
            ['name' => 'Espresso', 'description' => 'Double shot Italian espresso', 'price' => 3.49],
            ['name' => 'Cappuccino', 'description' => 'Espresso with steamed milk foam', 'price' => 4.99],
            ['name' => 'Fresh Juice', 'description' => 'Orange, apple, or mixed berry', 'price' => 5.99],
            ['name' => 'Smoothie', 'description' => 'Tropical mango and banana blend', 'price' => 6.99],
        ];

        $menus = [];
        foreach ($appetizers as $item) {
            $menus[] = Menu::create(array_merge($item, ['category' => 'Appetizers', 'is_available' => true]));
        }
        foreach ($mainCourse as $item) {
            $menus[] = Menu::create(array_merge($item, ['category' => 'Main Course', 'is_available' => true]));
        }
        foreach ($desserts as $item) {
            $menus[] = Menu::create(array_merge($item, ['category' => 'Desserts', 'is_available' => true]));
        }
        foreach ($beverages as $item) {
            $menus[] = Menu::create(array_merge($item, ['category' => 'Beverages', 'is_available' => true]));
        }

        // ===== TABLES =====
        $tables = [];
        $locations = ['Indoor', 'Indoor', 'Indoor', 'Indoor', 'Indoor', 'Outdoor', 'Outdoor', 'Outdoor', 'VIP', 'VIP'];
        $capacities = [2, 4, 4, 6, 4, 4, 6, 2, 8, 6];
        for ($i = 1; $i <= 10; $i++) {
            $tables[] = Table::create([
                'table_number' => 'T-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => $capacities[$i - 1],
                'status' => $i <= 7 ? 'available' : ($i == 8 ? 'reserved' : ($i == 9 ? 'occupied' : 'maintenance')),
                'location' => $locations[$i - 1],
            ]);
        }

        // ===== ORDERS =====
        $statuses = ['completed', 'completed', 'completed', 'completed', 'served', 'preparing', 'pending'];
        $waiters = [$waiter1, $waiter2];

        for ($i = 0; $i < 7; $i++) {
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'table_id' => $tables[array_rand(array_slice($tables, 0, 7))]->id,
                'user_id' => $waiters[array_rand($waiters)]->id,
                'status' => $statuses[$i],
                'notes' => $i == 0 ? 'No onions please' : null,
                'created_at' => now()->subHours(rand(0, 72)),
            ]);

            $itemCount = rand(2, 5);
            $usedMenuIds = [];
            $total = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $menuItem = $menus[array_rand($menus)];
                if (in_array($menuItem->id, $usedMenuIds)) continue;
                $usedMenuIds[] = $menuItem->id;

                $qty = rand(1, 3);
                $subtotal = $menuItem->price * $qty;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menuItem->id,
                    'quantity' => $qty,
                    'unit_price' => $menuItem->price,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);
        }

        // ===== INVENTORY =====
        $inventoryItems = [
            ['item_name' => 'Chicken Breast', 'category' => 'Meat', 'quantity' => 25, 'unit' => 'kg', 'min_quantity' => 10, 'cost_per_unit' => 8.50, 'supplier' => 'Fresh Farms Co.'],
            ['item_name' => 'Salmon Fillet', 'category' => 'Seafood', 'quantity' => 15, 'unit' => 'kg', 'min_quantity' => 5, 'cost_per_unit' => 18.00, 'supplier' => 'Ocean Harvest'],
            ['item_name' => 'Ribeye Steak', 'category' => 'Meat', 'quantity' => 20, 'unit' => 'kg', 'min_quantity' => 8, 'cost_per_unit' => 22.00, 'supplier' => 'Prime Cuts Ltd'],
            ['item_name' => 'Olive Oil', 'category' => 'Pantry', 'quantity' => 12, 'unit' => 'L', 'min_quantity' => 5, 'cost_per_unit' => 6.50, 'supplier' => 'Mediterranean Foods'],
            ['item_name' => 'Tomatoes', 'category' => 'Produce', 'quantity' => 30, 'unit' => 'kg', 'min_quantity' => 10, 'cost_per_unit' => 3.00, 'supplier' => 'Green Valley Farms'],
            ['item_name' => 'Mozzarella', 'category' => 'Dairy', 'quantity' => 8, 'unit' => 'kg', 'min_quantity' => 4, 'cost_per_unit' => 12.00, 'supplier' => 'Italian Dairy Co.'],
            ['item_name' => 'Pasta (Linguine)', 'category' => 'Pantry', 'quantity' => 20, 'unit' => 'kg', 'min_quantity' => 8, 'cost_per_unit' => 2.50, 'supplier' => 'Pasta World'],
            ['item_name' => 'Lettuce', 'category' => 'Produce', 'quantity' => 3, 'unit' => 'kg', 'min_quantity' => 5, 'cost_per_unit' => 2.00, 'supplier' => 'Green Valley Farms'],
            ['item_name' => 'Coffee Beans', 'category' => 'Beverages', 'quantity' => 5, 'unit' => 'kg', 'min_quantity' => 3, 'cost_per_unit' => 15.00, 'supplier' => 'Bean Masters'],
            ['item_name' => 'Lemons', 'category' => 'Produce', 'quantity' => 2, 'unit' => 'kg', 'min_quantity' => 5, 'cost_per_unit' => 4.00, 'supplier' => 'Green Valley Farms'],
            ['item_name' => 'Heavy Cream', 'category' => 'Dairy', 'quantity' => 10, 'unit' => 'L', 'min_quantity' => 4, 'cost_per_unit' => 5.50, 'supplier' => 'Italian Dairy Co.'],
            ['item_name' => 'Lamb Rack', 'category' => 'Meat', 'quantity' => 12, 'unit' => 'kg', 'min_quantity' => 5, 'cost_per_unit' => 28.00, 'supplier' => 'Prime Cuts Ltd'],
        ];

        foreach ($inventoryItems as $item) {
            Inventory::create($item);
        }
    }
}
