<?php /** @noinspection UnusedFunctionResultInspection */

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('oidc.login'));
});

Route::middleware('auth')->group(function () {
    Route::inertia('dashboard', 'Dashboard');

    Route::get('info/libraries', [Controller::class, 'getLibraries'])->name('app.libraries');
    Route::patch('lang', [Controller::class, 'changeLang'])->name('app.lang');
    Route::patch('theme', [Controller::class, 'changeTheme'])->name('app.theme');
});
