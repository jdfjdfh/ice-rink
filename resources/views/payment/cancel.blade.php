@extends('layouts.app')

@section('title', 'Платеж отменен')

@section('content')
    <style>
        .cancel-card {
            max-width: 400px;
            margin: var(--space-8) auto;
            padding: var(--space-6);
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            text-align: center;
        }

        .icon {
            width: 48px;
            height: 48px;
            margin: 0 auto var(--space-4);
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .btn-group {
            display: flex;
            gap: var(--space-2);
            margin-top: var(--space-4);
        }
    </style>

    <div class="container">
        <div class="cancel-card">
            <div class="icon">✗</div>
            <h2 style="margin-bottom: var(--space-2);">{{ $timeout ? 'Время истекло' : 'Платеж отменен' }}</h2>
            <p style="color: var(--text-light);">Попробуйте снова</p>

            <div class="btn-group">
                <a href="{{ route('home') }}" class="btn" style="flex: 1;">На главную</a>
                <a href="{{ route('home') }}#booking" class="btn btn-primary" style="flex: 1;">Снова</a>
            </div>
        </div>
    </div>
@endsection
