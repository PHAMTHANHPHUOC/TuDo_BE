<?php

namespace App\Http\Controllers;

use App\Mail\KichHoatKhachHang;
use App\Mail\QuenMatKhau;
use App\Mail\QuenMatKhauKhachHang;
use App\Models\KhachHang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Str;


class KhachHangController extends Controller
{
    public function kiemTraChiaKhoa()
    {
        $check  = Auth::guard('sanctum')->user();
        if ($check) {
            return response()->json([
                'status'   =>   true,
                'message'  =>   'Ok, bạn có thể đi qua!',
            ]);
        } else {
            return response()->json([
                'status'   =>   false,
                'message'  =>   'Bạn chưa đăng nhập tài khoản!',
            ]);
        }
    }
    public function store(Request $request)
    {

            $tai_khoan = KhachHang::create([
                'email'             => $request->email,
                'so_dien_thoai'     => $request->so_dien_thoai,
                'ho_va_ten'         => $request->ho_va_ten,
                'password'          => bcrypt($request->password),
                'hash_active'       => Str::uuid(),
            ]);

            Mail::to($request->email)->send(new KichHoatKhachHang($tai_khoan->hash_active, $request->ho_va_ten));

            return response()->json([
                'status' => true,
                'message' => "Đăng Kí Tài Khoản Thành Công!"
            ]);

    }

    public function actionLogin(Request $request)
    {
        $check  = Auth::guard('khach_hang')->attempt([
            'email'     => $request->email,
            'password'  => $request->password
        ]);
        if ($check) {
            $user   =   Auth::guard('khach_hang')->user();
            if ($user->is_active == 0) {
                return response()->json([
                    'message'  =>   'Tài khoản của bạn chưa được kích hoạt!',
                    'status'   =>   2
                ]);
            } else {
                if ($user->is_block) {
                    return response()->json([
                        'message'  =>   'Tài khoản của bạn đã bị khóa!',
                        'status'   =>   0
                    ]);
                }

                return response()->json([
                    'message'   =>   'Đã đăng nhập thành công!',
                    'status'    =>   1,
                    'chia_khoa' =>   $user->createToken('ma_so_bi_mat_khach_hang')->plainTextToken,
                ]);
            }
        } else {
            return response()->json([
                'message'  =>   'Tài khoản hoặc mật khẩu không đúng!',
                'status'   =>   0
            ]);
        }
    }
    public function kichHoatTaiKhoan(Request $request)
    {
        $khach_hang = KhachHang::where('id', $request->id)->first();

        if ($khach_hang) {
            if ($khach_hang->is_active == 0) {
                $khach_hang->is_active = 1;
                $khach_hang->save();

                return response()->json([
                    'status' => true,
                    'message' => "Đã kích hoạt tài khoản thành công!"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }
    public function actionQuenmatKhau(Request $request)
    {
        try {
            $quenMK = KhachHang::where('email', $request->email)->first();

            if ($quenMK) {
                $quenMK->hash_reset = Str::uuid();
                $quenMK->save();
                Mail::to($request->email)->send(new QuenMatKhau($quenMK->hash_reset, $quenMK->ho_va_ten));
            }
            return response()->json([
                'status' => true,
                'message' => "Kiểm tra email của bạn !!!"
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => "Đã có lỗi xảy ra!"
            ]);
        }
    }
    public function actionLayLaiMatKhau($hash_reset, Request $request)
    {
        $khach_hang = KhachHang::where('hash_reset', $hash_reset)->first();
        if ($khach_hang) {
            $khach_hang->password = bcrypt($request->password);
            $khach_hang->hash_reset = null;
            $khach_hang->save();
            return response()->json([
                'status' => true,
                'message' => "Mật khẩu đã được thay đổi"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Đã có lỗi xảy ra!"
            ]);
        }
    }
    public function thongTin()
    {
        $khach_hang = Auth::guard('sanctum')->user();

        return response()->json([
            'data' => $khach_hang
        ]);
    }
    public function dangXuat()
    {
        $khach_hang = Auth::guard('sanctum')->user();
        if($khach_hang){
            DB::table('personal_access_tokens')
            -> where('id', $khach_hang->currentAccessToken()->id)->delete();
            return response()->json([
                'status' => true,
                'message' => "Đã Đăng Xuất Thiết Bị Thành Công"
            ]);

        }else{
            return response()->json([
                'status' => false,
                'message' => "Vui lòng đăng nhập"
            ]);
        }

    }
    public function dangXuatALL()
    {
        $khach_hang = Auth::guard('sanctum')->user();
        if($khach_hang){
            $ds_token = $khach_hang->tokens;
            foreach($ds_token as $k => $v){
                $v->delete();
            }
            return response()->json([
                'status' => true,
                'message' => "Đã Đăng Xuất Thiết Bị Thành Công"
            ]);

        }else{
            return response()->json([
                'status' => false,
                'message' => "Vui lòng đăng nhập"
            ]);
        }

    }


    public function updateThongTin(Request $request)
    {
        $khach_hang = Auth::guard('sanctum')->user();

        if ($khach_hang) {
            KhachHang::where('id', $khach_hang->id)->update([
                'email'             => $request->email,
                'so_dien_thoai'     => $request->so_dien_thoai,
                'ho_va_ten'         => $request->ho_va_ten,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Bạn đã cập nhật thông tin thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }

    public function updateMatKhau(Request $request)
    {
        $khach_hang = Auth::guard('sanctum')->user();
        // return response()->json($khach_hang);
        if ($khach_hang) {
            KhachHang::where('id', $khach_hang->id)->update([
                'password'             => bcrypt($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => "Bạn đã cập nhật mật khẩu thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }
    public function kichHoatTaiKhoanKh(Request $request)
    {
        $khach_hang = KhachHang::where('id', $request->id)->first();

        if ($khach_hang) {
            if ($khach_hang->is_active == 0) {
                $khach_hang->is_active = 1;
                $khach_hang->save();

                return response()->json([
                    'status' => true,
                    'message' => "Đã kích hoạt tài khoản thành công!"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }
    public function dataKhachHang()
    {

            $data = KhachHang::get();

            return response()->json([
                'data' => $data
            ]);


    }
    public function doiTrangThaiKhachHang(Request $request)
    {
        $khach_hang = KhachHang::where('id', $request->id)->first();

        if ($khach_hang) {
            $khach_hang->is_block = !$khach_hang->is_block;
            $khach_hang->save();

            return response()->json([
                'status' => true,
                'message' => "Đã đổi trạng thái tài khoản thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }
    public function updateTaiKhoan(Request $request)
    {
        $khach_hang = KhachHang::where('id', $request->id)->first();

        if ($khach_hang) {
            $khach_hang->update([
                'email'             => $request->email,
                'so_dien_thoai'     => $request->so_dien_thoai,
                'ho_va_ten'         => $request->ho_va_ten,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Đã đổi trạng thái tài khoản thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }
    public function deleteTaiKhoan(Request $request)
    {
        $khach_hang = KhachHang::where('id', $request->id)->first();

        if ($khach_hang) {
            $khach_hang->delete();

            return response()->json([
                'status' => true,
                'message' => "Đã đổi trạng thái tài khoản thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }

    public function doiMatKhauTaiKhoan(Request $request)
    {
        $khach_hang = KhachHang::where('id', $request->id)->first();

        if ($khach_hang) {
            $khach_hang->update([
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => "Đã đổi mật khẩu tài khoản thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra!"
            ]);
        }
    }



}
