<?php

namespace App\Http\Controllers;

use App\Models\ThongTinThanhToan;
use App\Models\TuDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThongTinThanhToanController extends Controller
{
    public function pin(Request $request)
    {

        $khach_hang = Auth::guard('sanctum')->user();
        $tu_do=TuDo::where('id',$request->id)->first();
        if($tu_do){
            $pin_active =  (151203 +  +  50603);
            ThongTinThanhToan::create([
                'id'=>$request->id,
                'email' => $khach_hang->email,
                'ho_va_ten' => $khach_hang->ho_va_ten,
                'so_dien_thoai' => $khach_hang->so_dien_thoai,
                'pin_active' => $pin_active,
                'id_khach_hang' => $khach_hang->id,
            ]);
            return response()->json([
                'status' => true,
                'message' => "Đã Thanh Toán thành công!"
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => "lỗi"
            ]);
        }

    }
}
