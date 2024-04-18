<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\ThongTinChuyenKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Raw;

class DonHangController extends Controller
{
    public function acTionNapTien(Request $request)
    {
        $khach_hang = Auth::guard('sanctum')->user();
        if($khach_hang)
        {
            $don_hang = DonHang::create([
                'ma_don_hang'               => "",
                'tong_tien_thanh_toan'      => $khach_hang->tong_tien,
                'is_thanh_toan'             => 0,   //Khong cần viết dòng nãy cũng được
                'tinh_trang'       => 0,   //Khong cần viết dòng nãy cũng được
                'ten_khach_hang'            => $khach_hang->ho_va_ten,
                'so_dien_thoai'             => $khach_hang->so_dien_thoai,
                'dia_chi_giao_hang'         => "Đà Nẵng",   //Khong cần viết dòng nãy cũng được
                'id_khach_hang'             => $khach_hang->id
            ]);
            $ma_don_hang = "PTP" . (151203 + $don_hang->id);
            $don_hang->ma_don_hang = $ma_don_hang;
            $don_hang->save();


            $link   =   "https://img.vietqr.io/image/MB-0347941497-compact.png?amount="."&addInfo=" . $ma_don_hang;

            $nap_tien = ThongTinChuyenKhoan::find( $khach_hang->id); // Chị hỏi ni, cái dòng ni chỗ ni chị chưa thấy cái dòng nào ghi là thonginchuyenkhoan::create hết
            // vậy thì cái dòng ni nó lấy đâu ra cái thằng %khach_hang -> id ni~~~ em chưa làm create giowd thêm vô sao cị
            // .... vậy làm sao mà nó tìm kiếm đưuojc cái thằng ni dể nó cập nhập bên dưới


            // Cái khúc ni là coded cập nhập cái dòng sẵn có mà mình đi tìm cái trên, cái database cảu em nó có 1 dòng cái nó đập vô kiểu răn ri chị chịu
            // Cái nữa à bên kia
            $nap_tien->ten_nguoi_nhan = $khach_hang->ho_va_ten;
            $nap_tien->so_dien_thoai_nguoi_nhan = $khach_hang->so_dien_thoai;
            $nap_tien->link_qr_code = $link;
            $nap_tien->save();
            return response()->json([
                'status' => true,
                'message' => "Mời Bạn Thanh Toán"
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => "Đã Có Lỗi"
            ]);

        }



    }

}
