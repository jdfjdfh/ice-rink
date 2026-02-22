@extends('layouts.app')

@section('title', 'Админ-панель')

@section('main-class', 'admin-container container')

@section('content')
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: var(--space-3) 0;
        }

        .admin-header h1 {
            font-size: 24px;
            font-weight: 500;
            color: var(--primary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-2);
            margin-bottom: var(--space-3);
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: var(--space-2);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--accent);
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-light);
            margin-top: 4px;
        }

        .admin-tabs {
            display: flex;
            gap: var(--space-1);
            margin: var(--space-3) 0;
            border-bottom: 1px solid var(--border);
            padding-bottom: var(--space-1);
        }

        .admin-tab {
            padding: var(--space-1) var(--space-2);
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-light);
            transition: all 0.2s;
        }

        .admin-tab:hover {
            color: var(--accent);
        }

        .admin-tab.active {
            color: var(--accent);
            border-bottom: 2px solid var(--accent);
        }

        .admin-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: var(--space-3);
            margin-bottom: var(--space-3);
        }

        .admin-card-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: var(--space-2);
            padding-bottom: var(--space-1);
            border-bottom: 1px solid var(--border);
            color: var(--primary);
        }

        .admin-grid-2 {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: var(--space-2);
        }

        .form-group {
            margin-bottom: var(--space-2);
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
            color: var(--text-light);
        }

        .form-control {
            width: 100%;
            padding: var(--space-1);
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
        }

        .btn-sm {
            padding: 2px 8px;
            font-size: 12px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .admin-table th {
            text-align: left;
            padding: var(--space-1);
            background: var(--bg-light);
            font-weight: 500;
            color: var(--primary);
            border-bottom: 2px solid var(--border);
        }

        .admin-table td {
            padding: var(--space-1);
            border-bottom: 1px solid var(--border);
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: var(--space-2);
            border-radius: 4px;
            margin-bottom: var(--space-2);
        }

        .quantity-form {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .quantity-input {
            width: 60px;
            padding: 4px;
            border: 1px solid var(--border);
            border-radius: 3px;
        }
    </style>

    <div class="admin-header">
        <h1>Админ-панель</h1>
        <div>
            <span style="color: var(--text-light); margin-right: var(--space-2);">{{ Auth::user()->name ?? 'Admin' }}</span>
        </div>
    </div>

    <!-- Статистика -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_bookings'] }}</div>
            <div class="stat-label">Всего бронирований</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['paid_bookings'] }}</div>
            <div class="stat-label">Оплачено</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_skates'] }}</div>
            <div class="stat-label">Коньков в наличии</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['today_bookings'] }}</div>
            <div class="stat-label">За сегодня</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: var(--space-2); border-radius: 4px; margin-bottom: var(--space-2);">
            {{ session('error') }}
        </div>
    @endif

    <div class="admin-tabs">
        <button class="admin-tab active" onclick="showTab('skates')">Коньки</button>
        <button class="admin-tab" onclick="showTab('bookings')">Бронирования</button>
        <button class="admin-tab" onclick="showTab('tickets')">Билеты</button>
    </div>

    <!-- Коньки -->
    <div id="skatesTab">
        <div class="admin-grid-2">
            <!-- Добавление -->
            <div class="admin-card">
                <div class="admin-card-title">➕ Добавить коньки</div>
                <form action="{{ route('admin.skates.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Модель</label>
                        <input type="text" class="form-control" name="model" required placeholder="Название">
                    </div>
                    <div class="form-group">
                        <label>Размер</label>
                        <input type="number" class="form-control" name="size" required placeholder="от 20 до 50">
                    </div>
                    <div class="form-group">
                        <label>Количество</label>
                        <input type="number" class="form-control" name="quantity" min="1" required placeholder="мин. 1">
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </form>
            </div>

            <!-- Список -->
            <div class="admin-card">
                <div class="admin-card-title">📋 Список коньков</div>
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Модель</th>
                        <th>Размер</th>
                        <th>Кол-во</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($skates as $skate)
                        <tr>
                            <td>{{ $skate->model }}</td>
                            <td>{{ $skate->size }}</td>
                            <td>
                                <form action="{{ route('admin.skates.update', $skate) }}" method="POST" class="quantity-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $skate->quantity }}" class="quantity-input" min="0">
                                    <button type="submit" class="btn btn-primary btn-sm">OK</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.skates.destroy', $skate) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить?')">✗</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: var(--space-3); color: var(--text-light);">
                                Нет коньков
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Бронирования -->
    <div id="bookingsTab" style="display: none;">
        <div class="admin-card">
            <div class="admin-card-title">📅 Бронирования</div>
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Часы</th>
                    <th>Коньки</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->fio }}</td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->hours }}</td>
                        <td>
                            @if($booking->has_skates)
                                <div>{{ $booking->skate_info }}</div>
                                @if($booking->skate)
                                    <div style="font-size: 11px; color: var(--text-light);">
                                        {{ $booking->skate->model }} ({{ $booking->skate->size }})
                                    </div>
                                @endif
                            @else
                                Свои коньки
                            @endif
                        </td>
                        <td>{{ $booking->total_amount }} ₽</td>
                        <td>
                            @if($booking->is_paid)
                                <span class="badge badge-success">Оплачено</span>
                            @else
                                <span class="badge badge-warning">Ожидает</span>
                            @endif
                        </td>
                        <td>{{ $booking->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: var(--space-3); color: var(--text-light);">
                            Нет бронирований
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Билеты -->
    <div id="ticketsTab" style="display: none;">
        <div class="admin-card">
            <div class="admin-card-title">🎫 Оплаченные билеты</div>
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->fio }}</td>
                        <td>{{ $ticket->phone }}</td>
                        <td>{{ $ticket->total_amount }} ₽</td>
                        <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: var(--space-3); color: var(--text-light);">
                            Нет оплаченных билетов
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            function showTab(tab) {
                document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
                document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');

                document.getElementById('skatesTab').style.display = tab === 'skates' ? 'block' : 'none';
                document.getElementById('bookingsTab').style.display = tab === 'bookings' ? 'block' : 'none';
                document.getElementById('ticketsTab').style.display = tab === 'tickets' ? 'block' : 'none';
            }
        </script>
    @endpush
@endsection
