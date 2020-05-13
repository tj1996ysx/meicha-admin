<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Shopper extends Authenticatable implements JWTSubject
{
    use CrudTrait, SoftDeletes, DateScopeTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shoppers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [];
    const ROLE_SHOPPER = 'shopper';
    const ROLE_SELLER = 'seller';

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->refer_code = uniqid();
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function vouchers()
    {
        return $this->hasMany(ShopperVoucher::class, 'shopper_id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'shopper_id');
    }

    public function couponBatch()
    {
        return $this->hasOne(CouponBatch::class, 'shopper_id');
    }

    public function cards()
    {
        return $this->hasMany(MemberCard::class, 'shopper_id');
    }

    public function rebases()
    {
        return $this->hasMany(SellerRebate::class, 'seller_id');
    }

    public function parent_rebases()
    {
        return $this->hasMany(SellerRebate::class, 'parent_seller_id');
    }

    public function parent_seller()
    {
        return $this->belongsTo(Shopper::class, 'parent_seller_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeWithOpenID($query, $openID)
    {
        return $query->where('open_id', $openID);
    }

    public function scopeAuthed($query)
    {
        return $query->whereNotNull('mobile')->where('mobile', '<>', '');
    }

    public function scopeSeller($query)
    {
        return $query->where('role', static::ROLE_SELLER);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getAvatarAttribute()
    {
        if (empty($this->attributes[ 'avatar' ])) {
            return url('images/default_avatar.jpg');
        }

        return url($this->attributes[ 'avatar' ]);
    }

    public function getShopperNameAttribute()
    {
        if ($this->avatar) {
            $avatar = '<img src="'.$this->avatar.'" width="24px" class="m-r-5" />';
        } else {
            $avatar = '<i class="fa fa-user m-r-5"></i>';
        }

        return $avatar.($this->name ? $this->name.'('.$this->nickname.')' : $this->nickname);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
