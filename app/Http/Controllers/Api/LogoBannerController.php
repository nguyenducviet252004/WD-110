<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogoBanner;
use Illuminate\Http\Request;

class LogoBannerController extends Controller
{
    public function index()
    {
        try {
            // Lấy tất cả logo banners và phân chia theo type (logo và banner)
            $logoBanners = LogoBanner::all();

            // Chia dữ liệu theo type (logo hoặc banner)
            $groupedData = $logoBanners->groupBy('type')->map(function ($item) {
                return $item->map(function ($logoBanner) {
                    return [
                        'id' => $logoBanner->id,
                        'type' => $logoBanner->type,
                        'title' => $logoBanner->title,
                        'description' => $logoBanner->description,
                        'image' => $logoBanner->image ? asset('storage/' . $logoBanner->image) : null, // Đường dẫn đầy đủ của ảnh
                        'is_active' => $logoBanner->is_active,
                        'created_at' => $logoBanner->created_at,
                        'updated_at' => $logoBanner->updated_at,
                    ];
                });
            });

            return response()->json($groupedData, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    