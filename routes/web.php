<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UOMController;
use App\Http\Controllers\ItemGroupController;

Route::get('/', function () {
    return redirect()->route('inventory.index');
});

Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
});

Route::get('/storage/items/{filename}', function ($filename) {
    $path = storage_path('app/public/items/' . $filename);
    return response()->file($path);
})->name('item.image');

Route::prefix('uoms')->group(function () {
    Route::get('/', [UOMController::class, 'index'])->name('uoms.index');
    Route::get('create', [UOMController::class, 'create'])->name('uoms.create');
    Route::post('/', [UOMController::class, 'store'])->name('uoms.store');
});

Route::prefix('item-groups')->group(function () {
    Route::get('/', [ItemGroupController::class, 'index'])->name('item-groups.index');
    Route::get('create', [ItemGroupController::class, 'create'])->name('item-groups.create');
    Route::post('/', [ItemGroupController::class, 'store'])->name('item-groups.store');
});
