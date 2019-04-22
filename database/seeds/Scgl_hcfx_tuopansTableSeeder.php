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
                'title' => '班次',
                'name' => 'banci',
                'value' => "A-1\nA-2\nA-3\nB-1\nB-2\nB-3",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            2 => 
            array (
                'title' => '工序',
                'name' => 'gongxu',
                'value' => "A\nB\nCP\nRB\nRD\nRF",
                'suoshu' => 'qcreport',
				'created_at' => $nowtime,
                'updated_at' => $nowtime,

            ),
            3 => 
            array (
                'title' => '检查机类型',
                'name' => 'jianchajileixing',
                'value' => "AOI-1\nAOI-2\nAOI-3\nAOI-4\nVQZ\nMD",
                'suoshu' => 'qcreport',
				'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            4 => 
            array (
                'title' => '检查者一组',
                'name' => 'jianchazhe1',
                'value' => "许瑞萍\n李世英\n张向果\n第小霞\n蔡素英\n孙吻茹\n葛敏\n陈小枝\n李阳",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            5 => 
            array (
                'title' => '检查者二组',
                'name' => 'jianchazhe2',
                'value' => "贾东梅\n蔡小红\n黄俊英\n黎小娟\n张艳敏\n杨晓娟\n朱风婷",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            6 => 
            array (
                'title' => '检查者三组',
                'name' => 'jianchazhe3',
                'value' => "王凤娇\n肖厚春\n朱建珊\n李燕\n张艳红\n贺转云\n曾加英",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            7 => 
            array (
                'title' => '不良内容一（印刷系）',
                'name' => 'buliangneirong1',
                'value' => "连焊\n引脚焊锡量少\nCHIP部品焊锡少\n焊锡球",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            8 => 
            array (
                'title' => '不良内容二（装着系）',
                'name' => 'buliangneirong2',
                'value' => "1005部品浮起.竖立\nCHIP部品横立\n部品浮起.竖立\n欠品\n焊锡未熔解\n位置偏移\n部品打反\n部品错误\n多余部品",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            9 => 
            array (
                'title' => '不良内容三（异物系）',
                'name' => 'buliangneirong3',
                'value' => "异物",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            10 => 
            array (
                'title' => '不良内容四（人系）',
                'name' => 'buliangneirong4',
                'value' => "极性错误\n炉后部品破损\n引脚弯曲\n基板/部品变形后引脚浮起",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            11 => 
            array (
                'title' => '不良内容五（部品系）',
                'name' => 'buliangneirong5',
                'value' => "引脚不上锡\n基板不上锡\nCHIP部品不上锡\n基板不良\n部品不良",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            12 => 
            array (
                'title' => '不良内容六（其他系）',
                'name' => 'buliangneirong6',
                'value' => "其他",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            13 => 
            array (
                'title' => '品名',
                'name' => 'pinming',
                'value' => "DIGITAL\nMAIN",
                'suoshu' => 'qcreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            14 => 
            array (
                'title' => '担当者',
                'name' => 'dandangzhe',
                'value' => "庄慧\n曹平兰",
                'suoshu' => 'pdreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            15 => 
            array (
                'title' => '确认者',
                'name' => 'querenzhe',
                'value' => "庄慧\n曹平兰",
                'suoshu' => 'pdreport',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),

        ));		
		
    }
}
