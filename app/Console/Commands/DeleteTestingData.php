<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteTestingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:testing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All testing data are removed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Do you really wish to delete all testing data?')) {
            DB::table('customer_device_tokens')->delete();
            DB::table('customer_member_ships')->delete();
            DB::table('customer_meta')->delete();
            DB::table('order_customers')->delete();
            DB::table('order_items')->delete();
            DB::table('point_transactions')->delete();
            DB::table('transactions')->delete();
            DB::table('orders')->delete();
            DB::table('wishlists')->delete();
            DB::table('customers')->delete();
            DB::table('notifications')->delete();

            $this->info('All testing data has been successfully deleted.');
        } else {
            $this->info('Action canceled.');
        }
    }
}
