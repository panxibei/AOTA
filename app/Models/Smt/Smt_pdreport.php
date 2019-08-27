<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;
use DB;

class Smt_pdreport extends Model
{
	protected $fillable = [
        'shengchanriqi', 'xianti', 'banci', 'jizhongming', 'spno', 'pinming', 'lotshu', 'gongxu', 'dianmei', 'meimiao', 'meishu', 'shijishengchanshijian', 'shoudongshengchanshijian', 'taishu', 'lotcan', 'chajiandianshu', 'jiadonglv',
		'xinchan', 'dengdaibupin', 'liangchan', 'wujihua', 'qianhougongchengdengdai', 'wubupin', 'bupinanpaidengdai', 'dingqidianjian', 'guzhang', 'xinjizhongshengchanshijian', 'shizuo', 'jizaishixiang', 'luruzhe', 'dandangzhe', 'querenzhe',
    ];
	
    //批量更新
    public function updateBatch($multipleData = [])
    {
        try {
            if (empty($multipleData)) {
                throw new \Exception("数据不能为空");
            }
            $tableName = DB::getTablePrefix() . $this->getTable(); // 表名
            $firstRow  = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默认以id为条件更新，如果没有ID则以第一个字段为条件
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            // 拼接sql语句
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets      = [];
            $bindings  = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn   = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings  = array_merge($bindings, $whereIn);
            $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            // 传入预处理sql语句和对应绑定数据
            return DB::update($updateSql, $bindings);
        } catch (\Exception $e) {
            // dd('Message: ' .$e->getMessage());
            return false;
        }
    }
}
