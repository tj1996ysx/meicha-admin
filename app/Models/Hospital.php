<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Str;

class Hospital extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'hospitals';
    protected $guarded = ['id', 'clear_environments'];
    protected $casts = [
        'environments' => 'array',
        'experts'      => 'array',
    ];
    protected $appends = ['distance'];

    const CITY = '南京';
    const BECOME_BEAUTIFUL_DEMAND = [
        '眼部',
        '鼻部',
        '面部填充',
        '瘦脸',
        '下巴',
        '隆胸',
        '吸脂减肥',
        '私密整形',
        '毛发种植',
        '牙齿美容'
    ];
    const CHANGE_THE_BUDGET = [
        '1万以下',
        '1-5万',
        '5-10万',
        '10万以上'
    ];
    const PHOTO = [
        '素颜正面',
        '素颜侧面',
        '其他'
    ];
    const OTHER_DEMAND = '备注文字（两百字以内）';

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getLevels()
    {
        return [
            '3A' => '三级甲等',
            '3B' => '三级乙等',
            '3C' => '三级丙等',
            '2A' => '二级甲等',
            '2B' => '二级乙等',
            '2C' => '二级丙等',
            '1A' => '一级甲等',
            '1B' => '一级乙等',
            '1C' => '一级丙等',
        ];
    }

    public static function getHospitalList()
    {
        $model = self::first();
        if (empty($model)) {
            return null;
        }
        $data = $model->toArray();
        if ($data[ 'hospital_image' ]) {
            $url                      = env('APP_URL');
            $data[ 'hospital_image' ] = $url.'/'.$data[ 'hospital_image' ];
            $hospitals                = self::get();
            foreach ($hospitals as &$hospital) {
                $hospital->hospital_image = url($hospital->hospital_image);
            }
        }

        return $hospitals;
    }

    public static function getParams()
    {
        $data = [
            'city'                    => self::CITY,
            'become_beautiful_demand' => self::BECOME_BEAUTIFUL_DEMAND,
            'change_the_budget'       => self::CHANGE_THE_BUDGET,
            'photo'                   => self::PHOTO,
            'other_demand'            => self::OTHER_DEMAND
        ];

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function beautyItems()
    {
        return $this->belongsToMany(BeautyItem::class, 'hospital_beauty_items', 'hospital_id', 'item_id')
            ->withTimestamps();
    }

    public function getHospitalImageAttribute()
    {
        if (empty($this->attributes[ 'hospital_image' ])) {
            return url('images/default_hospital.png');
        }

        return url($this->attributes[ 'hospital_image' ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getDistanceAttribute()
    {
        if (request()->get('latitude') && request()->get('longitude')) {
            return get_distance(
                [request()->get('latitude'), request()->get('longitude')],
                [$this->latitude, $this->longitude]
            ). ' km';
        }
        return '';
    }

    public function getEnvironmentsAttribute()
    {
        return $this->toUrlArray($this->attributes[ 'environments' ]);
    }

    public function getExpertsAttribute()
    {
        return $this->toUrlArray($this->attributes[ 'experts' ]);
    }

    public function setHospitalImageAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes[ 'hospital_image' ] = $value;

            return;
        }
        $attribute_name   = "hospital_image";
        $disk             = "uploads";
        $destination_path = "uploads";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    private function toUrlArray($urls)
    {
        try {
            $url_array = json_decode($urls, true);
        } catch (\Exception $e) {
            return [];
        }

        if (!is_array($url_array) || empty($url_array)) {
            return [];
        }

        $result = [];
        foreach ($url_array as $url) {
            $result[] = url($url);
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setEnvironmentsAttribute($value)
    {
        $attribute_name   = "environments";
        $disk             = "uploads";
        $destination_path = "hospitals";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setExpertsAttribute($value)
    {
        $attribute_name   = "experts";
        $disk             = "uploads";
        $destination_path = "hospitals";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
