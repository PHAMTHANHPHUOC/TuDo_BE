<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTuDorequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        return [
            'ten_san_pham' => 'required',
            'hinh_anh' => 'required',
            'gia_ban' => 'required',
            'has_active' => 'required',
            'pin_active' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'ten_san_pham' => 'Tên Sản Phẩm Không Được Để Trống',
            'hinh_anh' => 'Hình ảnh không được để trống',
            'gia_ban' => 'Giá không được để trống',
            'has_active' => 'has_active không được để trống',
            'pin_active' => 'Nội Dung Chuyển Khoản không được để trống',
        ];
    }
}
