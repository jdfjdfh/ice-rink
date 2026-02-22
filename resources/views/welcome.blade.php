@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <style>
        .hero {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            padding: var(--space-6) 0;
            margin-bottom: var(--space-5);
            text-align: center;
        }

        .hero h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: var(--space-2);
        }

        .hero p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: var(--space-3);
        }

        /* Карточки */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--space-3);
            margin: var(--space-6) 0;
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: var(--space-4);
            transition: all 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: var(--space-2);
            color: var(--primary);
        }

        .price {
            font-size: 32px;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: var(--space-3);
        }

        .price small {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 400;
        }

        .card p {
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: var(--space-3);
        }

        /* Формы */
        .form-section {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: var(--space-5);
            margin: var(--space-6) 0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: var(--space-4);
            color: var(--primary);
        }

        .form-tabs {
            display: flex;
            gap: var(--space-2);
            margin-bottom: var(--space-4);
            border-bottom: 1px solid var(--border);
            padding-bottom: var(--space-2);
        }

        .tab-btn {
            padding: var(--space-1) var(--space-3);
            border: none;
            background: none;
            font-size: 14px;
            cursor: pointer;
            color: var(--text-light);
            transition: all 0.2s;
            border-radius: 4px;
        }

        .tab-btn.active {
            color: var(--accent);
            background: #ebf5fb;
        }

        .tab-btn:hover {
            color: var(--accent);
        }

        .form-group {
            margin-bottom: var(--space-3);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--space-1);
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: var(--space-2);
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        select.form-control {
            background: white;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            cursor: pointer;
            font-size: 14px;
        }

        .checkbox input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* Сетка коньков */
        .skates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: var(--space-2);
            margin-top: var(--space-3);
        }

        .skate-item {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: var(--space-3);
            text-align: center;
            transition: all 0.2s;
        }

        .skate-item:hover {
            border-color: var(--accent);
        }

        .skate-item h3 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: var(--space-1);
            color: var(--primary);
        }

        .skate-item p {
            font-size: 12px;
            color: var(--text-light);
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--space-3);
            margin: var(--space-4) 0;
        }

        .review-card {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: var(--space-3);
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            margin-bottom: var(--space-2);
        }

        .review-avatar {
            width: 40px;
            height: 40px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .review-name {
            font-weight: 600;
            color: var(--primary);
        }

        .review-date {
            font-size: 11px;
            color: var(--text-light);
        }

        .review-text {
            font-size: 13px;
            color: var(--text);
            line-height: 1.6;
        }

        .rating {
            color: #f1c40f;
            margin-top: var(--space-1);
        }
    </style>

    <div class="container">

        <section class="hero">
            <div class="container">
                <h1>Ледовый каток в центре города</h1>
                <p>Профессиональный лед, комфортные раздевалки, уютное кафе</p>
                <button class="btn btn-primary" onclick="document.getElementById('booking').scrollIntoView({behavior: 'smooth'})">
                    Забронировать сейчас
                </button>
            </div>
        </section>

        <!-- Цены -->
        <section id="prices">
            <div class="cards-grid">
                <div class="card">
                    <h2>Входной билет</h2>
                    <div class="price">300 ₽</div>
                    <p>Вход на каток на весь день</p>
                    <button class="btn btn-outline" style="width: 100%;" onclick="showTicketForm()">
                        Купить билет
                    </button>
                </div>

                <div class="card">
                    <h2>Аренда коньков</h2>
                    <div class="price">150 ₽ <small>/час</small></div>
                    <p>Профессиональные коньки всех размеров</p>
                </div>

                <div class="card">
                    <h2>Комбо</h2>
                    <div class="price">300 + 150/час</div>
                    <p>Вход + аренда коньков</p>
                    <button class="btn btn-primary" style="width: 100%;" onclick="showBookingForm()">
                        Забронировать
                    </button>
                </div>
            </div>
        </section>

        <!-- Бронирование -->
        <section id="booking" class="form-section">
            <h2 class="section-title">Бронирование</h2>

            <div class="form-tabs">
                <button class="tab-btn active" id="tabTicket" onclick="switchTab('ticket')">Входной билет</button>
                <button class="tab-btn" id="tabBooking" onclick="switchTab('booking')">С арендой коньков</button>
            </div>

            <!-- Форма билета -->
            <div id="ticketForm">
                <form action="{{ route('book.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="hours" value="1">
                    <input type="hidden" name="needSkates" value="0">

                    <div class="form-group">
                        <label for="ticket_fio">ФИО</label>
                        <input type="text" class="form-control" id="ticket_fio" name="fio" required>
                    </div>

                    <div class="form-group">
                        <label for="ticket_phone">Телефон</label>
                        <input type="text" class="form-control" id="ticket_phone" name="phone"
                               placeholder="+7 (___) ___-__-__" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Оплатить 300 ₽
                    </button>
                </form>
            </div>

            <!-- Форма с арендой -->
            <div id="bookingForm" style="display: none;">
                <form action="{{ route('book.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="booking_fio">ФИО</label>
                        <input type="text" class="form-control" id="booking_fio" name="fio" required>
                    </div>

                    <div class="form-group">
                        <label for="booking_phone">Телефон</label>
                        <input type="text" class="form-control" id="booking_phone" name="phone"
                               placeholder="+7 (___) ___-__-__" required>
                    </div>

                    <div class="form-group">
                        <label for="hours">Количество часов</label>
                        <select class="form-control" id="hours" name="hours" required>
                            <option value="1">1 час</option>
                            <option value="2">2 часа</option>
                            <option value="3">3 часа</option>
                            <option value="4">4 часа</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" id="needSkates" name="needSkates" value="1" onchange="toggleSkates()">
                            <span>Мне нужны коньки</span>
                        </label>
                    </div>

                    <div id="skatesSelect" style="display: none;">
                        <div class="form-group">
                            <label for="skate_id">Выберите коньки</label>
                            <select class="form-control" id="skate_id" name="skate_id">
                                @foreach($skates as $skate)
                                    <option value="{{ $skate->id }}">
                                        {{ $skate->model }} - размер {{ $skate->size }} ({{ $skate->quantity }} шт.)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Перейти к оплате
                    </button>
                </form>
            </div>
        </section>

        <!-- Коньки -->
        <section id="skates">
            <h2 class="section-title">Доступные коньки</h2>
            <div class="skates-grid">
                @forelse($skates as $skate)
                    <div class="skate-item">
                        <h3>{{ $skate->model }}</h3>
                        <p>Размер: {{ $skate->size }}</p>
                        <p>В наличии: {{ $skate->quantity }}</p>
                    </div>
                @empty
                    <p style="grid-column: 1/-1; text-align: center; color: var(--text-light); padding: var(--space-4);">
                        Коньки временно отсутствуют
                    </p>
                @endforelse
            </div>
        </section>

        <section id="reviews">
            <h2 class="section-title">Отзывы наших посетителей</h2>
            <div class="reviews-grid">
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar">АН</div>
                        <div>
                            <div class="review-name">Анна</div>
                            <div class="review-date">2 дня назад</div>
                        </div>
                    </div>
                    <div class="review-text">
                        Отличный каток! Лед хороший, раздевалки чистые, есть фен. Коньки дают нормальные, не убитые. Цены адекватные.
                    </div>
                    <div class="rating">★★★★★</div>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar">ДМ</div>
                        <div>
                            <div class="review-name">Дмитрий</div>
                            <div class="review-date">5 дней назад</div>
                        </div>
                    </div>
                    <div class="review-text">
                        Хожу сюда постоянно. Удобное расположение, есть парковка. Персонал приветливый. Рекомендую!
                    </div>
                    <div class="rating">★★★★★</div>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar">ЕК</div>
                        <div>
                            <div class="review-name">Екатерина</div>
                            <div class="review-date">Неделю назад</div>
                        </div>
                    </div>
                    <div class="review-text">
                        Водим детей по выходным. Есть прокат для малышей, тренировки для начинающих. Очень довольны.
                    </div>
                    <div class="rating">★★★★☆</div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            function showTicketForm() {
                switchTab('ticket');
                document.getElementById('booking').scrollIntoView({behavior: 'smooth'});
            }

            function showBookingForm() {
                switchTab('booking');
                document.getElementById('booking').scrollIntoView({behavior: 'smooth'});
            }

            function switchTab(tab) {
                document.getElementById('tabTicket').classList.toggle('active', tab === 'ticket');
                document.getElementById('tabBooking').classList.toggle('active', tab === 'booking');
                document.getElementById('ticketForm').style.display = tab === 'ticket' ? 'block' : 'none';
                document.getElementById('bookingForm').style.display = tab === 'booking' ? 'block' : 'none';
            }

            function toggleSkates() {
                const checkbox = document.getElementById('needSkates');
                document.getElementById('skatesSelect').style.display = checkbox.checked ? 'block' : 'none';
            }

            // Маска телефона
            document.querySelectorAll('input[name="phone"]').forEach(input => {
                input.addEventListener('input', function(e) {
                    let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                    e.target.value = !x[2] ? '+7 ' : '+7 (' + x[2] + ') ' + x[3] + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
                });
            });
        </script>
    @endpush
@endsection
