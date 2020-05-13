<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Invitation
 *
 * @package App\Models
 */
class Invitation extends Model
{
    use CrudTrait;

    protected $table = 'invitations';

    protected $guarded = [''];

    const STATUS_VALID = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_EXPIRED = 3;

    const TYPE_FIRST_LEVEL = 1;
    const TYPE_SECOEND_LEVEL = 2;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = Str::random();
        });
    }

    public function fromShopper()
    {
        return $this->belongsTo(Shopper::class, 'from_shopper_id');
    }

    public function shopper()
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }


    public function scopeValid($query)
    {
        return $query->where('status', static::STATUS_VALID);
    }

    public function accept($shopper)
    {
        if ($this->status != static::STATUS_VALID) {
            return false;
        }
        $this->status     = static::STATUS_ACCEPTED;
        $this->shopper_id = $shopper->id;
        $this->accept_at  = date('Y-m-d H:i:s');
        $this->save();
        return true;
    }

    public function reject($shopper)
    {
        if ($this->status != static::STATUS_VALID) {
            return false;
        }
        $this->status     = static::STATUS_REJECTED;
        $this->shopper_id = $shopper->id;
        $this->reject_at  = date('Y-m-d H:i:s');
        $this->save();
        return true;
    }
}
