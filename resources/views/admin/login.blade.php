@extends('layouts.app')

@section('title', 'Вход в админ-панель')

@section('main-class', 'container').

@section('content')
    <style>
        .login-wrapper {
            max-width: 360px;
            margin: var(--space-6) auto;
            padding: var(--space-4);
            background: white;
            border: 1px solid var(--border);
            border-radius: 4px;
        }

        .login-title {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: var(--space-3);
            color: var(--primary);
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
            padding: var(--space-1) var(--space-2);
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(52,152,219,0.1);
        }

        .btn-block {
            width: 100%;
            margin-top: var(--space-2);
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: var(--space-2);
            border-radius: 4px;
            margin-bottom: var(--space-3);
            font-size: 13px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: var(--space-2);
            color: var(--text-light);
            text-decoration: none;
            font-size: 13px;
        }

        .back-link:hover {
            color: var(--accent);
        }
    </style>

    <div class="login-wrapper">
        <h1 class="login-title">Вход в админ-панель</h1>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="admin@icerink.ru" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Войти</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">← На главную</a>
    </div>
@endsection
