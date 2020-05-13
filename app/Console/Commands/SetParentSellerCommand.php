<?php

namespace App\Console\Commands;

use App\Models\SellerRebate;
use App\Models\Shopper;
use Illuminate\Console\Command;

class SetParentSellerCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meicha:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set parent seller_id for level 1 seller';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $models = SellerRebate::whereNull('parent_seller_id')->get();
        foreach ($models as $model) {
            $model->parent_seller_id = $model->seller_id;
            $model->save();
            $this->info('updated: '.$model->id);
        }
        $this->info('Update rebate parent seller');


        $models = Shopper::where('role', Shopper::ROLE_SELLER)->update(['seller_level'=>1]);
    }
}
