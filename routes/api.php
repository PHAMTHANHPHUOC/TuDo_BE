<?php

use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NapTienController;
use App\Http\Controllers\TuDoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinController;
use App\Http\Controllers\ThongTinThanhToanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/khach-hang/create', [KhachHangController::class, 'store']);
Route::post('/khach-hang/login', [KhachhangController::class, 'actionLogin']);



Route::post('/admin/kiem-tra-chia-khoa', [KhachHangController::class, 'kiemTraChiaKhoa']);
Route::post('/admin/khach-hang/kich-hoat-tai-khoan', [KhachhangController::class, 'kichHoatTaiKhoan']);
Route::post('/khach-hang/quen-mat-khau', [KhachhangController::class, 'actionQuenmatKhau']);
Route::post('/khach-hang/lay-lai-mat-khau/{hash_reset}', [KhachhangController::class, 'actionLayLaiMatKhau']);
Route::get('/khach-hang/thong-tin', [KhachhangController::class, 'thongTin']);
Route::get('/khach-hang/dang-xuat', [KhachhangController::class, 'dangXuat']);
Route::get('/khach-hang/dang-xuat-all', [KhachhangController::class, 'dangXuatALL']);


Route::post('/khach-hang/update-thong-tin', [KhachhangController::class, 'updateThongTin']);
Route::post('/khach-hang/update-mat-khau', [KhachhangController::class, 'updateMatKhau']);

Route::post('/khach-hang/tao-nap-tien', [NapTienController::class, 'taoNapTien']);





Route::post('/tu-do/thanh-toan', [TuDoController::class, 'thanhToan']);
Route::get('/tu-do/data', [TuDoController::class, 'getData']);
Route::post('/tu-do/key-chuyen-khoan', [TuDoController::class, 'keyChuyenKhoan']);
Route::post('/tu-do/create', [TuDoController::class, 'store']);



Route::post('/thanh-toan/key-chuyen-khoan', [NapTienController::class, 'keyChuyenKhoan']);




