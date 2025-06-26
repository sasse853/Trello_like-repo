<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListItemController;


Route::get('/',[UserController::class,'register']);
Route::get('/login',[UserController::class,'login'])->name('login');
Route::post('/Traitement',[UserController::class,'ajout_membre']);
Route::get('/Dashboard',[UserController::class,'user_dashboard']);
Route::post('/Vérification',[UserController::class,'verify_user']);
Route::get('/Modification',[UserController::class,'password_modification']);
Route::post('/Modify', [UserController::class,'updatePassword']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/boards', [BoardsController::class, 'index'])->name('boards.index');
    Route::get('/boards/create', [BoardsController::class, 'create'])->name('boards.create');
    Route::post('/boards/store', [BoardsController::class, 'store'])->name('boards.store');
    Route::get('/boards/{board}', [BoardsController::class, 'show'])->name('boards.show');
    Route::get('/boards/{board}', [BoardsController::class, 'show_single_board'])->name('boards.showoff');
    Route::get('/boards/{board}/edit', [BoardsController::class, 'edit'])->name('boards.edit');
    Route::get('/boards/{board}/invite',[BoardsController::class,'invite_collabs_interface'])->name('invitation');
    Route::delete('/boards/{board}/members/{member}', [BoardsController::class, 'removeMember'])->name('boards.members.remove');    
    Route::post('/boards/{board}/invite', [BoardsController::class, 'inviteMember'])->name('boards.invite');
    Route::put('/boards/{board}', [BoardsController::class, 'update'])->name('boards.update');
    Route::delete('/boards/{board}', [BoardsController::class, 'destroy'])->name('boards.destroy');
    Route::get('/boards/{board}/lists', [ListController::class, 'index_lists'])->name('listes.index');
    Route::post('/boards/{board}/lists', [ListController::class, 'store_lists'])->name('listes.store');
    Route::post('/lists/{list}/items', [ListItemController::class, 'store'])->name('list_items.store');
    Route::patch('/list-items/{item}/toggle', [ListItemController::class, 'toggleCompletion'])->name('list_items.toggle');
    Route::get('/notifications/{id}/read', function ($id) {
        $notification = auth()->id->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.read');
    Route::delete('/list-items/{id}', [ListItemController::class, 'destroy'])->name('list_items.destroy');

    
});


Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm']);
Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail']);
Route::get('/reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-pwd', [UserController::class, 'resetPassword']);
