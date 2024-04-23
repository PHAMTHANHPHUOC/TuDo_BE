<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTuDorequest;
use App\Models\GiaoDich;
use App\Models\KhachHang;
use App\Models\TuDo;
use Carbon\Carbon;
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
        if ($data) {
            if ($data->gia_ban <= $khach_hang->tong_tien) {
                $user = KhachHang::find($khach_hang->id);
                $so_tien_con_lai = $user->tong_tien - $data->gia_ban;
                $user->update([
                    'tong_tien' => $so_tien_con_lai,
                ]);
                $data->is_active        = 1;
                $data->id_khach_hang    = $user->id;
                $data->save();
                return response()->json([
                    'message'  =>   'Đã Thanh Toán thành công! mã pin tủ đồ của bạn là : ' . $data->pin_active . '!',
                    'status'   =>   true
                ]);
            } else {
                return response()->json([
                    'message'  =>   'Số Tiền Của Bạn Không Đủ Để Thanh Toán',
                    'status'   =>   false
                ]);
            }
        }
    }
    public function update(Request $request)
    {
        $data = TuDo::where('id', $request->id)->first();
        if ($data) {
            $data->update([
                'ten_san_pham'   => $request->ten_san_pham,
                'hinh_anh'       => $request->hinh_anh,
                'gia_ban'        => $request->gia_ban,
                'is_active'      => $request->is_active,
                'pin_active'     => $request->pin_active,
            ]);
            return response()->json([
                'status'    =>   true,
                'message'   =>   'Đã cập nhật danh sách tủ thành công!'
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Không tìm được tủ đồ để cập nhật!'
            ]);
        }
    }

    public function desroy($id)
    {
        $data   =   TuDo::where('id', $id)->first();
        if ($data) {
            $data->delete();
            return response()->json([
                'status'    =>   true,
                'message'   =>   'Đã xóa danh mục thành công!'
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Không tìm được danh mục để xóa!'
            ]);
        }
    }
    public function getData()
    {
        $data   = TuDo::select('tu_dos.*')->get();

        return response()->json([
            'data'  =>  $data
        ]);
    }

    public function changeStatus(Request $request)
    {

        $data = TuDo::where('id', $request->id)->first();
        if ($data) {
            if ($data->is_active == 0) {
                $data->is_active = 1;



            } else {
                $data->is_active = 0;
                $data->id_khach_hang = 0;
                $pin_hash = rand(1000, 9999);
                $data->update([
                    'pin_active'     =>  $pin_hash
                ]);
            }
            $data->save();
            return response()->json([
                'status'    =>   true,
                'message'   =>   'Đã đổi trạng thái danh mục  !',
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Không tìm được danh mục để cập nhật!'
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
    public function dataPinTu()
    {
        $khach_hang = Auth::guard('sanctum')->user();
        $data = TuDo::where('id_khach_hang' , $khach_hang->id)->get();

        return response()->json([
            'data'  =>  $data
        ]);

    }
    public function updatePin(Request $request)
    {
        $data = TuDo::where('id', $request->id)->first();
        if($data){
            $data->update([
                'pin_active' => $request->pin_active
            ]);
            return response()->json([
                'message'  =>   'Đã cập nhật mã pin thành công!',
                'status'   =>   true
            ]);
        }
        return response()->json([
            'message'  =>   'đã cập nhật mã pin thất bại',
            'status'   =>   false
        ]);

    }

}
