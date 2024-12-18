<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CheckAuthController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\Friendship\FriendshipController;

// middlewares
use Illuminate\Auth\Middleware\Authenticate;

use App\Http\Controllers\Profile\FriendshipProfileController;

//
//Route::get('{path}', function() {
//    return view('spa'); // Single page application
//})->where('path', '.*');

Route::get('/', function() {
    return view('welcome');
});
Route::get('/main', function() {
    return redirect('/');
});


// Работа с аутентификацией
// Вход пользователя
Route::get('/login', function() {
    return view('welcome');
});
Route::post('/login', [LoginController::class, 'loginStore'])->name('login');

// Регистрация пользователя
Route::get('/signin', function() {
    return view('welcome');
});
Route::post('/signin', [RegisterController::class, 'signinStore'])->name('signin');

// Работа с восстановлением пароля + подтверждением почты
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'index'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'update'])->name('password.update');

// Проверка состояния пользователя
Route::get('/user', [CheckAuthController::class, 'checkAuth'])->name('user');

// Выход
Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

// Контакты сайта
Route::get('/contacts', function() {
    return view('welcome');
});


// Профиль авторизованного пользователя
Route::get('/profile', function() {
    return view('welcome');
});
// Настройки профиля
Route::get('/profile/profileSettings', function() {
    return view('welcome');
});
Route::get('/profile/profileSettings/show', [UserProfileController::class, 'showProfileData'])->name('profile.show');
Route::post('/profile/profileSettings/update', [UserProfileController::class, 'updateProfileData'])->name('profile.update');

// Профиль другого пользователя
Route::get('/profile/{id}', [FriendshipProfileController::class, 'show'])->name('friendshipProfile.show');


// Личная информация
Route::get('/profile/personalData/show', [UserProfileController::class, 'showPersonalData'])->name('personalData.show');
Route::post('/profile/personalData/update', [UserProfileController::class, 'updatePersonalData'])->name('personalData.update');

// Управление друзьями
Route::get('/friendship', function() {
    return view('welcome');
});
Route::get('/friendship/friends', [FriendshipController::class, 'showFriends'])->name('friends.show');
Route::get('/friendship/search', [FriendshipController::class, 'showAll'])->name('all.show');
Route::get('/friendship/pendings', [FriendshipController::class, 'showPendings'])->name('pendings.show');
Route::get('/friendship/followers', [FriendshipController::class, 'showFollowers'])->name('followers.show');
Route::get('/friendship/followings', [FriendshipController::class, 'showFollowings'])->name('followings.show');
Route::get('/friendship/blocked', [FriendshipController::class, 'showBlocked'])->name('blocked.show');
Route::get('/friendship/count', [FriendshipController::class, 'friendshipCount'])->name('friendshipCount.show');

// отправить заявку
Route::post('/friendship/friends/following/{id}', [FriendshipController::class, 'addFollowing'])->name('following.add');
// отменить отправленную заявку
Route::delete('/friendship/friends/following/{id}', [FriendshipController::class, 'cancelFollowing'])->name('following.delete');
// принять заявку
Route::post('/friendship/friends/{id}', [FriendshipController::class, 'addFriend'])->name('friend.add');
// удалить из друзей, отменить заявку пользователя
Route::delete('/friendship/friends/{id}', [FriendshipController::class, 'addFollower'])->name('friend.delete');
// заблокировать пользователя
Route::post('/friendship/friends/block/{id}', [FriendshipController::class, 'blockIt'])->name('block.add');
// разблокировать пользователя
Route::delete('/friendship/friends/block/{id}', [FriendshipController::class, 'unblockIt'])->name('block.delete');

// Работа с группами
Route::get('/groups', function() {
    return view('welcome');
});
Route::get('/groups/edit', function() {
    return view('welcome');
});




// Тестирование
Route::get('/test-session', function () {
    $value = session('test_key', 'default');
    session(['test_key' => 'value']);
    return $value;
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

