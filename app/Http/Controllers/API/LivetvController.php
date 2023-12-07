<?php

namespace App\Http\Controllers\API;

use App\Genres;
use App\Http\Controllers\Controller;
use App\LiveTV;
use App\Series;
use App\TvCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivetvController extends Controller
{
    public function categories(Request $request)
    {
        $cateogries = TvCategory::query()->where('status', 1)->orderby('id')
//            ->select('id', 'category_name as name', 'category_slug as slug', 'status')
//            ->paginate(config('data.per_page'));
            ->get();

        $data = [];
        foreach ($cateogries as $cateogry) {
            $cateogry->append('liveTvs');
            $cateogry->append('totalItems');
            if ($cateogry->LiveTvs->count() > 0) {
                $data[] = $cateogry;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $data,
//            'data' => $cateogries->items(),
//            'currentPage' => $cateogries->currentPage(),
//            'last_page' => $cateogries->lastPage(),
//            'total' => $cateogries->total(),
            'msg' => ''
        ]);
    }

}
