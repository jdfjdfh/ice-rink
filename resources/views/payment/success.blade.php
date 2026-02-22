@extends('layouts.app')

@section('title', 'Оплата успешна')

@section('content')
    <style>
        .success-wrapper {
            max-width: 600px;
            margin: var(--space-6) auto;
        }

        .success-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: var(--space-5);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .success-icon {
            width: 64px;
            height: 64px;
            background: #27ae60;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto var(--space-3);
        }

        .success-title {
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            margin-bottom: var(--space-1);
            color: var(--primary);
        }

        .success-subtitle {
            text-align: center;
            color: var(--text-light);
            margin-bottom: var(--space-4);
            font-size: 14px;
        }

        .ticket-info {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: var(--space-4);
            margin: var(--space-4) 0;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-3);
            padding-bottom: var(--space-2);
            border-bottom: 1px dashed var(--border);
        }

        .ticket-number {
            font-size: 18px;
            font-weight: 600;
            color: var(--accent);
        }

        .ticket-date {
            font-size: 12px;
            color: var(--text-light);
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--space-2);
            padding: var(--space-1) 0;
        }

        .ticket-label {
            color: var(--text-light);
            font-size: 14px;
        }

        .ticket-value {
            font-weight: 600;
            color: var(--primary);
            font-size: 14px;
        }

        .ticket-total {
            display: flex;
            justify-content: space-between;
            margin-top: var(--space-3);
            padding-top: var(--space-3);
            border-top: 2px solid var(--border);
            font-size: 16px;
            font-weight: 600;
        }

        .total-label {
            color: var(--primary);
        }

        .total-value {
            color: var(--accent);
            font-size: 20px;
        }

        .skate-info {
            background: white;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: var(--space-2);
            margin-top: var(--space-2);
            font-size: 13px;
        }

        .qr-placeholder {
            text-align: center;
            margin: var(--space-4) 0;
            padding: var(--space-3);
            background: white;
            border: 1px solid var(--border);
            border-radius: 4px;
        }

        .qr-code {
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #f3f3f3 25%, transparent 25%);
            margin: 0 auto var(--space-2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--text-light);
            border: 1px solid var(--border);
        }

        .warning-note {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: var(--space-2);
            border-radius: 4px;
            margin: var(--space-4) 0;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .actions {
            display: flex;
            gap: var(--space-2);
            margin-top: var(--space-4);
        }

        .btn {
            flex: 1;
            padding: var(--space-2);
            text-align: center;
        }

        .btn-outline {
            border: 1px solid var(--border);
            background: white;
            color: var(--text);
        }

        .btn-outline:hover {
            background: var(--bg-light);
        }

        .print-hint {
            text-align: center;
            margin-top: var(--space-3);
            color: var(--text-light);
            font-size: 12px;
        }

        @media print {
            .header, .footer, .actions, .print-hint {
                display: none;
            }
            .success-wrapper {
                margin: 0;
                max-width: 100%;
            }
        }
    </style>

    <div class="container">
        <div class="success-wrapper">
            <div class="success-card" id="ticketToPrint">
                <h1 class="success-title">Оплата прошла успешно!</h1>
                <p class="success-subtitle">Ваш билет готов. Сохраните этот экран или сделайте скриншот.</p>

                <div class="ticket-info">
                    <div class="ticket-header">
                        <span class="ticket-number">Билет #{{ $booking->id }}</span>
                        <span class="ticket-date">{{ $booking->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="ticket-row">
                        <span class="ticket-label">ФИО:</span>
                        <span class="ticket-value">{{ $booking->fio }}</span>
                    </div>

                    <div class="ticket-row">
                        <span class="ticket-label">Телефон:</span>
                        <span class="ticket-value">{{ $booking->phone }}</span>
                    </div>

                    <div class="ticket-row">
                        <span class="ticket-label">Дата посещения:</span>
                        <span class="ticket-value">{{ now()->format('d.m.Y') }}</span>
                    </div>

                    <div class="ticket-row">
                        <span class="ticket-label">Входной билет:</span>
                        <span class="ticket-value">300 ₽</span>
                    </div>

                    @if($booking->has_skates)
                        <div class="ticket-row">
                            <span class="ticket-label">Аренда коньков:</span>
                            <span class="ticket-value">{{ $booking->hours }} ч × 150 ₽ = {{ $booking->hours * 150 }} ₽</span>
                        </div>
                        <div class="skate-info">
                            <div>⛸️ {{ $booking->skate_info }}</div>
                            @if($booking->skate)
                                <div style="font-size: 12px; color: var(--text-light); margin-top: 4px;">
                                    Модель: {{ $booking->skate->model }}, размер {{ $booking->skate->size }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="ticket-row">
                            <span class="ticket-label">Аренда коньков:</span>
                            <span class="ticket-value">Свои коньки</span>
                        </div>
                    @endif

                    <div class="ticket-total">
                        <span class="total-label">ИТОГО К ОПЛАТЕ:</span>
                        <span class="total-value">{{ $booking->total_amount }} ₽</span>
                    </div>
                </div>

                <div class="warning-note">
                    <span></span>
                    <span>
                    <strong>Важно:</strong> При входе покажите этот экран или назовите номер билета #{{ $booking->id }}.
                    @if($booking->has_skates && $booking->skate)
                            <br>Ваши коньки: {{ $booking->skate->model }} (размер {{ $booking->skate->size }})
                        @endif
                </span>
                </div>

                <div class="actions">
                    <button onclick="window.print()" class="btn btn-outline">Распечатать</button>
                    <a href="{{ route('home') }}" class="btn btn-primary">На главную</a>
                </div>

                <div class="print-hint">
                    Совет: сделайте скриншот или распечатайте этот билет
                </div>
            </div>
        </div>
    </div>
@endsection
