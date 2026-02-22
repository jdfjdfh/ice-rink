<?php

namespace App\Http\Controllers;

use App\Models\Skate;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $skates = Skate::all();
        $bookings = Booking::with('skate')->latest()->get();
        $tickets = Booking::where('is_paid', true)->latest()->get();
        $stats = [
            'total_bookings' => Booking::count(),
            'paid_bookings' => Booking::where('is_paid', true)->count(),
            'total_skates' => Skate::sum('quantity'),
            'today_bookings' => Booking::whereDate('created_at', today())->count()
        ];

        return view('admin.dashboard', compact('skates', 'bookings', 'tickets', 'stats'));
    }

    public function skatesStore(Request $request)
    {
        Log::info('===== SKATES STORE STARTED =====');
        Log::info('Request data:', $request->all());
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());

        try {
            // Валидация
            $validated = $request->validate([
                'model' => 'required|string|max:255',
                'size' => 'required|integer|min:20|max:50',
                'quantity' => 'required|integer|min:0'
            ]);

            Log::info('Validation passed:', $validated);

            // Создание записи
            $skate = Skate::create($validated);

            Log::info('Skate created with ID: ' . $skate->id);

            // Редирект с сообщением
            return redirect()->route('admin.dashboard')->with('success', 'Коньки успешно добавлены! ID: ' . $skate->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return redirect()->route('admin.dashboard')->with('error', 'Ошибка валидации: ' . json_encode($e->errors()));

        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return redirect()->route('admin.dashboard')->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    public function skatesUpdate(Request $request, Skate $skate)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $skate->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Количество обновлено');
    }

    public function skatesDestroy(Skate $skate)
    {
        $skate->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Коньки удалены');
    }
}
