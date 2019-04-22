<?php

use Illuminate\Database\Seeder;

use App\Models\Scgl\Scgl_hcfx_tuopan;

class Scgl_hcfx_tuopansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$nowtime = date("Y-m-d H:i:s",time());
		
		Scgl_hcfx_tuopan::truncate();
		
		// DB::table('configs')->insert(array (
        Scgl_hcfx_tuopan::insert(array (
            0 => 
            array (
                'pinming' => 'Y03熏蒸板',
                'daima' => 'Y03',
                'guige' => '1140*980*130',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            1 => 
            array (
                'pinming' => 'Y06熏蒸板',
                'daima' => 'Y06',
                'guige' => '1220*1010*130',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            2 => 
            array (
                'pinming' => 'Y05熏蒸板',
                'daima' => 'Y05',
                'guige' => '1220*1140*130',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            3 => 
            array (
                'pinming' => 'Y07木托盘',
                'daima' => 'Y07',
                'guige' => '1140*820*130',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            4 => 
            array (
                'pinming' => '刨花板托盘',
                'daima' => 'Y01',
                'guige' => '1160*1120*130',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            5 => 
            array (
                'pinming' => '刨花板托盘',
                'daima' => 'Y02',
                'guige' => '1000*1050*130',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),

        ));		
		
    }
}
