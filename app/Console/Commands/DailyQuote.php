<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use App\User;
use App\Model\product_reward_poin;
class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Reward points for user';

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
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d');
        $product_reward_points_data = DB::select(DB::raw("SELECT * from product_reward_poins where date(expired_at)='$now'"));
        foreach ($product_reward_points_data as $data) {

        $user = User::find($data->user_id);
        $reward_points=$user->reward_points ? $user->reward_points : 0;

            if($reward_points >= $data->reward_point){
                $reward_points=$reward_points - $data->reward_point;
            }
            else{
                $reward_points =0;
            }
        User::where(['id' => $user->id])->update(['reward_points' => $reward_points]);
        product_reward_poin::where('id', $data->id)->delete();
        }
        // \Log::info($product_reward_points_data);
        
    }
}
