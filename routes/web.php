<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(PageController::class)->group(function () {
    Route::get("/", "index")->name("index");
    Route::get("/article-detail/{slug}", "show")->name("detail");
    Route::get("/category/{slug}", "categorized")->name("categorized");
});

Route::resource('comment', CommentController::class)->only(['store', 'update', 'delete'])->middleware('auth');


Route::middleware(['auth'])->prefix("dashboard")->group(function () {
    Route::resource("article", ArticleController::class);
    Route::resource("category", CategoryController::class)->middleware("can:viewAny," . Category::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/user-list', [HomeController::class, 'users'])->name('users')->can('admin-only');
});

require __DIR__ . '/auth.php';
