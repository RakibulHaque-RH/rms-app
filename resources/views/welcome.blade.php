<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestaurantOS | Fresh Food. Warm Nights.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Manrope:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #fffdf7;
            --ink: #20170f;
            --muted: #6a5543;
            --brand: #bf5b2c;
            --brand-dark: #99431c;
            --accent: #f4c430;
            --surface: #fff8ec;
            --line: #ecdac2;
            --good: #246a3d;
            --radius-lg: 24px;
            --radius-md: 14px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at 10% 10%, #ffe3bf 0%, transparent 35%),
                radial-gradient(circle at 90% 20%, #ffe7b6 0%, transparent 30%),
                linear-gradient(180deg, var(--bg) 0%, #fff2dc 100%);
            min-height: 100vh;
        }

        .container {
            width: min(1120px, 92%);
            margin: 0 auto;
        }

        .nav {
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(8px);
            background: rgba(255, 253, 247, .74);
            border-bottom: 1px solid rgba(236, 218, 194, .5);
        }

        .nav-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .brand-badge {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), #e38e3f);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 18px;
            box-shadow: 0 8px 18px rgba(191, 91, 44, .28);
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            border: none;
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-light {
            background: #fff;
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .btn-brand {
            background: linear-gradient(120deg, var(--brand), #da7d39);
            color: #fff;
            box-shadow: 0 10px 24px rgba(191, 91, 44, .3);
        }

        .hero {
            padding: 72px 0 56px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 28px;
            align-items: stretch;
        }

        .hero-copy h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 6vw, 64px);
            line-height: 1.03;
            letter-spacing: -.8px;
            margin-bottom: 16px;
        }

        .hero-copy p {
            color: var(--muted);
            max-width: 56ch;
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .hero-card {
            border-radius: var(--radius-lg);
            background: linear-gradient(160deg, #fff6ea 0%, #fff1dc 100%);
            border: 1px solid var(--line);
            padding: 24px;
            box-shadow: 0 18px 44px rgba(144, 82, 44, .16);
            animation: floatIn .8s ease both;
        }

        .hero-card h3 {
            margin-bottom: 14px;
            font-size: 19px;
        }

        .hero-list {
            display: grid;
            gap: 10px;
        }

        .hero-list li {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4f3d30;
        }

        .hero-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--good);
        }

        .section {
            padding: 26px 0 56px;
        }

        .section h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 42px);
            margin-bottom: 10px;
        }

        .section-sub {
            color: var(--muted);
            margin-bottom: 24px;
            font-size: 16px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .dish {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(102, 68, 38, .08);
            animation: reveal .6s ease both;
        }

        .dish-media {
            position: relative;
            height: 158px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8c770, #f3a64b);
        }

        .dish-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .dish-media-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: rgba(60, 34, 15, .35);
        }

        .dish-head {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(20, 11, 5, .68) 75%);
            display: flex;
            align-items: end;
            justify-content: space-between;
            padding: 14px;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
        }

        .dish-body {
            padding: 14px;
        }

        .dish-title {
            font-weight: 800;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .dish-desc {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
            min-height: 38px;
            margin-bottom: 10px;
        }

        .dish-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .price {
            font-weight: 800;
            color: var(--brand-dark);
        }

        .tag {
            font-size: 11px;
            font-weight: 700;
            color: #6f4a30;
            background: #ffe8cc;
            border: 1px solid #f3d8b8;
            border-radius: 999px;
            padding: 4px 8px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .stat {
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            background: var(--surface);
            padding: 16px;
            text-align: center;
        }

        .stat strong {
            display: block;
            font-size: 28px;
            font-family: 'Playfair Display', serif;
            margin-bottom: 4px;
        }

        .cta {
            margin: 30px 0 70px;
            border: 1px solid #edc89f;
            border-radius: var(--radius-lg);
            background: linear-gradient(140deg, #ffedd2 0%, #ffd8a5 100%);
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .cta h3 {
            font-size: clamp(24px, 3.3vw, 34px);
            font-family: 'Playfair Display', serif;
            line-height: 1.1;
        }

        .footer {
            border-top: 1px solid var(--line);
            color: #7e644f;
            padding: 22px 0 30px;
            font-size: 14px;
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(18px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 980px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .menu-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cta {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 620px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .nav-wrap {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <nav class="nav">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('website.home') }}">
                <span class="brand-badge">R</span>
                <span>RestaurantOS</span>
            </a>
            <div class="nav-actions">
                @auth
                    @if (auth()->user()->role === 'customer')
                        <a class="btn btn-light" href="{{ route('customer.account') }}">My Account</a>
                        <a class="btn btn-light" href="{{ route('customer.orders') }}">Track Orders</a>
                    @else
                        <a class="btn btn-light" href="{{ route('dashboard') }}">Dashboard</a>
                    @endif
                @else
                    <a class="btn btn-light" href="{{ route('customer.login') }}">Customer Login</a>
                    <a class="btn btn-light" href="{{ route('customer.register') }}">Register</a>
                    <a class="btn btn-light" href="{{ route('login') }}">Staff Login</a>
                @endauth
                <a class="btn btn-brand" href="#menu">See Menu</a>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <h1>Fresh Flavors, Fired Daily,<br>Served With Soul.</h1>
                <p>
                    Welcome to our restaurant. We craft bold dishes with seasonal ingredients and a warm dining
                    experience. Explore popular menu items below and drop by tonight.
                </p>
                <div class="nav-actions">
                    <a class="btn btn-brand" href="#menu">Explore Dishes</a>
                    <a class="btn btn-light" href="#contact">Visit Us</a>
                </div>
            </div>
            <aside class="hero-card">
                <h3>Why Guests Love Us</h3>
                <ul class="hero-list">
                    <li><span class="hero-dot"></span>Handcrafted menu made fresh each day</li>
                    <li><span class="hero-dot"></span>Fast table service with live kitchen workflow</li>
                    <li><span class="hero-dot"></span>Family-friendly atmosphere</li>
                    <li><span class="hero-dot"></span>Secure offline and online bill payments</li>
                </ul>
            </aside>
        </div>
    </header>

    <section class="section" id="menu">
        <div class="container">
            <h2>Featured Menu</h2>
            <p class="section-sub">Live from your current available menu items.</p>

            <div class="menu-grid">
                @forelse ($featuredItems as $index => $item)
                    @php
                        $imageUrl = null;
                        if (!empty($item->image)) {
                            $imageUrl = str_starts_with($item->image, 'http')
                                ? $item->image
                                : asset('storage/' . ltrim($item->image, '/'));
                        }
                    @endphp
                    <article class="dish" style="animation-delay: {{ $index * 0.08 }}s">
                        <div class="dish-media">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $item->name }}"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div class="dish-media-fallback" style="display:none">🍽</div>
                            @else
                                <div class="dish-media-fallback">🍽</div>
                            @endif
                            <div class="dish-head">
                                <span>{{ $item->category }}</span>
                                <span>Chef Pick</span>
                            </div>
                        </div>
                        <div class="dish-body">
                            <div class="dish-title">{{ $item->name }}</div>
                            <p class="dish-desc">{{ $item->description ?: 'Signature flavor crafted in-house.' }}</p>
                            <div class="dish-foot">
                                <span class="price">BDT {{ number_format($item->price, 2) }}</span>
                                <span class="tag">Available</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="dish" style="grid-column:1/-1">
                        <div class="dish-body">
                            <div class="dish-title">No available menu items yet</div>
                            <p class="dish-desc">Add menu items from the staff dashboard to show them here.</p>
                        </div>
                    </article>
                @endforelse
            </div>

            <div class="stats">
                <div class="stat">
                    <strong>{{ $featuredItems->count() }}</strong>
                    <span>Featured Dishes</span>
                </div>
                <div class="stat">
                    <strong>{{ $menuByCategory->keys()->count() }}</strong>
                    <span>Menu Categories</span>
                </div>
                <div class="stat">
                    <strong>4.8</strong>
                    <span>Guest Rating</span>
                </div>
            </div>

            <div style="margin-top:26px;padding:18px;border:1px solid var(--line);border-radius:var(--radius-md);background:#fff6e8"
                id="order-now">
                <h3 style="font-family:'Playfair Display',serif;font-size:28px;margin-bottom:8px">Order From Landing
                    Page</h3>
                <p class="section-sub" style="margin-bottom:14px">Menu updates from admin/manager appear here
                    automatically.</p>

                @auth
                    @if (auth()->user()->role === 'customer')
                        <form method="POST" action="{{ route('customer.orders.store') }}" id="customerOrderForm">
                            @csrf
                            <div class="menu-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
                                @foreach ($orderMenuItems as $category => $items)
                                    @foreach ($items as $item)
                                        @php
                                            $imageUrl = null;
                                            if (!empty($item->image)) {
                                                $imageUrl = str_starts_with($item->image, 'http')
                                                    ? $item->image
                                                    : asset('storage/' . ltrim($item->image, '/'));
                                            }
                                        @endphp
                                        <article class="dish" data-menu-id="{{ $item->id }}" style="cursor:pointer">
                                            <div class="dish-media">
                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $item->name }}"
                                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                    <div class="dish-media-fallback" style="display:none">🍽</div>
                                                @else
                                                    <div class="dish-media-fallback">🍽</div>
                                                @endif
                                                <div class="dish-head"><span>{{ $category }}</span><span>BDT
                                                        {{ number_format($item->price, 2) }}</span></div>
                                            </div>
                                            <div class="dish-body">
                                                <div class="dish-title">{{ $item->name }}</div>
                                                <p class="dish-desc">
                                                    {{ $item->description ?: 'Freshly prepared for you.' }}</p>
                                                <div class="dish-foot">
                                                    <span class="tag">Tap to select</span>
                                                    <input type="number" min="1" value="1"
                                                        class="qty-input"
                                                        style="width:64px;padding:4px;border:1px solid var(--line);border-radius:8px"
                                                        onclick="event.stopPropagation()"
                                                        onchange="updateCustomerOrderSummary()">
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                @endforeach
                            </div>

                            <div style="margin-top:14px">
                                <label style="display:block;font-weight:700;margin-bottom:6px">Order Notes
                                    (optional)</label>
                                <textarea name="notes" rows="3"
                                    style="width:100%;padding:10px;border:1px solid var(--line);border-radius:10px"
                                    placeholder="Any special instruction?"></textarea>
                            </div>

                            <div
                                style="margin-top:12px;display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
                                <div><strong>Total:</strong> <span id="customerTotal">BDT 0.00</span></div>
                                <button class="btn btn-brand" type="submit" id="customerSubmitBtn" disabled>Place
                                    Order</button>
                            </div>

                            <div id="customerHiddenInputs"></div>
                        </form>
                    @else
                        <p class="muted">Staff users can create orders from dashboard. Customer ordering is available for
                            customer accounts.</p>
                    @endif
                @else
                    <p class="muted" style="margin-bottom:10px">Please login/register as customer to place order from
                        landing page.</p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap">
                        <a class="btn btn-brand" href="{{ route('customer.login') }}">Customer Login</a>
                        <a class="btn btn-light" href="{{ route('customer.register') }}">Create Account</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <section class="container" id="contact">
        <div class="cta">
            <div>
                <h3>Book a table and enjoy tonight's specials.</h3>
                <p style="margin-top:8px;color:#6b4c33">Open daily 11:00 AM to 11:00 PM | Call: +880 1700-000000</p>
            </div>
            @auth
                @if (auth()->user()->role === 'customer')
                    <a class="btn btn-brand" href="{{ route('customer.account') }}">Open My Account</a>
                @else
                    <a class="btn btn-brand" href="{{ route('orders.create') }}">Create Order</a>
                @endif
            @else
                <a class="btn btn-brand" href="{{ route('customer.register') }}">Create Customer Account</a>
            @endauth
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <span>RestaurantOS • Crafted for smooth service and happy guests.</span>
        </div>
    </footer>

    @auth
        @if (auth()->user()->role === 'customer')
            <script>
                const customerMenuMeta = @json($menuMeta);
                const customerSelectedItems = {};

                document.querySelectorAll('.dish[data-menu-id]').forEach((card) => {
                    card.addEventListener('click', (event) => {
                        if (event.target.closest('.qty-input')) {
                            return;
                        }

                        const menuId = parseInt(card.dataset.menuId, 10);
                        toggleOrderItem(menuId, card);
                    });
                });

                function toggleOrderItem(id, el) {
                    const meta = customerMenuMeta[id];
                    if (!meta || !meta.ingredients || meta.ingredients.length === 0) {
                        return;
                    }

                    const qtyInput = el.querySelector('.qty-input');
                    if (customerSelectedItems[id]) {
                        delete customerSelectedItems[id];
                        el.style.outline = 'none';
                    } else {
                        customerSelectedItems[id] = {
                            id,
                            name: meta.name,
                            price: meta.price,
                            qty: Math.max(1, parseInt(qtyInput.value || 1, 10))
                        };
                        el.style.outline = '3px solid rgba(191,91,44,.45)';
                    }

                    updateCustomerOrderSummary();
                }

                function updateCustomerOrderSummary() {
                    let total = 0;
                    let hidden = '';
                    let i = 0;

                    Object.values(customerSelectedItems).forEach((item) => {
                        const card = document.querySelector(`.dish[data-menu-id="${item.id}"]`);
                        if (card) {
                            const qtyInput = card.querySelector('.qty-input');
                            item.qty = Math.max(1, parseInt(qtyInput.value || 1, 10));
                        }

                        total += item.qty * item.price;
                        hidden += `<input type="hidden" name="items[${i}][menu_id]" value="${item.id}">`;
                        hidden += `<input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
                        i++;
                    });

                    document.getElementById('customerTotal').textContent = 'BDT ' + total.toFixed(2);
                    document.getElementById('customerHiddenInputs').innerHTML = hidden;
                    document.getElementById('customerSubmitBtn').disabled = i === 0;
                }
            </script>
        @endif
    @endauth
</body>

</html>
