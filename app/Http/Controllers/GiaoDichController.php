<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\GiaoDich;
use App\Models\KhachHang;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GiaoDichController extends Controller
{
    public function index()
    {
        $client = new Client();
        $payload = [
            "USERNAME"      => "0347941497",
            "PASSWORD"      => "PhamPhuoc1512.",
            "DAY_BEGIN"     => Carbon::today()->format('d/m/Y'),
            "DAY_END"       => Carbon::today()->format('d/m/Y'),
            "NUMBER_MB"     => "0347941497"
        ];

        try {
            $response = $client->post('http://103.137.185.71:2603/mb', [
                'json' => $payload
            ]);

            $data   = json_decode($response->getBody(), true);
            $duLieu = $data['data'];

            foreach ($duLieu as $key => $value) {
                $giaoDich   = GiaoDich::where('pos', $value['pos'])
                    ->where('creditAmount', $value['creditAmount'])
                    ->where('description', $value['description'])
                    ->first();

                if (!$giaoDich) {
                    GiaoDich::create([
                        'creditAmount'      =>  $value['creditAmount'],
                        'description'       =>  $value['description'],
                        'pos'               =>  $value['pos'],
                    ]);
                    // Khi mà chúng ta tạo giao dịch => tìm giao dịch dựa vào description => đổi trạng thái của đơn hàng
                    // Khi mà chúng ta tạo giao dịch => tìm giao dịch dựa vào description => đổi trạng thái của đơn hàng
                    $description = $value['description'];
                    // Tìm vị trí của chuỗi "HDBH"
                    $startIndex = strpos($description, "HDBH");
                    if ($startIndex !== false) {
                        $maDonHang = substr($description, $startIndex, strcspn(substr($description, $startIndex), " \t\n\r\0\x0B"));
                    }

                    $donHang = DonHang::where('ma_don_hang', $maDonHang)
                        ->where('tong_tien_thanh_toan', $value['creditAmount'])
                        ->first();
                    if ($donHang) {
                        // $user = KhachHang::find($khach_hang -> id)->get();
                        // $user->tong_tien = $user->tong_tien + $value['creditAmount'];
                        // $user->save();

                        $donHang->is_thanh_toan = 1;
                        $donHang->save();
                    }
                }
            }
        } catch (Exception $e) {
            echo $e;
        }
    }
}
