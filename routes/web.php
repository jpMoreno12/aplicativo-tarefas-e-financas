<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Goals
    Route::resource('goals', GoalController::class);

    // Finances
    Route::get('/finances', [FinanceController::class, 'index'])->name('finances.index');
    Route::post('/finances/expense', [FinanceController::class, 'storeExpense'])->name('finances.expense.store');
    Route::delete('/finances/expense/{expense}', [FinanceController::class, 'destroyExpense'])->name('finances.expense.destroy');
    Route::patch('/finances/income', [FinanceController::class, 'updateIncome'])->name('finances.income.update');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
});
