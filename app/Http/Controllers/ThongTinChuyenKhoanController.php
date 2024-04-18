<?php

namespace App\Http\Controllers;

use App\Models\ThongTinChuyenKhoan;
use Illuminate\Http\Request;

class ThongTinChuyenKhoanController extends Controller
{
    public function getData()
    {
        $data   = ThongTinChuyenKhoan::get();
        // Cái chỗ này, nếu mà em chọn là get thì nó sẽ đưua ra array
        // vậy nên nếu chỗ này nó hiện ra á chị thắc mắc cái bảng thongtinchuyenkhoan này nó lưu
        // Chỗ cái hàm ni nếu em muốn đưa ra chính xác thì chỗ ni em phải tìm kiếm cái thẳng khách hàng và cái giao dịch nớ luôn
        return response()->json([
            'data'  =>  $data
        ]);
    }
}
