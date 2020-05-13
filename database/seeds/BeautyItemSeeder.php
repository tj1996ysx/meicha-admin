<?php

use Illuminate\Database\Seeder;

class BeautyItemSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = env('APP_URL');
        \App\Models\BeautyItem::create([
            'item_code'   => 'Y0001',
            'name'        => '皮肤检测',
            'intro'       => '主要功效：深层肌肤问题可视化，提前预警早知道',
            'description' => '<img alt="" src="'.url('/images/itemsimages/皮肤检测详情页.jpg').'" style="width:100%" />',
            'items_image' => 'images/itemsimages/items_01.jpg',
            'amount'      => '380'
        ]);
        \App\Models\BeautyItem::create([
            'item_code'   => 'X0001',
            'name'        => '小气泡',
            'intro'       => '主要功效：深层清洁补水，肌肤柔嫩换新',
            'description' => '<img alt=""  src="'.url('/images/itemsimages/小气泡详情页.jpg').'" style="width:100%;" />',
            'items_image' => 'images/itemsimages/items_02.jpg',
            'amount'      => '680'
        ]);
        \App\Models\BeautyItem::create([
            'item_code'   => 'G0001',
            'name'        => '光子嫩肤',
            'intro'       => '主要功效：针对不同皮肤瑕疵问题，定制专属化祛斑',
            'description' => '<img alt="" src="'.url('/images/itemsimages/光子嫩肤详情页.jpg').'" style="width:100%;" />',
            'items_image' => 'images/itemsimages/items_03.jpg',
            'amount'      => '3280'
        ]);
        \App\Models\BeautyItem::create([
            'item_code'   => 'G0001',
            'name'        => '果酸焕肤',
            'intro'       => '主要功效：剥离皮肤角质层, 有效针对痘疤, 痘印, 毛孔粗大',
            'description' => '<img alt="" src="'.url('/images/itemsimages/果酸焕肤详情页.jpg').'" style="width:100%;" />',
            'items_image' => 'images/itemsimages/items_04.jpg',
            'amount'      => '780'
        ]);
        \App\Models\BeautyItem::create([
            'item_code'   => 'R0001',
            'name'        => '肉毒素除皱',
            'intro'       => '[和水光针二选一]主要功效：剥离皮肤角质层, 有效针对痘疤, 痘印, 毛孔粗大',
            'description' => '<img alt="" src="'.url('/images/itemsimages/肉毒素除皱详情页.jpg').'" style="width:100%;" />',
            'items_image' => 'images/itemsimages/items_05.jpg',
            'amount'      => '980'
        ]);
        \App\Models\BeautyItem::create([
            'item_code'   => 'S0001',
            'name'        => '水光针',
            'intro'       => '[和单部位除皱二选一] 主要功效：短时间重塑紧致平滑有弹性肌肤',
            'description' => '<img alt="" src="'.url('images/itemsimages/水光针详情页.jpg').'" style="width:100%;" />',
            'items_image' => 'images/itemsimages/items_06.jpg',
            'amount'      => '980'
        ]);
    }
}
