<?php

use App\Models\Hospital;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('hospitals')->truncate();
        $hostitals = [
            [
                'name' => '南京医科大学友谊整形外科医院',
                'address' => '江苏省南京市鼓楼区汉中路146号',
                'latitude' => '32.042946',
                'longitude' => '118.773908'
            ],
            [
                'name' => '南京艺星医疗美容医院',
                'address' => '江苏省南京市玄武区洪武北路16号楼东首(全民健身中心旁)',
                'latitude' => '32.042154',
                'longitude' => '118.789073'
            ],
            [
                'name' => '南京康美医疗美容医院',
                'address' => '江苏省南京市秦淮区洪武路288号',
                'latitude' => '32.032512',
                'longitude' => '118.786476'
            ],
            [
                'name' => '南京亚韩医疗美容医院',
                'address' => '江苏省南京市江宁区金箔路468号女人街E区',
                'latitude' => '31.950020',
                'longitude' => '118.851090'
            ],
            [
                'name' => '南京华美医疗美容医院',
                'address' => '江苏省南京市玄武区珠江路655号',
                'latitude' => '32.046470',
                'longitude' => '118.805360'
            ],
            [
                'name' => '南京美莱医疗美容医院',
                'address' => '江苏省南京市鼓楼区广州路188号苏宁环球大厦',
                'latitude' => '32.051123',
                'longitude' => '118.774842'
            ],
            [
                'name' => '南京韩辰医疗美容医院',
                'address' => '江苏省南京市秦淮区洪武路396号',
                'latitude' => '32.029430',
                'longitude' => '118.786000'
            ],

            [
                'name' => '南京施尔美医疗美容医院',
                'address' => '江苏省南京市秦淮区太平南路389号凤凰和睿大厦1-3层',
                'latitude' => '32.029259',
                'longitude' => '118.791556'
            ],
            [
                'name' => '南京美贝尔医疗美容医院',
                'address' => '江苏省南京市鼓楼区石头城路117号丽晶国际1-3层',
                'latitude' => '32.067843',
                'longitude' => '118.747590'
            ],
            [
                'name' => '南京连天美医疗美容医院',
                'address' => '江苏省南京市鼓楼区新模范马路46号',
                'latitude' => '32.077660',
                'longitude' => '118.771580'
            ],
            [
                'name' => '南京华韩奇致医疗美容医院',
                'address' => '江苏省南京市建邺区江东中路126号',
                'latitude' => '32.028020',
                'longitude' => '118.738630'
            ],
            [
                'name' => '南京维多利亚医疗美容医院',
                'address' => '江苏省南京市建邺区虎踞南路100-102号建宇大厦',
                'latitude' => '32.030960',
                'longitude' => '118.769280'
            ],

//            '南京艺星医疗美容医院',
//            '南京康美医疗美容医院',
//            '南京亚韩医疗美容医院',
//            '南京华美医疗美容医院',
//            '南京美莱医疗美容医院',
//            '南京韩辰医疗美容医院',
//            '南京施尔美医疗美容医院',
//            '南京美贝尔医疗美容医院',
//            '南京连天美医疗美容医院',
//            '南京华韩奇致医疗美容医院',
//            '南京维多利亚医疗美容医院',
//            '南京六博医疗美容医院',
        ];

        foreach ($hostitals as $item) {
            $hostital  = $item[ 'name' ];
            $address   = $item[ 'address' ];
            $longitude = $item[ 'longitude' ];
            $latitude  = $item[ 'latitude' ];

            $environment_dir = new DirectoryIterator(public_path('/images/hospital/expert/'.$hostital.'/环境'));
            $environments    = [];
            foreach ($environment_dir as $file) {
                if ($file->isFile()) {
                    $environments[] = url('/images/hospital/expert/'.$hostital.'/环境/'.$file->getFilename());
                }
            }
            $environments = json_encode($environments, JSON_UNESCAPED_UNICODE);

            $experts_dir = new DirectoryIterator(public_path('/images/hospital/expert/'.$hostital.'/专家'));
            $experts     = [];
            foreach ($experts_dir as $file) {
                if ($file->isFile()) {
                    $experts[] = url('/images/hospital/expert/'.$hostital.'/专家/'.$file->getFilename());
                }
            }
            $experts = json_encode($experts, JSON_UNESCAPED_UNICODE);

            DB::table('hospitals')->insert([
                'name'           => $hostital,
                'address'        => $address,
                'longitude'      => $longitude,
                'latitude'       => $latitude,
                'description'    => '<img style="width:100%" src="'.url('images/hospital/detail/'.$hostital.'.jpg').'"/>',
                'hospital_image' => url('images/hospital/logo/'.$hostital.'.jpg'),
                'desc'           => url('images/hospital/desc/'.$hostital.'.jpg'),
                'environments'   => $environments,
                'experts'        => $experts,
                'map'            => url('images/hospital/map/'.$hostital.'.jpg'),
            ]);
        }

    }
}
