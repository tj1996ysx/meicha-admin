<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\CouponBatch;
use App\Models\Hospital;
use App\Models\Invitation;
use App\Models\Membership;
use App\Models\Order;
use App\Models\SellerRebate;
use App\Models\Shopper;
use App\Models\ShopperVoucher;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCard()
    {
        $response = $this->json('GET', '/api/card');
        $response->assertStatus(401);
        $this->login();
        $card     = Membership::first();
        $response = $this->json('GET', '/api/card');
        $response->assertStatus(200)->assertJson([
            'data' => [
                'prefix' => $card->prefix,
                'id'     => $card->id,
                'name'   => $card->name,
                'price'  => $card->price,
                'rebate' => $card->rebate,
            ]
        ]);
    }

    public function testGetItems()
    {
        $this->login();
        $response = $this->get('/api/items');
        $response->assertStatus(200);
    }

    public function testBeautyRequestOptions()
    {
        $this->login();
        $response = $this->get('api/beauty_request_options');
        $response->assertJson(config('app.beauty_request'));
    }

    public function testSubmitBeautyRequest()
    {
        $this->login();

        $data     = [
            'mobile'  => '18188888888',
            'remark'  => Str::random(),
            'project' => Str::random(),
            'city'    => Str::random(),
            'budget'  => Str::random(),
        ];
        $response = $this->post('api/beauty_request', $data);
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'mobile', 'shopper_id']);
        $this->assertDatabaseHas('beauty_requests', $data);
    }

    public function testHospitalList()
    {
        $this->login();
        $hospital = factory(Hospital::class, 3)->create();
        $response = $this->get('api/hospitals');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'name'
                    ]
                ]
            ]);
    }

    public function testAssessment()
    {
        $shopper  = $this->login();
        $data     = [
            'shopper_voucher_id' => 1,
            'rate'               => 3,
            'comment'            => Str::random(50)
        ];
        $response = $this->post('api/assessment', $data);
        $response->assertStatus(422);
        $voucher = ShopperVoucher::create([
            'shopper_id'  => $shopper->id,
            'member_id'   => 1,
            'hospital_id' => 1,
            'voucher_id'  => 1,
            'card_id'     => 1,
            'earned_at'   => '2019-01-01 00:00:00',
        ]);
        $data = [
            'voucher_no' => $voucher->voucher_no,
            'rate'       => 3,
            'comment'    => Str::random(50)
        ];

        $response = $this->post('api/assessment', $data);
        $response->assertStatus(200);

        $response = $this->post('api/assessment', $data);
        $response->assertStatus(422);
    }

    public function testRedeemCoupon()
    {
        $shopper      = $this->login();
        $membership   = Membership::first();
        $start        = rand(0, 100);
        $end          = rand(101, 200);
        $coupon_batch = CouponBatch::create([
            'prefix'        => Str::random(3),
            'start_code'    => $start,
            'end_code'      => $end,
            'membership_id' => $membership->id,
        ]);
        $coupons      = Coupon::where('coupon_batch_id', $coupon_batch->id)->get();
        $this->assertEquals(Coupon::where('coupon_batch_id', $coupon_batch->id)->count(), $end - $start + 1);
        $coupon = Coupon::where('coupon_batch_id', $coupon_batch->id)->inRandomOrder()->first();
        $this->assertEquals($coupon->couponBatch->id, $coupon_batch->id);
        $response = $this->post('api/redeem', [
            'password' => $coupon->password
        ]);
        $response->assertStatus(200);
        $coupon->refresh();
        $this->assertEquals($coupon->status, Coupon::STATUS_USED);
        $this->assertEquals($coupon->shopper_id, $shopper->id);
        $this->assertEquals($shopper->vouchers->count(), 5);
        $this->assertEquals($shopper->member->point_balance, round($membership->price));
    }

    public function testRebate($seller = null)
    {
        $shopper = $this->login();

        if (!$seller) {
            $seller = factory(Shopper::class)->create([
                'role'        => Shopper::ROLE_SELLER,
                'rebase_rate' => rand(1, 5) / 10
            ]);
        }

        $shopper->source_shopper_id = $seller->id;
        $shopper->save();

        $membership = Membership::first();

        $order         = Order::create([
            'shopper_id'    => $shopper->id,
            'total_paid'    => $membership->price,
            'membership_id' => $membership->id,
            'quantity'      => 1,
            'request_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $order->status = Order::STATUS_ORDER_SUCCESS;
        $order->save();

        $order->status = Order::STATUS_PAID_SUCCESS;
        $order->save();

        $this->assertEquals($shopper->vouchers->count(), 5);

        $seller->refresh();

        $this->assertDatabaseHas('seller_rebates', [
            'shopper_id' => $shopper->id,
            'seller_id'  => $seller->id,
            'order_id'   => $order->id,
            'amount'     => $membership->price
        ]);
    }

    public function testEnrollSeller()
    {
        $admin = \factory(Shopper::class)->create([
            'role'     => Shopper::ROLE_SELLER,
            'is_admin' => true
        ]);

        $this->login($admin);

        $name     = Str::random();
        $response = $this->call('post', 'api/invitation', [
            'type'   => Invitation::TYPE_FIRST_LEVEL,
            'rebate' => 0.5,
            'name'   => $name
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'code'
        ]);

        $return = json_decode($response->getContent(), true);

        return $return[ 'code' ];
    }

    public function testGetInvitationInfo()
    {
        $from_shopper = factory(Shopper::class)->create();

        $invitation = Invitation::create([
            'status' => Invitation::STATUS_VALID,
            'type' => Invitation::TYPE_SECOEND_LEVEL,
            'from_shopper_id' => $from_shopper->id,
        ]);

        $shopper = $this->login();

        $response = $this->get('api/invitation?code='.$invitation->code);

        $response->assertStatus(200)->assertJson([
            'code' => $invitation->code,
            'from_nickname' => $from_shopper->nickname,
            'type' => Invitation::TYPE_SECOEND_LEVEL,
        ]);
    }

    public function testAcceptInvitation()
    {
        $parent_seller = factory(Shopper::class)->create([
            'role'         => Shopper::ROLE_SELLER,
            'seller_level' => 1,
        ]);
        $name          = Str::random();
        $rebate        = rand(1, 5) / 10;
        $invitation    = Invitation::create([
            'from_shopper_id' => $parent_seller->id,
            'status'          => Invitation::STATUS_VALID,
            'name'            => $name,
            'rebate'          => $rebate,
            'type'            => Invitation::TYPE_FIRST_LEVEL,
        ]);

        $code = $invitation->code;

        $shopper = $this->login();

        $response = $this->post('api/invitation/accept', [
            'code' => $code,
        ]);

        $response->assertStatus(200);

        $shopper = Shopper::find($shopper->id);

        $this->assertEquals($shopper->role, Shopper::ROLE_SELLER);
        $this->assertEquals($shopper->name, $invitation->name);
        $this->assertEquals($shopper->rebase_rate, $invitation->rebate);
        $this->assertEquals($shopper->seller_level, $invitation->type);
        $this->assertEquals($shopper->parent_seller_id, $parent_seller->id);

        // test level 2 seller
        $this->testRebate($shopper);
        $this->assertDatabaseHas('seller_rebates', [
            'seller_id'        => $shopper->id,
            'parent_seller_id' => $parent_seller->id,
        ]);
    }

    public function testGetSellerList()
    {
        // test get sellers list
        $shopper = factory(Shopper::class)->create([
            'role' => Shopper::ROLE_SELLER,
            'seller_level' => 1
        ]);

        factory(Shopper::class, 10)->create([
            'role' => Shopper::ROLE_SELLER,
            'seller_level' => 2,
            'parent_seller_id' => $shopper->id
        ]);

        $this->login($shopper);

        $response = $this->get('api/me/sellers');

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'parent_seller_id' => $shopper->id
                ]
            ]
        ]);

    }

    public function testSellerSales()
    {
        $parent_seller = factory(Shopper::class)->create([
            'role' => Shopper::ROLE_SELLER,
            'seller_level' => 1
        ]);

        $seller = factory(Shopper::class)->create([
            'role' => Shopper::ROLE_SELLER,
            'seller_level' => 2,
            'parent_seller_id' => $parent_seller->id
        ]);


        $total = rand(1, 100);
        factory(SellerRebate::class, $total)->create([
            'seller_id' => $seller->id,
            'parent_seller_id' => $parent_seller->id
        ]);

        $this->login($parent_seller);

        $response = $this->get('api/me/sellers/'.$seller->id.'/sales');

        \Log::debug($response->getContent());
        $response->assertStatus(200)->assertJson([
            'total' => $total,
            'data' => [
                [
                    'seller_id' => $seller->id,
                    'parent_seller_id' => $parent_seller->id
                ]
            ]
        ]);

    }


}
