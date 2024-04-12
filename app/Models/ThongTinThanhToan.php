<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongTinThanhToan extends Model
{
    use HasFactory;
    protected $table = "thong_tin_thanh_toans";
    protected $filltable = [
        'email',
        'so_dien_thoai',
        'ho_va_ten',
        'is_active',
        'has_active',
        'pin_active',
    ];
}
