<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

Route::get('/', function (): View {
    $hasBaseTables = Schema::hasTable('users')
        && Schema::hasTable('mangas')
        && Schema::hasTable('genres');

    return view('welcome', [
        'latestMangas' => $hasBaseTables
            ? Manga::with(['genres', 'user'])->latest()->take(6)->get()
            : collect(),
        'stats' => [
            'mangas' => $hasBaseTables ? Manga::count() : 0,
            'genres' => $hasBaseTables ? Genre::count() : 0,
            'users' => $hasBaseTables ? User::count() : 0,
        ],
    ]);
})->name('welcome');

Route::get('/dashboard', function (): View {
    $user = Auth::user();

    return view('dashboard', [
        'stats' => [
            'mangasTotals' => Manga::count(),
            'mangasCreats' => $user->mangas()->count(),
            'mangasColleccio' => $user->collectionMangas()->count(),
            'genresTotals' => Genre::count(),
        ],
    ]);
})->middleware('auth')->name('dashboard');

Route::get('/mangas', [MangaController::class, 'index'])->name('mangas.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('mangas', MangaController::class)->except(['index', 'show']);

    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections/{manga}', [CollectionController::class, 'store'])->name('collections.store');
    Route::patch('/collections/{manga}', [CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{manga}', [CollectionController::class, 'destroy'])->name('collections.destroy');
});

Route::get('/mangas/{manga}', [MangaController::class, 'show'])->name('mangas.show');

Route::middleware(['auth', 'can:is_admin'])->group(function () {
    Route::resource('genres', GenreController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
