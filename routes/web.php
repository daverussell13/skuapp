<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrozenFoodController;
use App\Http\Controllers\TransactionController;
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

Route::group(
    ["middleware" => ["auth"]],
    function() {
        Route::get("/", [AppController::class, "dashboardPage"]);
        Route::get("/logout", [AuthController::class, "logout"]);
        Route::get("/food/table", [FrozenFoodController::class, "table"]);
        Route::get("/food/create", [FrozenFoodController::class, "addFormInput"]);
        Route::get("/food/update/{id}", [FrozenFoodController::class, "updateFormInput"]);
        Route::post("/food/create", [FrozenFoodController::class, "add"]);
        Route::post("/food/search", [FrozenFoodController::class, "search"]);
        Route::put("/food/update/{id}", [FrozenFoodController::class, "update"]);
        Route::delete("/food/{id}", [FrozenFoodController::class, "delete"]);
        Route::get("/transaction/table", [TransactionController::class, "table"]);
        Route::get("/transaction/create", [TransactionController::class, "createForm"]);
        Route::post("/transaction/create", [TransactionController::class, "create"]);
        Route::get("/transaction/detail/{id}", [TransactionController::class, "detail"]);
    }
);

Route::group(
    ["middleware" => ["guest"]],
    function() {
        Route::get("/login", [AuthController::class, "loginPage"])->name("login");
        Route::post('/login', [AuthController::class, "login"]);
    }
);
