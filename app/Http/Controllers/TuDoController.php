<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTuDorequest;
use App\Models\TuDo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TuDoController extends Controller
{

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
        TuDo::create([
            'ten_san_pham'   => $request->ten_san_pham,
            'hinh_anh'       => $request->hinh_anh,
            'gia_ban'        => $request->gia_ban,
            'is_active'      => $request->is_active,
            'has_active'     => $request->has_active,
            'pin_active'     => $request->pin_active,
        ]);

        // Sau khi xong thì BE nên trả về FE thông tin, muốn trả về gì thì do coder
        return response()->json([
            'message'  =>   'Đã tạo mới thành công!' ,
            'status'   =>   true
        ]);

}
}
