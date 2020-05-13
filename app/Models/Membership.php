<?php

namespace App\Models;

use App\Events\CouponRedeemed;
use App\Events\CouponUsed;
use App\Events\MembershipCardIssued;
use Backpack\Settings\app\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Member;

class Membership extends Model
{
    use CrudTrait, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'memberships';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
//    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @param $order
     * @deprecated
     */
    public static function issueCard($order)
    {
        $shopper_id    = $order->shopper_id;
        $membership_id = $order->membership_id;
        //first create member
        try {
            $member = Member::ofShopper($order->shopper_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $member = Member::create([
                'membership_id' => $membership_id,
                'shopper_id'    => $shopper_id,
                'joined_at'     => Carbon::now()->toDateTimeString(),
                'level'         => 'A01'
            ]);
        }

        $quantity = $order->quantity;
        for ($i = 1; $i <= $quantity; $i++) {
            $card = MemberCard::create([
                'shopper_id'    => $shopper_id,
                'membership_id' => $membership_id,
                'member_id'     => $member->id,
                'order_id'      => $order->id,
                'purchased_at'  => Carbon::now()->toDateTimeString(),
                'expired_at'    => '2020-9-20' //TODO fixed to config
            ]);

            //update point, purchase and level, issue vouchers
        }
    }

    public function issue($shopper, $order = null)
    {
        $quantity = $order ? $order->quantity : 1;
        $order_id = $order ? $order->id : null;

        for ($i = 1; $i <= $quantity; $i++) {
            $card = MemberCard::create([
                'shopper_id'    => $shopper->id,
                'membership_id' => $this->id,
                'order_id'      => $order_id,
                'member_id'     => $shopper->member->id,
                'purchased_at'  => Carbon::now()->toDateTimeString(),
                'expired_at'    => '2020-9-20' //TODO fixed to config
            ]);
            event(new MembershipCardIssued($card, $order));
        }
    }

    //tj20190911
    public static function getCartInfo()
    {
        $model = self::first();
        if (empty($model)) {
            return null;
        }
        $data = $model->toArray();
        if ($data[ 'image_url' ]) {
            $url                 = env('APP_URL');
            $data[ 'image_url' ] = $url.'/'.$data[ 'image_url' ];
        }
        if ($data[ 'description' ]) {
            $description = strip_tags($data[ 'description' ]);
            preg_match_all('/<img[^>]*?src="([^"]*?)"[^>]*?>/i', $data[ 'description' ], $match);
            $data[ 'description' ] = $description;
            $data[ 'title' ]       = $match[ 1 ][ 0 ] ?? '';
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function vouchers()
    {
        return $this->belongsToMany(
            Voucher::class,
            'membership_vouchers',
            'membership_id',
            'voucher_id'
        )->withTimestamps();
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImageUrlAttribute($value)
    {
        $attribute_name   = "image_url";
        $disk             = config('backpack.base.root_disk_name'); // or use your own disk, defined in config/filesystems.php
        $destination_path = "public/uploads/memberships";// path relative to the disk above

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[ $attribute_name ] = null;
        }
        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it from the root folder
            // that way, what gets saved in the database is the user-accesible URL
            $public_destination_path             = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[ $attribute_name ] = $public_destination_path.'/'.$filename;
        } else {
            $this->attributes[ $attribute_name ] = $value;
        }
    }
}
