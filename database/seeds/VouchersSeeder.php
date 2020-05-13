<?php

use Illuminate\Database\Seeder;

class VouchersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $membership = \App\Models\Membership::find(1);

        $voucher = \App\Models\Voucher::create([
            'name' => 'VISIA皮肤检测',
            'description' => 'VISIA皮肤检测仪是一种能对皮肤的病理学特征进行定量分析的仪器。Visia皮肤检测仪能对皮肤色斑、毛孔、皱纹、平整度、卟啉、紫外线斑和日光损伤定量评估',
            'item_id' => 1,
            'image_url' => 'images/itemsimages/items_01.jpg',
        ]);
        $membership->vouchers()->attach($voucher->id);

        $voucher = \App\Models\Voucher::create([
            'name' => '韩式t区小气泡黑头护理',
            'description' => '去老废角质 美白肌肤 去黑头 除脏污物质 补充营养 强健肌肤',
            'item_id' => 2,
            'image_url' => 'images/itemsimages/items_02.jpg',
        ]);
        $membership->vouchers()->attach($voucher->id);

        $voucher = \App\Models\Voucher::create([
            'name' => '果酸焕肤',
            'description' => '使用高浓度的果酸进行皮肤角质的剥离作用，促使老化角质层脱落，加速角质细胞及少部分上层表皮细胞的更新速度，促进真皮层内弹性纤维增生，对浅层痘疤有较好疗效，也能改善毛孔粗大，但需经多次疗程治疗后才能消除痘疤，其优点是安全，副作用小。',
            'item_id' => 3,
            'image_url' => 'images/itemsimages/items_03.jpg',
        ]);
        $membership->vouchers()->attach($voucher->id);

        $voucher = \App\Models\Voucher::create([
            'name' => '光子祛斑',
            'description' => '光子祛斑方法是通过全新的彩色光子头系列彩色光子头，多光谱不同颜色光，可调激光能量，分别针对不同的皮肤瑕疵问题，嫩肤祛斑效果更突出; 专为东方人设计，更适合东方人的特点;这种方法采用最新科技的等离子阴极发射技术，更加严格控制特定光谱的输出，嫩肤效果更好、更安全。治疗及保养双重效果复合彩光嫩肤祛斑可以对皮肤起到治疗及保养作用，这些皮肤美容的技术都是比较常用的方法，光子祛斑可以让您拥有光滑洁净的肌肤。',
            'item_id' => 4,
            'image_url' => 'images/itemsimages/items_04.jpg',
        ]);
        $membership->vouchers()->attach($voucher->id);

        $voucher = \App\Models\Voucher::create([
            'name' => '水光针 或 单部位除皱',
            'description' => '水光针，是通过借助负压针向皮肤真皮层注入小分子玻尿酸为基底的美容针剂。肉毒素注射除皱注射局部后能阻断乙酰神经胆碱的释放，从而阻断了神经对肌肉的传导。临床上，肉毒素注射除皱通常注射2-3天出现肌肉运动的减弱。但最终轴突可萌芽形成新运动终板，同时无功能的运动单位被吸收，从而肌肉功能逐渐得以恢复。临床上肉毒素注射除皱的效果一般可维持3-6个月。',
            'item_id' => 5,
            'image_url' => 'images/itemsimages/items_05.jpg',
        ]);
        $membership->vouchers()->attach($voucher->id);
    }
}
