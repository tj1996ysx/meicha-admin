<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeautyRequestRequest;
use App\Models\BeautyItem;
use App\Models\BeautyRequest;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index()
    {
        return Hospital::paginate();
    }

    /**
     * enter the hospital search page
     *
     * @deprecated
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchForm()
    {
        $beauty_items = BeautyItem::get();
        $items = [];
        foreach ($beauty_items as $beauty_item) {
            $items[] = [
                'key' => $beauty_item->id,
                'value' => $beauty_item->name
            ];
        }

        return response()->json(['beauty_items' => $items]);
    }

    /**
     * submit hospital search
     * @param BeautyRequestRequest $request
     * @deprecated
     * @return JsonResponse
     */
    public function submitSearch(BeautyRequestRequest $request)
    {
        $shopper = wechat_shopper();
        $beauty_items = $request->input('items', []);
        $estimate = $request->input('estimate', '');
        $remark = $request->input('remark', '');

        $beauty_request = BeautyRequest::create([
            'shopper_id' => $shopper->getKey(),
            'estimate' => $estimate,
            'remark' => $remark
        ]);

        if (!empty($beauty_items)) {
            $beauty_request->beautyItems()->sync($beauty_items);
        }

        $hospitals = Hospital::whereHas('beautyItems', function (Builder $query) use ($beauty_items) {
            $query->whereIn('id', $beauty_items);
        });

        $result = [];
        $hospital_levels = Hospital::getLevels();
        foreach ($hospitals as $hospital) {
            $result[] = [
                'name' => $hospital->name,
                'level' => Arr::get($hospital_levels, $hospital->level, '')
            ];
        }

        return response()->json(['hospitals' => $hospitals]);
    }

    /**
     * Get hospital list
     *
     * @deprecated Use index instead
     * @return JsonResponse
     */
    public function hospitalList()
    {
        $data = Hospital::getHospitalList();
        return response()->json(['data' => $data]);
    }

    public function getParams()
    {
        $data = Hospital::getParams();
        return response()->json(['data' => $data]);
    }

    public function hospitalSearch()
    {
        return response()->json(['data' => 'success']);
    }
}
