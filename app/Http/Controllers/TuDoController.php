<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTuDorequest;
use App\Models\GiaoDich;
use App\Models\KhachHang;
use App\Models\TuDo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TuDoController extends Controller
{
    public function thanhToan(Request $request)
    {
        $khach_hang = Auth::guard('sanctum')->user();
        $data = TuDo::where('id', $request->id)->first();
        if($data)
        {
            if($data->gia_ban <= $khach_hang->tong_tien)
            {
                $user = KhachHang::find($khach_hang->id);
                $so_tien_con_lai = $user->tong_tien - $data->gia_ban;
                $user->update([
                    'tong_tien' => $so_tien_con_lai,
                ]);

                $data->is_active        = 1;
                $data->id_khach_hang    = $user->id;
                $data->save();
                return response()->json([
                    'message'  =>   'Đã Thanh Toán thành công!',
                    'status'   =>   true
                ]);

            }else{
                return response()->json([
                    'message'  =>   'Số Tiền Của Bạn Không Đủ Để Thanh Toán',
                    'status'   =>   false
                ]);

            }


        }

    }
    // public function desroy(Request $request)
    // {
    //     $tu_do = TuDo::where('id' ,$request->id)->first()
    //     if ($tu_do)
    //      {
    //         $tu_do->delete();

    //         return response()->json([
    //             'status' => true,
    //             'message' => "Đã đổi trạng thái tài khoản thành công!"
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => "Có lỗi xảy ra!"
    //         ]);
    //     }
    // }
    public function getData()
    {
        $data   = TuDo::select('tu_dos.*')->get();

        return response()->json([
            'data'  =>  $data
        ]);
    }

    public function keyChuyenKhoan(CreateTuDoRequest $request)
    {
        $data = TuDo::where('id', $request->id)->first();
        $data->update([
            'has_active'    => $request->has_active,
            'ten_san_pham'  => $request->ten_san_pham,
            'hinh_anh'      => $request->hinh_anh,
            'gia_ban'       => $request->gia_ban,
        ]);
        if ($data) {
            if ($data->has_active == $request->pin_active) {

                $data->is_active = 1;
                $data->save();

            } else {
                $data->is_active = 0;
                $data->save();

                return response()->json([
                    'status' => false,
                    'message' => "Vui lòng nhập đúng thông tin chuyển khoản!"
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => "Thanh toán thành công!" ,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Đã có lỗi"
            ]);
        }
    }
    public function store(Request $request)
    {
       $Tu_do = TuDo::create([
            'ten_san_pham'   => $request->ten_san_pham,
            'hinh_anh'       => $request->hinh_anh,
            'gia_ban'        => $request->gia_ban,
            'is_active'      => $request->is_active,
            'has_active'     => $request->has_active,
            'pin_active'     => $request->pin_active,
        ]);

        // Sau khi xong thì BE nên trả về FE thông tin, muốn trả về gì thì do coder
        return response()->json([
            'message'  =>   'Đã tạo mới thành công!',
            'status'   =>   true
        ]);

}

}
