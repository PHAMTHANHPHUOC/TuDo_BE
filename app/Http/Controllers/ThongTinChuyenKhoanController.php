<?php

namespace App\Http\Controllers;

use App\Models\ThongTinChuyenKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThongTinChuyenKhoanController extends Controller
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        $data   = ThongTinChuyenKhoan::where('id_khach_hang', $user->id)->first();
        // Cái chỗ này, nếu mà em chọn là get thì nó sẽ đưua ra array
        // vậy nên nếu chỗ này nó hiện ra á chị thắc mắc cái bảng thongtinchuyenkhoan này nó lưu
        // Chỗ cái hàm ni nếu em muốn đưa ra chính xác thì chỗ ni em phải tìm kiếm cái thẳng khách hàng và cái giao dịch nớ luôn
        return response()->json([
            'data'  =>  $data
        ]);
    }
}
