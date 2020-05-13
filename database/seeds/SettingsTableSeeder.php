<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = $this->getSettings();
        foreach ($settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }

        $this->command->info('Inserted '.count($settings).' setting records.');
    }

    private function getSettings()
    {
        $settings = [
            [
                'key'         => 'wechat_app_id',
                'name'        => '开发者ID(AppID)',
                'description' => '开发者ID是公众号开发识别码。',
                'value'       => 'wx22d3438df7c61db1',
                'field'       => '{"name":"value","label":"开发者ID","type":"text"}',
                'active'      => 1,
            ],
            [
                'key'           => 'wechat_app_secret',
                'name'          => '开发者密码(AppSecret)',
                'description'   => '开发者密码是校验公众号开发者身份的密码。',
                'value'         => '',
                'field'         => '{"name":"value","label":"开发者密码","type":"password"}',
                'active'        => 1,
            ],
            [
                'key'           => 'wechat_menu',
                'name'          => '微信公众号菜单',
                'description'   => '微信公众号菜单设置',
                'value'         => '[{"type":"view","name":"红人卡","key":"V1001_MEMBER_CARD","url":"https:\/\/meicha.parse.cn\/wechat\/member"},{"type":"view","name":"查医院","key":"V1002_HOSPITAL","url":"https:\/\/meicha.parse.cn\/wechat\/hospital"},{"type":"view","name":"合作","key":"V1003_CONTACT","url":"https:\/\/meicha.parse.cn\/wechat\/contact"}]',
                'field'         => '{"name":"value", "label":" 菜单内容 (JSON格式) ","type":"textarea"}',
                'active'        => 1,
            ],
            [
                'key'           => 'achievement_level',
                'name'          => '会员成就等级',
                'description'   => '定义红人卡的成就等级',
                'value'         => json_encode([
                    ['code' => 'A01', 'label' => '路人', 'min_spending' => 0, 'max_spending' => 198.99],
                    ['code' => 'A02', 'label' => '红人', 'min_spending' => 199, 'max_spending' => 19900.99],
                    ['code' => 'A03', 'label' => '未来之星', 'min_spending' => 19901, 'max_spending' => 1990000.99],
                    ['code' => 'A04', 'label' => '璀璨之星', 'min_spending' => 1990001, 'max_spending' => 199000000],
                ], JSON_UNESCAPED_UNICODE),
                'field'         => '{"name":"value","label":"会员成就等级","type":"textarea"}',
                'active'        => 1,
            ],
            [
                'key'           => 'reservation_fetch_interval',
                'name'          => '获取预约信息的循环周期(s)',
                'description'   => '',
                'value'         => 5,
                'field'         => '{"name":"value","label":"秒数","type":"number"}',
                'active'        => 1,
            ]
        ];

        return $settings;
    }
}
