<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Pages;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function list()
    {
        return Pages::all()->keyBy('page_slug');
    }
}
