<?php

namespace App\Http\Controllers\API;

use App\HomeSection;
use App\Http\Controllers\Controller;
use App\LiveTV;
use App\Movies;
use App\RecentlyWatched;
use App\Section;
use App\Series;
use App\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $get_data = $request->all();

        $response = [];
        $response['slider'] = Slider::query()->where('status', 1)->orderby('id', 'DESC')
            ->get();
        $response['slider'] = $response['slider']->map(function ($item, $index) {
            $item->series = null;
            $item->movie = null;
            $item->liveTv = null;
            if ($item->type == 'Shows') {
                $item->series = Series::find($item->postId);
            } elseif ($item->type == 'Movies') {
                $item->movie = Movies::find($item->postId);
            } elseif ($item->type == 'LiveTV') {
                $item->liveTv = LiveTV::find($item->postId);
            }
            return $item;
        });

        $response['sections'] = Section::query()
            ->where('status', 1)
            ->orderByRaw('ISNULL(order_index), order_index ASC')
            ->get();

        $data = [];
        foreach ($response['sections'] as $key => $section) {

            if ((!empty($section->movie_ids) or !empty($section->series_ids) or !empty($section->livetv_ids))) {
                $data[] = $section;
            }
        }
        //return $data;
        $response['sections'] = $data;

        return response([
            'status' => true,
            'data' => $response,
            'msg' => ''
        ]);
    }

    public function single_section(Request $request, $id)
    {
        $item = Section::query()
            ->where('id', $id)
            //            ->active()
            ->first();
        if (!$item) {
            return response([
                'status' => false,
                'data' => [],
                'msg' => 'Section not found'
            ]);
        }
        $item->append(['movies', 'series']);


        return response([
            'status' => true,
            'data' => $item,
            'msg' => ''
        ]);
    }
}
