@extends('layouts.app')

@section('title', 'Ожидание оплаты')

@section('content')
    <style>
        .payment-card {
            max-width: 400px;
            margin: var(--space-8) auto;
            padding: var(--space-6);
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            text-align: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto var(--space-4);
            border: 3px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .payment-info {
            margin: var(--space-4) 0;
            padding: var(--space-3);
            background: var(--bg-light);
            border-radius: 4px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--space-2);
            font-size: 14px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .label {
            color: var(--text-light);
        }

        .value {
            font-weight: 500;
            color: var(--primary);
        }

        .btn-group {
            display: flex;
            gap: var(--space-2);
            margin-top: var(--space-4);
        }

        .timer {
            font-size: 24px;
            font-weight: 600;
            color: var(--accent);
            margin: var(--space-2) 0;
        }

        .timer-warning {
            color: #e74c3c;
            font-size: 13px;
            margin-top: var(--space-2);
        }
    </style>

    <div class="container">
        <div class="payment-card">
            <div class="spinner"></div>
            <h2 style="margin-bottom: var(--space-2);">Ожидание оплаты</h2>
            <p style="color: var(--text-light); font-size: 14px;">Мы ожидаем подтверждение платежа</p>

            <!-- Таймер -->
            <div class="timer" id="timer">30</div>
            <div class="timer-warning" id="timerWarning" style="display: none;">
                ⚠️ Время оплаты истекает
            </div>

            <div class="payment-info">
                <div class="info-row">
                    <span class="label">Номер брони:</span>
                    <span class="value">#{{ $booking->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Сумма:</span>
                    <span class="value">{{ $booking->total_amount }} ₽</span>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('home') }}" class="btn" style="flex: 1;">На главную</a>
                <button onclick="checkStatus()" class="btn btn-primary" style="flex: 1;">Проверить</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let timeLeft = 30;
            let timerInterval;
            let checkInterval;

            function updateTimer() {
                const timerElement = document.getElementById('timer');
                const warningElement = document.getElementById('timerWarning');

                timerElement.textContent = timeLeft;

                if (timeLeft <= 5) {
                    timerElement.style.color = '#e74c3c';
                    warningElement.style.display = 'block';
                }

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    clearInterval(checkInterval);
                    window.location.href = '{{ route('payment.cancel', ['booking' => $booking, 'timeout' => 1]) }}';
                }

                timeLeft--;
            }

            function checkStatus() {
                fetch('{{ route('payment.check', $booking) }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.paid) {
                            clearInterval(timerInterval);
                            clearInterval(checkInterval);
                            window.location.href = '{{ route('payment.success', $booking) }}';
                        } else if (data.redirect) {
                            clearInterval(timerInterval);
                            clearInterval(checkInterval);
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            timerInterval = setInterval(updateTimer, 1000);
            checkInterval = setInterval(checkStatus, 3000);

            setTimeout(checkStatus, 1000);
        </script>
    @endpush
@endsection
