<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeautyItem;

class BeautyItemController extends Controller
{
    public function itemList()
    {
        $data = BeautyItem::all();

        return response()->json(['data' => $data]);
    }
}
