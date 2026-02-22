<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ледовый каток')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }

        /* Сетка 8px */
        :root {
            --space-1: 8px;
            --space-2: 16px;
            --space-3: 24px;
            --space-4: 32px;
            --space-5: 40px;
            --space-6: 48px;
            --space-7: 56px;
            --space-8: 64px;

            --primary: #2c3e50;
            --primary-light: #34495e;
            --accent: #3498db;
            --accent-hover: #2980b9;
            --border: #ecf0f1;
            --text: #333;
            --text-light: #7f8c8d;
            --bg-light: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-2);
        }

        /* Шапка */
        .header {
            background: white;
            padding: var(--space-2) 0;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
        }

        .logo a {
            text-decoration: none;
            color: var(--primary);
        }

        .nav {
            display: flex;
            gap: var(--space-3);
            align-items: center;
        }

        .nav a {
            text-decoration: none;
            color: var(--text);
            font-size: 14px;
            transition: color 0.2s;
        }

        .nav a:hover {
            color: var(--accent);
        }

        /* Кнопки */
        .btn {
            padding: var(--space-1) var(--space-3);
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            background: white;
            color: var(--text);
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn-outline {
            border-color: var(--accent);
            color: var(--accent);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--accent);
            color: white;
        }

        .admin-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 18px;
            transition: color 0.2s;
        }

        .admin-link:hover {
            color: var(--accent);
        }

        /* Футер */
        .footer {
            background: var(--bg-light);
            padding: var(--space-6) 0;
            margin-top: var(--space-8);
            border-top: 1px solid var(--border);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-4);
        }

        .footer h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: var(--space-2);
            color: var(--primary);
        }

        .footer p {
            font-size: 13px;
            color: var(--text-light);
            line-height: 1.6;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: var(--space-1);
        }

        .footer-links a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .copyright {
            text-align: center;
            padding-top: var(--space-4);
            margin-top: var(--space-4);
            border-top: 1px solid var(--border);
            color: var(--text-light);
            font-size: 12px;
        }

        /* Адаптив */
        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<header class="header">
    <div class="container">
        <div class="logo">
            <a href="{{ route('home') }}">Ледовый каток</a>
        </div>
        <nav class="nav">
            <a href="#prices">Цены</a>
            <a href="#booking">Бронирование</a>
            <a href="#skates">Коньки</a>
            @auth
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="admin-link">⚙️</a>
                @endif
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">Выйти</button>
                </form>
            @else
                <a href="{{ route('admin.login') }}" class="admin-link">🔐</a>
            @endauth
            <button class="btn btn-primary" onclick="document.getElementById('booking').scrollIntoView({behavior: 'smooth'})">
                Купить билет
            </button>
        </nav>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <h4>Ледовый каток</h4>
                <p>ул. Ленина, 52<br>+7 (999) 999-99-99<br>info@icerink.ru</p>
            </div>
            <div>
                <h4>Режим работы</h4>
                <ul class="footer-links">
                    <li>Пн-Пт: 10:00 - 22:00</li>
                    <li>Сб-Вс: 09:00 - 23:00</li>
                </ul>
            </div>
            <div>
                <h4>Навигация</h4>
                <ul class="footer-links">
                    <li><a href="#prices">Цены</a></li>
                    <li><a href="#booking">Бронирование</a></li>
                    <li><a href="#skates">Коньки</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            © 2026 Ледовый каток. Все права защищены.
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
