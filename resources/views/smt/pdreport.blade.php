@extends('smt.layouts.mainbase')

@section('my_title')
SMT - PD report 
@parent
@endsection

@section('my_style')
<style>
.ivu-table .table-info-row td{
	background-color: #2db7f5;
	color: #fff;
}
.ivu-table .table-error-row td{
	background-color: #ff6600;
	color: #fff;
}
.ivu-table td.table-info-column{
	background-color: #2db7f5;
	color: #fff;
}
.ivu-table .table-info-cell-name {
	background-color: #2db7f5;
	color: #fff;
}
.ivu-table .table-info-cell-age {
	background-color: #ff6600;
	color: #fff;
}
.ivu-table .table-info-cell-address {
	background-color: #187;
	color: #fff;
}

.ivu-table td.table-info-column-tingzhishijian {
	background-color: #90A4AE;
	color: #fff;
}

</style>
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_project')
<strong>SMT Daily Production Report</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	@hasanyrole('role_smt_pdreport_write|role_super_admin')
	<Tabs type="card" v-model="currenttabs" :animated="false">
	@else
	<Tabs type="card" active-key="0" :animated="false">
	@endhasanyrole

		@hasanyrole('role_smt_pdreport_write|role_super_admin')
		<Tab-pane label="生产计划导入">

			<i-row :gutter="16">
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="7">
					日期&nbsp;&nbsp;
					<Date-picker v-model.lazy="date_plan_suoshuriqi" :options="date_plan_suoshuriqi_options" @on-change="pdplangets()" type="daterange" size="small" style="width:200px"></Date-picker>
				</i-col>
				<i-col span="1">
					&nbsp;
				</i-col>
				<i-col span="1">
					导入：
				</i-col>
				<i-col span="2">
					<Upload
						:before-upload="uploadstart_plan"
						:show-upload-list="false"
						:format="['xls','xlsx']"
						:on-format-error="handleFormatError"
						:max-size="2048"
						action="/">
						<i-button icon="ios-cloud-upload-outline" :loading="loadingStatus" :disabled="uploaddisabled" size="small">@{{ loadingStatus ? '导入中...' : '导入生产计划' }}</i-button>
					</Upload>
				</i-col>
				<i-col span="2">
					<i-button @click="download_plan()" type="text"><font color="#2db7f5">[下载模板]</font></i-button>
				</i-col>
				<i-col span="10">&nbsp;
				@hasanyrole('role_smt_refreshplan|role_super_admin')
				<Poptip confirm title="确定要刷新生产计划数据吗？" placement="right-start" @on-ok="refreshplan" @on-cancel="" transfer="true">
					<!-- <i-button icon="ios-refresh" :loading="loadingStatus_refreshplan" :disabled="uploaddisabled_refreshplan" @click="refreshplan" type="default" size="small">@{{ loadingStatus_refreshplan ? '刷新中...' : '刷新生产计划' }}</i-button> -->
					<i-button icon="ios-refresh" :loading="loadingStatus_refreshplan" :disabled="uploaddisabled_refreshplan" type="primary" size="small">@{{ loadingStatus_refreshplan ? '刷新中...' : '刷新生产计划' }}</i-button>
				</Poptip>
				@endhasanyrole
				@hasanyrole('role_super_admin')
				&nbsp;
				<Poptip confirm title="确定要清空生产计划表吗？" placement="right-start" @on-ok="truncateplan" @on-cancel="" transfer="true">
					<!-- <i-button icon="ios-sync" @click="truncateplan" type="warning" size="small">清空生产计划表</i-button> -->
					<i-button icon="ios-sync" type="warning" size="small">清空生产计划表</i-button>
				</Poptip>
				@endhasanyrole
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="1">
					&nbsp;
				</i-col>
				<i-col span="4">
					线体&nbsp;&nbsp;
					<i-select v-model.lazy="xianti_plan_filter" clearable style="width:120px"  @on-change="pdplangets()" size="small" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					班次&nbsp;&nbsp;
					<i-select v-model.lazy="banci_plan_filter" clearable style="width:100px" @on-change="pdplangets()" size="small" placeholder="">
						<i-option v-for="item in option_banci_filter" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="1">
					&nbsp;
				</i-col>
				<i-col span="1">
					上传：
				</i-col>
				<i-col span="2">
					<Upload
						:before-upload="uploadstart_plan_table"
						:show-upload-list="false"
						:format="['xls','xlsx']"
						:on-format-error="handleFormatError"
						:max-size="2048"
						action="/">
						<i-button icon="ios-cloud-upload-outline" :loading="loadingStatus_table" :disabled="uploaddisabled_table" size="small" shape="circle">@{{ loadingStatus ? '上传中...' : '上传计划表' }}</i-button>
					</Upload>
				</i-col>
				<i-col span="2">
					<i-button @click="download_plan_table()" icon="ios-download-outline" size="small" shape="circle">下载计划表</i-button>
				</i-col>
				<i-col span="10">
					<font color="#ff9900">* 注意：旧的生产计划内容数据会被覆盖！！</font>
				</i-col>
			</i-row>
			<br><br>

			<i-table height="350" size="small" border :columns="tablecolumns_plan" :data="tabledata_plan"></i-table>
			<br><Page :current="pagecurrent_plan" :total="pagetotal_plan" :page-size="pagepagesize_plan" @on-change="currentpage => oncurrentpagechange_plan(currentpage)" show-total show-elevator></Page><br><br>



		</Tab-pane>

		<Tab-pane label="生产信息录入">

			<Divider orientation="left">生产计划表</Divider>

			<i-row :gutter="16">
				<i-col span="24">
					<i-table ref="planresult" :row-class-name="rowClassName_planresult" height="200" size="small" border no-data-text="选择 <strong>计划日期</strong>、<strong>线体</strong> 和 <strong>班次</strong> 来查询计划！" :columns="tablecolumns_planresult" :data="tabledata_planresult" @on-row-click="(selection, index) => onselectchange_planresult(selection, index)"></i-table>
					&nbsp;
				</i-col>
			</i-row>
			<br><br>

			<Divider orientation="left">生产基本信息</Divider>

			<i-row :gutter="16">
				<i-col span="11">
					<font color="#2db7f5">↓ 1. 选择 <strong>计划日期</strong>、<strong>线体</strong> 和 <strong>班次</strong> 来查询计划。 2. 点选上部计划表项，提高录入速度及有效性。</font>
				</i-col>
				<i-col span="13">
					<i-button @click="download_plan_table()" icon="ios-download-outline" type="primary" size="small" shape="circle">下载计划表</i-button>
				</i-col>
			</i-row>

			<br><br>
			<i-row :gutter="16">
				<i-col span="4">
					<strong><font color="#ff9900">计划日期</font></strong>&nbsp;&nbsp;
					<Date-picker v-model.lazy="jihuariqi" @on-change="pdplanresultgets()" type="date" style="width:120px" placeholder=""></Date-picker>
				</i-col>
				<i-col span="4">
					* <strong><font color="#ff9900">线体</font></strong>&nbsp;&nbsp;
					<i-select v-model.lazy="xianti" @on-change="pdplanresultgets()" clearable style="width:120px" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
					* <strong><font color="#ff9900">班次</font></strong>&nbsp;&nbsp;
					<i-select v-model.lazy="banci" @on-change="pdplanresultgets()" clearable style="width:120px" placeholder="">
						<i-option v-for="item in option_banci" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
					* 生产日期&nbsp;&nbsp;
					<Date-picker v-model.lazy="shengchanriqi" type="date" style="width:120px" placeholder=""></Date-picker>
				</i-col>
				<i-col span="8">
					&nbsp;
				</i-col>
			</i-row>
			<br><br><br>

			<i-row :gutter="16">
				<i-col span="4">
					机种名&nbsp;&nbsp;
					<!-- <i-input v-model.lazy="jizhongming" @on-blur="load_jizhongming()" @on-keyup="jizhongming=jizhongming.toUpperCase()" size="small" clearable style="width: 120px" placeholder=""></i-input> -->
					<i-input v-model.lazy="jizhongming" @on-keyup="jizhongming=jizhongming.toUpperCase()" size="small" clearable style="width: 120px" placeholder=""></i-input>
				</i-col>
				<i-col span="4">
					SP NO.&nbsp;&nbsp;
					<i-input v-model.lazy="spno" size="small" clearable style="width: 120px" placeholder=""></i-input>
				</i-col>
				<i-col span="4">
					品名&nbsp;&nbsp;
					<!-- <i-select v-model.lazy="pinming" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_pinming" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select> -->
					<i-input v-model.lazy="pinming" @on-keyup="pinming=pinming.toUpperCase()" size="small" clearable style="width: 120px" placeholder=""></i-input>
				</i-col>
				<i-col span="4">
					LOT数&nbsp;&nbsp;
					<Input-number v-model.lazy="lotshu" :min="1" size="small" style="width: 120px" placeholder=""></Input-number>
				</i-col>
				<i-col span="4">
					工序&nbsp;&nbsp;
					<!-- <i-select v-model.lazy="gongxu" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_gongxu" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select> -->
					<i-input v-model.lazy="gongxu" @on-keyup="gongxu=gongxu.toUpperCase()" size="small" clearable style="width: 120px" placeholder=""></i-input>
				</i-col>
				<i-col span="4">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="4">
					枚/秒&nbsp;&nbsp;
					<Input-number ref="ref_meimiao" element-id="id_meimiao" v-model.lazy="meimiao" :min="1" size="small" style="width: 120px;" placeholder=""></Input-number>
				</i-col>
				<i-col span="4">
					台数&nbsp;&nbsp;
					<Input-number element-id="id_taishu" v-model.lazy="taishu" :min="0" size="small" style="width: 120px" placeholder=""></Input-number>
				</i-col>
				<i-col span="5">
					手动生产时间（分）&nbsp;&nbsp;
					<Input-number element-id="id_shoudongshengchanshijian" v-model.lazy="shoudongshengchanshijian" :min="0" size="small" style="width: 120px" placeholder=""></Input-number>
				</i-col>
				<i-col span="11">
					&nbsp;
				</i-col>
			</i-row>
			<br><br>

			<Divider orientation="left">机器未运转时间（分）</Divider>

			<!-- <i-row :gutter="16">
				<i-col span="3">
					1.新产切换&nbsp;&nbsp;
					<Input-number element-id="id_xinchan" v-model.lazy="xinchan" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="3">
					1.量产切换&nbsp;&nbsp;
					<Input-number element-id="id_liangchan" v-model.lazy="liangchan" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="2">
				&nbsp;
				</i-col>
				<i-col span="4">
					2.等待部品&nbsp;&nbsp;
					<Input-number element-id="id_dengdaibupin" v-model.lazy="dengdaibupin" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					3.无计划&nbsp;&nbsp;
					<Input-number element-id="id_wujihua" v-model.lazy="wujihua" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					4.前后工程等待&nbsp;&nbsp;
					<Input-number element-id="id_qianhougongchengdengdai" v-model.lazy="qianhougongchengdengdai" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					5.部品欠品&nbsp;&nbsp;
					<Input-number element-id="id_wubupin" v-model.lazy="wubupin" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="4">
					6.部品准备等待&nbsp;&nbsp;
					<Input-number element-id="id_bupinanpaidengdai" v-model.lazy="bupinanpaidengdai" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					7.定期点检&nbsp;&nbsp;
					<Input-number element-id="id_dingqidianjian" v-model.lazy="dingqidianjian" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					8.故障&nbsp;&nbsp;
					<Input-number element-id="id_guzhang" v-model.lazy="guzhang" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="6">
				&nbsp;
					9.新机种生产时间（试作）&nbsp;&nbsp;
					<Input-number element-id="id_shizuo" v-model.lazy="shizuo" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
				&nbsp;
					10.试作&nbsp;&nbsp;
					<Input-number v-model.lazy="shizuo" :min="1" size="small" style="width: 80px"></Input-number> 
				</i-col>
				<i-col span="2">
					&nbsp;
				</i-col>
			</i-row>
			<br><br> -->

			<i-row :gutter="16">
				<i-col span="8">
					1.新产切换&nbsp;&nbsp;
					<Input-number element-id="id_xinchan" v-model.lazy="xinchan" :min="1" size="small" style="width: 80px"></Input-number>
					&nbsp;&nbsp;
					1.量产切换&nbsp;&nbsp;
					<Input-number element-id="id_liangchan" v-model.lazy="liangchan" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="8">
					2.等待部品&nbsp;&nbsp;
					<Input-number element-id="id_dengdaibupin" v-model.lazy="dengdaibupin" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="8">
					3.无计划&nbsp;&nbsp;
					<Input-number element-id="id_wujihua" v-model.lazy="wujihua" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="8">
					<i-input element-id="id_jizaishixiang1" type="textarea" :rows="2" v-model.lazy="jizaishixiang1" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="8">
					<i-input element-id="id_jizaishixiang2" type="textarea" :rows="2" v-model.lazy="jizaishixiang2" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="8">
					<i-input element-id="id_jizaishixiang3" type="textarea" :rows="2" v-model.lazy="jizaishixiang3" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="24">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>
				
			<i-row :gutter="16">
				<i-col span="8">
					4.前后工程等待&nbsp;&nbsp;
					<Input-number element-id="id_qianhougongchengdengdai" v-model.lazy="qianhougongchengdengdai" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="8">
					5.部品欠品&nbsp;&nbsp;
					<Input-number element-id="id_wubupin" v-model.lazy="wubupin" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="8">
					6.部品准备等待&nbsp;&nbsp;
					<Input-number element-id="id_bupinanpaidengdai" v-model.lazy="bupinanpaidengdai" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="8">
					<i-input element-id="id_jizaishixiang4" type="textarea" :rows="2" v-model.lazy="jizaishixiang4" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="8">
					<i-input element-id="id_jizaishixiang5" type="textarea" :rows="2" v-model.lazy="jizaishixiang5" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="8">
					<i-input element-id="id_jizaishixiang6" type="textarea" :rows="2" v-model.lazy="jizaishixiang6" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="24">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="8">
					7.定期点检&nbsp;&nbsp;
					<Input-number element-id="id_dingqidianjian" v-model.lazy="dingqidianjian" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="8">
					8.故障&nbsp;&nbsp;
					<Input-number element-id="id_guzhang" v-model.lazy="guzhang" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="8">
					9.新机种生产时间（试作）&nbsp;&nbsp;
					<Input-number element-id="id_shizuo" v-model.lazy="shizuo" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="8">
					<i-input element-id="id_jizaishixiang7" type="textarea" :rows="2" v-model.lazy="jizaishixiang7" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="8">
					<i-input element-id="id_jizaishixiang8" type="textarea" :rows="2" v-model.lazy="jizaishixiang8" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="8">
					<i-input element-id="id_jizaishixiang9" type="textarea" :rows="2" v-model.lazy="jizaishixiang9" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="24">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="8">
					其他记载事项&nbsp;<i-button @click="modal_jizhaishixiang=true" type="text" size="small"><font color="#2db7f5">[查看说明]</font></i-button><br>
					<i-input element-id="id_jizaishixiang" type="textarea" :rows="2" v-model.lazy="jizaishixiang" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="16">
					<br>&nbsp;&nbsp;
					<i-button @click="create()" icon="ios-create-outline" :disabled="disabled_create" type="primary" size="large">记入</i-button>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<i-button @click="clear()" icon="ios-close-circle-outline" size="large">清除</i-button>
				</i-col>
			</i-row>
			&nbsp;<br><br><br><br><br><br>

		</Tab-pane>
		@endhasanyrole


		<Tab-pane label="生产信息表">
			<br>
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="6">
					* 生产日期&nbsp;&nbsp;
					<Date-picker v-model.lazy="date_filter" :options="date_filter_options" @on-change="dailyreportgets(pagecurrent, pagelast)" type="daterange" size="small" style="width:200px"></Date-picker>
				</i-col>
				<i-col span="4">
					线体&nbsp;&nbsp;
					<i-select v-model.lazy="xianti_filter" clearable style="width:120px"  @on-change="dailyreportgets(pagecurrent, pagelast)" size="small" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>

					<!-- <i-input v-model.lazy="xianti_filter" @on-change="dailyreportgets(pagecurrent, pagelast)" @on-keyup="xianti_filter=xianti_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input> -->
				</i-col>
				<i-col span="4">
					班次&nbsp;&nbsp;
					<i-select v-model.lazy="banci_filter" clearable style="width:120px" @on-change="dailyreportgets(pagecurrent, pagelast)" size="small" placeholder="">
						<i-option v-for="item in option_banci_filter" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>

					<!-- <i-input v-model.lazy="banci_filter" @on-change="dailyreportgets(pagecurrent, pagelast)" @on-keyup="banci_filter=banci_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input> -->
				</i-col>
				<i-col span="4">
					机种名&nbsp;&nbsp;
					<i-input v-model.lazy="jizhongming_filter" @on-change="dailyreportgets(pagecurrent, pagelast)" @on-keyup="jizhongming_filter=jizhongming_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="3">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>
			
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;<br>&nbsp;
				</i-col>
				<i-col span="4">
					导出：&nbsp;&nbsp;&nbsp;&nbsp;
					<Poptip confirm title="确定要导出后台数据吗？" placement="right-start" @on-ok="exportData_pdreport" @on-cancel="" transfer="true">
						<i-button type="default" size="small" icon="ios-download-outline">导出后台数据</i-button>
					</Poptip>
				</i-col>
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="16">
				<font color="#ff9900">* 注意：同时查询当天日期、线体和班次，可显示合计信息。</font>
				</i-col>
			</i-row>
			<br><br>
			
			<i-row :gutter="16">
				
				@hasanyrole('role_smt_pdreport_write|role_super_admin')
				<i-col span="2">
					<Poptip confirm title="确定要删除选择的数据吗？" placement="right-start" @on-ok="ondelete" @on-cancel="" transfer="true">
					<i-button :disabled="boo_delete" icon="ios-trash-outline" type="warning" size="small">删除</i-button>
					</Poptip>
					&nbsp;&nbsp;
				</i-col>
				<i-col span="4">
					担当者&nbsp;&nbsp;
					<i-select v-model.lazy="select_dandangzhe" :disabled="disabled_dandangzhe" @on-change="value => dandangzhechange(value)" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_dandangzhe" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
					确认者&nbsp;&nbsp;
					<i-select v-model.lazy="select_querenzhe" :disabled="disabled_querenzhe" @on-change="value => querenzhechange(value)" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_querenzhe" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				@else
				<i-col span="2">
				&nbsp;
				</i-col>
				<i-col span="4">
				&nbsp;
				</i-col>
				<i-col span="4">
				&nbsp;
				</i-col>
				@endhasanyrole
				
				<i-col span="14">
					<!-- <strong>插件点数小计：@{{ xiaoji_chajiandianshu.toLocaleString() }} &nbsp;&nbsp;&nbsp;&nbsp;稼动率小计：@{{ parseFloat(xiaoji_jiadonglv * 100) + '%' }} &nbsp;&nbsp;&nbsp;&nbsp;合计（分）：@{{ hejifen }}</strong>&nbsp;&nbsp; -->
					<div style="text-align:right"><strong>合计信息 （枚数：@{{ xiaoji_meishu.toLocaleString() }} &nbsp;&nbsp;&nbsp;&nbsp;插件点数：@{{ xiaoji_chajiandianshu.toLocaleString() }} &nbsp;&nbsp;&nbsp;&nbsp;生产时间：@{{ (xiaoji_shengchanshijian/60).toFixed(0).toLocaleString() + '分' }} &nbsp;&nbsp;&nbsp;&nbsp;浪费时间：@{{ (xiaoji_langfeishijian/60).toFixed(0).toLocaleString() + '分' }} &nbsp;&nbsp;&nbsp;&nbsp;部品补充时间：@{{ (xiaoji_bupinbuchongshijian/60).toFixed(0).toLocaleString() + '分' }}）</strong>&nbsp;&nbsp;</div>
				</i-col>
			</i-row>
			<br><br>
			
			
			<i-table height="300" size="small" border :columns="tablecolumns1" :data="tabledata1" @on-selection-change="selection => onselectchange(selection)"></i-table>
			<br>
			
			<Modal v-model="modal_jizhaishixiang" title="机器未运转原因区分表" width="540">
				<div style="text-align:center">
					<i-table height="300" size="small" border :columns="tablecolumns3" :data="tabledata3"></i-table>
				</div>
			</Modal>

			<Modal v-model="modal_jizhaishixiang_detail" title="机器未运转原因 - 记载事项" width="540">
				<div style="white-space: pre-line;">
					@{{ jizaishixiang_detail }}
				</div>
			</Modal>

			<Modal v-model="modal_pdreport_edit1" @on-ok="pdreport_edit_ok1" ok-text="保存" title="编辑 - 生产信息表" width="680">
				<div style="text-align:left">
				<p>
					生产日期：@{{ shengchanriqi_edit }}
					
					&nbsp;&nbsp;&nbsp;&nbsp;

					创建时间：@{{ created_at_edit }}
					
					&nbsp;&nbsp;&nbsp;&nbsp;
					
					更新时间：@{{ updated_at_edit }}
				
				</p>

				<Divider></Divider>
						
				<p>
					机种名&nbsp;&nbsp;
					<i-input v-model.lazy="jizhongming_edit" @on-keyup="jizhongming_edit=jizhongming_edit.toUpperCase()" size="small" clearable style="width: 100px" placeholder=""></i-input>

					&nbsp;&nbsp;&nbsp;&nbsp;

					SP NO.&nbsp;&nbsp;
					<i-input v-model.lazy="spno_edit" size="small" clearable style="width: 120px" placeholder=""></i-input>

					&nbsp;&nbsp;&nbsp;&nbsp;

					品名&nbsp;&nbsp;
					<i-input v-model.lazy="pinming_edit" @on-keyup="pinming_edit=pinming_edit.toUpperCase()" size="small" clearable style="width: 120px" placeholder=""></i-input>

					&nbsp;&nbsp;&nbsp;&nbsp;

					工序&nbsp;&nbsp;
					<i-input v-model.lazy="gongxu_edit" @on-keyup="gongxu_edit=gongxu_edit.toUpperCase()" size="small" clearable style="width: 80px" placeholder=""></i-input>
					
					<br><br>

					LOT数&nbsp;&nbsp;
					<Input-number v-model.lazy="lotshu_edit" :min="1" size="small" style="width: 120px" placeholder=""></Input-number>

					&nbsp;&nbsp;&nbsp;&nbsp;

					枚/秒&nbsp;&nbsp;
					<Input-number v-model.lazy="meimiao_edit" :min="1" size="small" style="width: 120px;" placeholder=""></Input-number>

					&nbsp;&nbsp;&nbsp;&nbsp;

					台数&nbsp;&nbsp;
					<Input-number v-model.lazy="taishu_edit" :min="0" size="small" style="width: 120px" placeholder=""></Input-number>

					<br><br>

					手动生产时间（分）&nbsp;&nbsp;
					<Input-number v-model.lazy="shoudongshengchanshijian_edit" :min="0" size="small" style="width: 120px" placeholder=""></Input-number>

				
				</p>
					
				&nbsp;
				
				</div>	
			</Modal>

			<Modal v-model="modal_pdreport_edit2" @on-ok="pdreport_edit_ok2" ok-text="保存" title="编辑 - 生产信息表" width="760">
				<div style="text-align:left">
				<p>
					生产日期：@{{ shengchanriqi_edit }}
					
					&nbsp;&nbsp;&nbsp;&nbsp;

					创建时间：@{{ created_at_edit }}
					
					&nbsp;&nbsp;&nbsp;&nbsp;
					
					更新时间：@{{ updated_at_edit }}
				
					</p><br>

					<p>
						机种名：@{{ jizhongming_edit }}

						&nbsp;&nbsp;&nbsp;&nbsp;
						
						SP NO：@{{ spno_edit }}

						&nbsp;&nbsp;&nbsp;&nbsp;
						
						品名：@{{ pinming_edit }}
						
						&nbsp;&nbsp;&nbsp;&nbsp;
						
						工序：@{{ gongxu_edit }}

					</p>

				<Divider></Divider>
						
				<p>

					<i-row :gutter="16">
						<i-col span="8">

							1.新产&nbsp;&nbsp;
							<Input-number v-model.lazy="xinchan_edit" :min="1" size="small" style="width: 60px"></Input-number>

							&nbsp;&nbsp;

							1.量产&nbsp;&nbsp;
							<Input-number v-model.lazy="liangchan_edit" :min="1" size="small" style="width: 60px"></Input-number>

						</i-col>
						<i-col span="8">

							2.等待部品&nbsp;&nbsp;
							<Input-number v-model.lazy="dengdaibupin_edit" :min="1" size="small" style="width: 80px"></Input-number>

						</i-col>
						<i-col span="8">

							3.无计划&nbsp;&nbsp;
							<Input-number v-model.lazy="wujihua_edit" :min="1" size="small" style="width: 80px"></Input-number>
						
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="24">
							&nbsp;
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit1" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit2" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit3" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
					</i-row>

					<i-row :gutter="16"><i-col span="24">&nbsp;</i-col></i-row>

					<i-row :gutter="16">
						<i-col span="8">
							4.前后工程等待&nbsp;&nbsp;
							<Input-number v-model.lazy="qianhougongchengdengdai_edit" :min="1" size="small" style="width: 80px"></Input-number>
						</i-col>
						<i-col span="8">
							5.部品欠品&nbsp;&nbsp;
							<Input-number v-model.lazy="wubupin_edit" :min="1" size="small" style="width: 80px"></Input-number>
						</i-col>
						<i-col span="8">
							6.部品准备等待&nbsp;&nbsp;
							<Input-number v-model.lazy="bupinanpaidengdai_edit" :min="1" size="small" style="width: 80px"></Input-number>
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="24">
							&nbsp;
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit4" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit5" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit6" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
					</i-row>



					<i-row :gutter="16"><i-col span="24">&nbsp;</i-col></i-row>

					<i-row :gutter="16">
						<i-col span="8">
							7.定期点检&nbsp;&nbsp;
							<Input-number v-model.lazy="dingqidianjian_edit" :min="1" size="small" style="width: 80px"></Input-number>
						</i-col>
						<i-col span="8">
							8.故障&nbsp;&nbsp;
							<Input-number v-model.lazy="guzhang_edit" :min="1" size="small" style="width: 80px"></Input-number>
						</i-col>
						<i-col span="8">
							9.新机种生产时间（试作）&nbsp;&nbsp;
							<Input-number v-model.lazy="shizuo_edit" :min="1" size="small" style="width: 80px"></Input-number>
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="24">
							&nbsp;
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit7" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit8" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
						<i-col span="8">
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit9" size="small" placeholder="" clearable style="width: 200px"></i-input>
						</i-col>
					</i-row>

					<i-row :gutter="16"><i-col span="24">&nbsp;</i-col></i-row>

					<i-row :gutter="16">
						<i-col span="24">
							其他记载事项&nbsp;<i-button @click="modal_jizhaishixiang=true" type="text" size="small"><font color="#2db7f5">[查看说明]</font></i-button><br>
							<i-input type="textarea" :rows="2" v-model.lazy="jizaishixiang_edit" size="small" placeholder="" clearable style="width: 400px"></i-input>
						</i-col>
					</i-row>


				
				</p>
					
				&nbsp;
				
				</div>	
			</Modal>


			<br>
			<i-table height="350" size="small" border :columns="tablecolumns2" :data="tabledata2"></i-table>
			<br><Page :current="pagecurrent" :total="pagetotal" :page-size="pagepagesize" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total show-elevator></Page><br><br>


		</Tab-pane>

		<Tab-pane label="月报汇总">

			<i-row :gutter="16">
				<br>
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="3">
					查询：&nbsp;&nbsp;
					<Date-picker v-model.lazy="tongji_date_filter" @on-change="tongjigets(tongji_date_filter)" type="month" size="small" style="width:100px"></Date-picker>
				</i-col>
				<i-col span="1">
					&nbsp;
				</i-col>
				<i-col span="3">
					导出：
					&nbsp;&nbsp;
					<Poptip confirm title="确定要导出当前表格数据吗？" placement="right-start" @on-ok="exportData_tongji" @on-cancel="" transfer="true">
						<i-button type="default" size="small" icon="ios-download-outline">导出数据</i-button>
					</Poptip>
				</i-col>
				<i-col span="15">
					<font color="#2db7f5">* 设备型号可在配置页面中调整。</font>
				</i-col>
			</i-row>

			&nbsp;
			<i-row :gutter="16">
				<br>
				<i-col span="24">
					<i-table ref="table_tongji" height="320" size="small" border :columns="tablecolumns_tongji" :data="tabledata_tongji"></i-table>
				</i-col>
			</i-row>


			&nbsp;
			<i-row :gutter="16">
				<br><br>
				<i-col span="24">
					<Collapse simple v-model="collapse_tongji">
						<Panel name="1">
							汇总公式参考
							<p slot="content">

								<span id="jiadonglv"></span>
								<br><br>
								<span id="jizhongqiehuan"></span>
								<br><br>
								<span id="bupinbuchong"></span>
								<br><br>
								<span id="tingzhishijian"></span>

								<script>
									katex.render("\\text{1.稼动率} = \\dfrac{\\text{实际时间}}{\\text{持续时间 - 无计划}} \\times \\text{100}\\%", jiadonglv, {
										throwOnError: false
									});
									katex.render("\\text{2.机种切换（1次）} = \\dfrac{\\text{机种切换（时间）}}{\\text{机种切换（次数）}}", jizhongqiehuan, {
										throwOnError: false
									});
									katex.render("\\text{3.部品补充时间} = \\text{手动生产时间} - \\text{实际生产时间} - \\text{停止时间}", bupinbuchong, {
										throwOnError: false
									});
									katex.render("\\text{4.停止时间} = \\text{机种切换（时间） + 等待部品 + 无计划 + 前后工程等待 + 无部品 + 部品安排等待 + 定期点检 + 故障 + 试作}", tingzhishijian, {
										throwOnError: false
									});
								</script>
								&nbsp;
							</p>
						</Panel>
					</Collapse>
				</i-col>
			</i-row>
			

			&nbsp;
			<Divider orientation="left">打点稼动率计算表</Divider>

			<i-row :gutter="16">
				<br>
				<i-col span="24">
					<i-table ref="table_dadianjiadonglv" height="320" size="small" border :columns="tablecolumns_dadianjiadonglv" :data="tabledata_dadianjiadonglv"></i-table>
				</i-col>
			</i-row>

			&nbsp;
			<i-row :gutter="16">
				<br><br>
				<i-col span="24">
				<Collapse simple v-model="collapse_dadianjiadonglv">
						<Panel name="2">
							打点稼动率公式参考
							<p slot="content">
								<span id="lilundadianshu"></span>
								<br><br>
								<span id="jiadongshijian"></span>
								<br><br>
								<span id="dadianjiadonglv"></span>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<font color="#2db7f5">* 设备能力为常数，单位为K点，可在配置页面中调整。</font>

								<script>
									katex.render("\\text{1.理论打点数 (ld)} = \\text{设备能力 (N)} \\times \\text{稼动时间 (js)}", lilundadianshu, {
										throwOnError: false
									});
									katex.render("\\text{2.稼动时间 (js)} = \\text{持续时间 (chs)} - \\text{无计划 (w)} - \\text{试作 (s)} - \\text{定期点检(dq)}", jiadongshijian, {
										throwOnError: false
									});
									katex.render("\\text{3.打点稼动率 (v)} = \\dfrac{\\text{实际打点数 (sd)}}{ \\frac{\\text{(持续时间 (chs)} - \\text{无计划 (w)} - \\text{试作 (s)} - \\text{定期点检(dq)})}{\\text{1440分}} \\times \\text{设备能力 (N)} \\times \\text{1000} } \\times \\text{100}\\%", dadianjiadonglv, {
										throwOnError: false
									});
								</script>
							</p>
						</Panel>
					</Collapse>
				</i-col>
			</i-row>

		</Tab-pane>

	</Tabs>

</div>

<my-passwordchange></my-passwordchange>

@endsection

@section('my_js_others')
@parent	
<script>
var vm_app = new Vue({
	el: '#app',
	components: {
		'my-passwordchange': httpVueLoader("{{ asset('components/my-passwordchange.vue') }}")
	},
	data: {
		// 修改密码界面
		modal_password_edit: false,
		
		// 记载事项说明
		modal_jizhaishixiang: false,
		modal_jizhaishixiang_detail: false,
		jizaishixiang_detail: '',
		
		// 担当者
		disabled_dandangzhe: true,
		select_dandangzhe: '',
		option_dandangzhe: [
			// {
			// 	value: '庄慧',
			// 	label: '庄慧'
			// },
			// {
			// 	value: '曹平兰',
			// 	label: '曹平兰'
			// }
		],
		
		// 确认者
		disabled_querenzhe: true,
		select_querenzhe: '',
		option_querenzhe: [
			// {
			// 	value: '庄慧1',
			// 	label: '庄慧1'
			// },
			// {
			// 	value: '曹平兰1',
			// 	label: '曹平兰1'
			// }
		],
		
		// 线体
		select_xianti: '',
		option_xianti: [
			{
				value: 'SMT-1',
				label: 'SMT-1'
			},
			{
				value: 'SMT-2',
				label: 'SMT-2'
			},
			{
				value: 'SMT-3',
				label: 'SMT-3'
			},
			{
				value: 'SMT-4',
				label: 'SMT-4'
			},
			{
				value: 'SMT-5',
				label: 'SMT-5'
			},
			{
				value: 'SMT-6',
				label: 'SMT-6'
			},
			{
				value: 'SMT-7',
				label: 'SMT-7'
			},
			{
				value: 'SMT-8',
				label: 'SMT-8'
			},
			{
				value: 'SMT-9',
				label: 'SMT-9'
			},
			{
				value: 'SMT-10',
				label: 'SMT-10'
			}
		],
		
		// 线体
		xianti: '',
		// select_xianti: '',
		option_xianti: [
			{
				value: 'SMT-1',
				label: 'SMT-1'
			},
			{
				value: 'SMT-2',
				label: 'SMT-2'
			},
			{
				value: 'SMT-3',
				label: 'SMT-3'
			},
			{
				value: 'SMT-4',
				label: 'SMT-4'
			},
			{
				value: 'SMT-5',
				label: 'SMT-5'
			},
			{
				value: 'SMT-6',
				label: 'SMT-6'
			},
			{
				value: 'SMT-7',
				label: 'SMT-7'
			},
			{
				value: 'SMT-8',
				label: 'SMT-8'
			},
			{
				value: 'SMT-9',
				label: 'SMT-9'
			},
			{
				value: 'SMT-10',
				label: 'SMT-10'
			}		
		],
		
		// 班次
		banci: '',
		// select_banci: '',
		option_banci: [
			{
				value: 'A',
				label: 'A'
			},
			{
				value: 'B',
				label: 'B'
			},
		],

		// 计划日期
		jihuariqi: '',

		// 生产日期
		shengchanriqi: '',

		// 机种名
		jizhongming: '',
		
		//sp no.
		spno: '',
		
		//品名
		pinming: '',
		option_pinming: [],
		
		//lot数
		lotshu: '',
		
		//枚/秒
		meimiao: '',
		
		//枚数
		// meishu: '',
		
		//手动生产时间
		shoudongshengchanshijian: '',
		
		//台数
		taishu: '',
		
		//工序
		gongxu: '',
		option_gongxu: [],
		
		// 异常
		xinchan: '',
		liangchan: '',
		dengdaibupin: '',
		wujihua: '',
		qianhougongchengdengdai: '',
		wubupin: '',
		bupinanpaidengdai: '',
		dingqidianjian: '',
		guzhang: '',
		// xinjizhongshengchanshijian: '',
		shizuo: '',
		jizaishixiang1: '',
		jizaishixiang2: '',
		jizaishixiang3: '',
		jizaishixiang4: '',
		jizaishixiang5: '',
		jizaishixiang6: '',
		jizaishixiang7: '',
		jizaishixiang8: '',
		jizaishixiang9: '',
		jizaishixiang: '',
		
		
		// 表头1
		tablecolumns1: [
			@hasanyrole('role_smt_pdreport_write|role_super_admin')
			{
				type: 'selection',
				width: 60,
				align: 'center',
				fixed: 'left'
			},
			@endhasanyrole
			{
				type: 'index',
				align: 'center',
				width: 70,
				align: 'center',
				fixed: 'left',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize * (vm_app.pagecurrent - 1)
				}
			},
			{
				title: '生产日期',
				key: 'shengchanriqi',
				align: 'center',
				width: 110,
				render: (h, params) => {
					return h('div', [
						params.row.shengchanriqi.substring(0,10)
					]);
				}
			},
			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80
			},
			{
				title: '班次',
				key: 'banci',
				align: 'center',
				width: 70,
			},
			{
				title: '机种信息',
				align: 'center',
				children: [
					{
						title: '机种名',
						key: 'jizhongming',
						align: 'center',
						width: 110,
						render: (h, params) => {
							return h('div', [
								params.row.jizhongming || ''
							]);
						}
					},
					{
						title: 'SP NO.',
						key: 'spno',
						align: 'center',
						width: 130,
						render: (h, params) => {
							return h('div', [
								params.row.spno || ''
							]);
						}
					},
					{
						title: '品名',
						key: 'pinming',
						align: 'center',
						width: 80,
						render: (h, params) => {
							return h('div', [
								params.row.pinming || ''
							]);
						}
					},
					{
						title: 'LOT数',
						key: 'lotshu',
						align: 'center',
						width: 70,
						render: (h, params) => {
							return h('div', [
								params.row.lotshu ? params.row.lotshu.toLocaleString() : ''
							]);
						}
					}

				]
			},
			{
				title: '程序',
				align: 'center',
				children: [
					{
						title: '工序',
						key: 'gongxu',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.gongxu || ''
							]);
						}
					},
					{
						title: '点/枚',
						key: 'dianmei',
						align: 'center',
						width: 70,
						render: (h, params) => {
							return h('div', [
								params.row.dianmei ? params.row.dianmei.toLocaleString() : ''
							]);
						}
					}
				]
			},
			{
				title: '生产预定及实际',
				align: 'center',
				children: [
					{
						title: '枚/秒',
						key: 'meimiao',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.meimiao || ''
							]);
						}
					},
					{
						title: '枚数',
						key: 'meishu',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.meishu ? params.row.meishu.toLocaleString() : ''
							]);
						}
					},
					{
						title: '手动生产时间',
						key: 'shoudongshengchanshijian',
						align: 'center',
						width: 90,
						renderHeader: (h, params) => {
							return h('div', [
								h('span', {
								}, '手动'),
								h('br', {
								}, ''),
								h('span', {
								}, '生产时间')
							]);
						},
						render: (h, params) => {
							return h('div', [
								params.row.shoudongshengchanshijian ? params.row.shoudongshengchanshijian.toLocaleString() : ''
							]);
						}
					},
					{
						title: '部品补充时间',
						key: 'bupinbuchongshijian',
						align: 'center',
						width: 90,
						renderHeader: (h, params) => {
							return h('div', [
								h('span', {
								}, '部品'),
								h('br', {
								}, ''),
								h('span', {
								}, '补充时间')
							]);
						},
						render: (h, params) => {
							return h('div', [
								params.row.bupinbuchongshijian ? params.row.bupinbuchongshijian.toLocaleString() : ''
							]);
						}
					},
					{
						title: '台数',
						key: 'taishu',
						align: 'center',
						width: 70,
						render: (h, params) => {
							return h('div', [
								params.row.taishu ? params.row.taishu.toLocaleString() : ''
							]);
						}
					},
					{
						title: 'LOT残',
						key: 'lotcan',
						align: 'center',
						width: 70,
						render: (h, params) => {
							return h('div', [
								params.row.lotcan ? params.row.lotcan.toLocaleString() : ''
							]);
						}
					},
					{
						title: '插件点数',
						key: 'chajiandianshu',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						renderHeader: (h, params) => {
							return h('div', [
								h('span', {
								}, '插件'),
								h('br', {
								}, ''),
								h('span', {
								}, '点数')
							]);
						},
						render: (h, params) => {
							return h('div', [
								params.row.chajiandianshu ? params.row.chajiandianshu.toLocaleString() : ''
							]);
						}
					},
					// {
					// 	title: '稼动率',
					// 	key: 'jiadonglv',
					// 	align: 'center',
					// 	width: 70,
					// 	className: 'table-info-column',
					// 	render: (h, params) => {
					// 		return h('div', [
					// 			params.row.jiadonglv ? Math.round(params.row.jiadonglv*100) + '%' : ''
					// 		]);
					// 	}
					// }
				]
			},
			@hasanyrole('role_smt_pdreport_write|role_super_admin')
			{
				title: '操作',
				key: 'action',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						h('Button', {
							props: {
								type: 'info',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.pdreport_edit1(params.row)
								}
							}
						}, '编辑')
					]);
				},
				fixed: 'right'
			},
			@endhasanyrole
			// {
			// 	title: '创建日期',
			// 	key: 'created_at',
			// 	align: 'center',
			// 	width: 160,
			// }
		],
		tabledata1: [],

		// 表头2
		tablecolumns2: [
			// 1
			{
				type: 'index',
				align: 'center',
				width: 70,
				align: 'center',
				fixed: 'left',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize * (vm_app.pagecurrent - 1)
				}
			},
			// 2
			{
				title: '机器未运转时间（分）',
				align: 'center',
				children: [
					{
						title: '1',
						align: 'center',
						children: [
							{
								title: '机种切换',
								align: 'center',
								children: [
									{
										title: '新产',
										key: 'xinchan',
										align: 'center',
										width: 70,
										render: (h, params) => {
											if (params.row.jizaishixiang1) {
												return h('div', [
													h('Button', {
														props: {
															type: 'text',
															size: 'small'
														},
														on: {
															click: () => {
																let jizaishixiang_detail = params.row.jizaishixiang1 || '无内容'
																vm_app.jizaishixiang_detail = jizaishixiang_detail
																vm_app.modal_jizhaishixiang_detail = true
															}
														}
													}, [
														h('span', {
														}, [
															h('Poptip', {
																props: {
																	'word-wrap': params.row.jizaishixiang1.length>20 ? true : false,
																	'trigger': 'hover',
																	'content': params.row.jizaishixiang1
																}
															}, params.row.xinchan)
														])
													]),
												]);
											} else {
												return h('div', [
													params.row.xinchan
												]);
											}
										},
									},
									{
										title: '量产',
										key: 'liangchan',
										align: 'center',
										width: 70,
										render: (h, params) => {
											if (params.row.jizaishixiang1) {
												return h('div', [
													h('Button', {
														props: {
															type: 'text',
															size: 'small'
														},
														on: {
															click: () => {
																let jizaishixiang_detail = params.row.jizaishixiang1 || '无内容'
																vm_app.jizaishixiang_detail = jizaishixiang_detail
																vm_app.modal_jizhaishixiang_detail = true
															}
														}
													}, [
														h('span', {
														}, [
															h('Poptip', {
																props: {
																	'word-wrap': params.row.jizaishixiang1.length>20 ? true : false,
																	'trigger': 'hover',
																	'content': params.row.jizaishixiang1
																}
															}, params.row.liangchan)
														])
													]),
												]);
											} else {
												return h('div', [
													params.row.liangchan
												]);
											}
										},
									}
								]
							}
						]
					},
					{
						title: '2',
						align: 'center',
						children: [
							{
								title: '等待部品',
								key: 'dengdaibupin',
								align: 'center',
								width: 70,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '等待'),
										h('br', {
										}, ''),
										h('span', {
										}, '部品')
									]);
								},
								render: (h, params) => {
									if (params.row.jizaishixiang2) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang2 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang2.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang2
														}
													}, params.row.dengdaibupin)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.dengdaibupin
										]);
									}
								},
							}
						]
					},
					{
						title: '3',
						align: 'center',
						children: [
							{
								title: '无计划',
								key: 'wujihua',
								align: 'center',
								width: 70,
								render: (h, params) => {
									if (params.row.jizaishixiang3) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang3 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang3.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang3
														}
													}, params.row.wujihua)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.wujihua
										]);
									}
								},
							}
						]
					},
					{
						title: '4',
						align: 'center',
						children: [
							{
								title: '前后工程等待',
								key: 'qianhougongchengdengdai',
								align: 'center',
								width: 80,
								render: (h, params) => {
									if (params.row.jizaishixiang4) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang4 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang4.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang4
														}
													}, params.row.qianhougongchengdengdai)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.qianhougongchengdengdai
										]);
									}
								},
							}
						]
					},
					{
						title: '5',
						align: 'center',
						children: [
							{
								title: '部品欠品',
								key: 'wubupin',
								align: 'center',
								width: 70,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '部品'),
										h('br', {
										}, ''),
										h('span', {
										}, '欠品')
									]);
								},
								render: (h, params) => {
									if (params.row.jizaishixiang5) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang5 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang5.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang5
														}
													}, params.row.wubupin)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.wubupin
										]);
									}
								},
							}
						]
					},
					{
						title: '6',
						align: 'center',
						children: [
							{
								title: '部品准备等待',
								key: 'bupinanpaidengdai',
								align: 'center',
								width: 80,
								render: (h, params) => {
									if (params.row.jizaishixiang6) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang6 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang6.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang6
														}
													}, params.row.bupinanpaidengdai)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.bupinanpaidengdai
										]);
									}
								},
							}
						]
					},
					{
						title: '7',
						align: 'center',
						children: [
							{
								title: '定期点检',
								key: 'dingqidianjian',
								align: 'center',
								width: 70,
								render: (h, params) => {
									if (params.row.jizaishixiang7) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang7 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang7.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang7
														}
													}, params.row.dingqidianjian)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.dingqidianjian
										]);
									}
								},
							}
						]
					},
					{
						title: '8',
						align: 'center',
						children: [
							{
								title: '故障',
								key: 'guzhang',
								align: 'center',
								width: 70,
								render: (h, params) => {
									if (params.row.jizaishixiang8) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang8 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang8.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang8
														}
													}, params.row.guzhang)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.guzhang
										]);
									}
								},
							}
						]
					},
					{
						title: '9',
						align: 'center',
						children: [
							{
								title: '新机种生产时间（试作）',
								// key: 'xinjizhongshengchanshijian',
								key: 'shizuo',
								align: 'center',
								width: 110,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '新机种生产'),
										h('br', {
										}, ''),
										h('span', {
										}, '时间（试作）')
									]);
								},
								render: (h, params) => {
									if (params.row.jizaishixiang9) {
										return h('div', [
											h('Button', {
												props: {
													type: 'text',
													size: 'small'
												},
												on: {
													click: () => {
														let jizaishixiang_detail = params.row.jizaishixiang9 || '无内容'
														vm_app.jizaishixiang_detail = jizaishixiang_detail
														vm_app.modal_jizhaishixiang_detail = true
													}
												}
											}, [
												h('span', {
												}, [
													h('Poptip', {
														props: {
															'word-wrap': params.row.jizaishixiang9.length>20 ? true : false,
															'trigger': 'hover',
															'content': params.row.jizaishixiang9
														}
													}, params.row.shizuo)
												])
											]),
										]);
									} else {
										return h('div', [
											params.row.shizuo
										]);
									}
								},
							}
						]
					},
					// {
					// 	title: '10',
					// 	align: 'center',
					// 	children: [
					// 		{
					// 			title: '试作',
					// 			key: 'shizuo',
					// 			align: 'center',
					// 			width: 80
					// 		}
					// 	]
					// },
					{
						title: '其他记载事项<br>查看说明',
						key: 'jizaishixiang',
						align: 'center',
						width: 110,
						renderHeader: (h, params) => {
							return h('div', [
								h('span', {
								}, '其他记载事项'),
								h('br', {
								}, ''),
								h('Button', {
									props: {
										type: 'text',
										size: 'small'
									},
									on: {
										click: () => {
											// vm_app.viewmpoint(params.row)
											vm_app.modal_jizhaishixiang = true
										}
									}
								}, '查看说明')
								
							]);
						},
						render: (h, params) => {
							if (params.row.jizaishixiang) {
								return h('div', [
									h('Button', {
										props: {
											type: 'text',
											size: 'small'
										},
										on: {
											click: () => {
												// vm_app.viewmpoint(params.row)
												let jizaishixiang_detail = params.row.jizaishixiang || '无内容'
												vm_app.jizaishixiang_detail = jizaishixiang_detail

												vm_app.modal_jizhaishixiang_detail = true
											}
										}
									}, [
										// h('span', {
										// }, params.row.jizaishixiang ? params.row.jizaishixiang.substr(0, 6) + '...' : '无内容'),
										h('span', {
										}, [
											// h('Tooltip', {
											// 	props: {
											// 		content: params.row.jizaishixiang,
											// 		placement: 'top',
											// 	}
											// }, params.row.jizaishixiang.substr(0, 6) + ' ...')
											h('Poptip', {
												props: {
													'word-wrap': params.row.jizaishixiang.length>20 ? true : false,
													'trigger': 'hover',
													'content': params.row.jizaishixiang
												}
											}, params.row.jizaishixiang.substr(0, 6) + ' ...')
										])
									]),
									
								]);
							}
						},
					}
				]

			},
			{
				title: '品质确认',
				align: 'center',
				children: [
					{
						title: '定数确认',
						key: 'dingshuqueren',
						align: 'center',
						width: 50
					},
					{
						title: '外观确认',
						key: 'waiguanqueren',
						align: 'center',
						width: 50
					},
					{
						title: '录入者',
						key: 'luruzhe',
						align: 'center',
						width: 80
					},
					{
						title: '编辑者',
						key: 'bianjizhe',
						align: 'center',
						width: 80
					},
					{
						title: '担当者',
						key: 'dandangzhe',
						align: 'center',
						width: 80
					},
					{
						title: '确认者',
						key: 'querenzhe',
						align: 'center',
						width: 80
					}
				]
			},
			@hasanyrole('role_smt_pdreport_write|role_super_admin')
			{
				title: '操作',
				key: 'action',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						h('Button', {
							props: {
								type: 'info',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.pdreport_edit2(params.row)
								}
							}
						}, '编辑')
					]);
				},
				fixed: 'right'
			},
			@endhasanyrole
		],
		tabledata2: [],
		
		
		// 未运转说明表
		// 表头3
		tablecolumns3: [
			{
				title: '机器未运转原因区分表',
				align: 'center',
				children: [
					{
						type: 'index',
						width: 60,
						align: 'center'
					},
					{
						title: '原因区分',
						key: 'yuanyinqufeng',
						align: 'left',
						width: 150
					},
					{
						title: '内容',
						key: 'neirong',
						align: 'left'
					}
				]
			}
		],
		tabledata3: [
			{
				yuanyinqufeng: '机种切换',
				neirong: '因切换机种而停机的时间'
			},
			{
				yuanyinqufeng: '部品待ち',
				neirong: '待基板/待设计确认'
			},
			{
				yuanyinqufeng: '計画なし',
				neirong: '无计划STOP/来客对应准备STOP/天灾STOP/早礼'
			},
			{
				yuanyinqufeng: '前後工程待ち',
				neirong: '程(等待基板,检查机/初物检查)'
			},
			{
				yuanyinqufeng: '部品切れ',
				neirong: '资材出库的基板・部品出库错误/实装错误造成的部品不足'
			},
			{
				yuanyinqufeng: '部品段取待ち',
				neirong: '等待资材部品准备/部品组装'
			},
			{
				yuanyinqufeng: '定期点検',
				neirong: '班次,日,周,月的定期点检时间'
			},
			{
				yuanyinqufeng: 'トラブル',
				neirong: 'OP设备还原对应不了的停止(设备故障/品质关联等的设备调整,停止)'
			},
			{
				yuanyinqufeng: 'ﾁｮｺ停部品補充',
				neirong: '部品补充'
			},
			{
				yuanyinqufeng: '試作',
				neirong: 'PP/AP生产'
			}

		],

		// 表头 plan
		tablecolumns_plan: [
			{
				type: 'index',
				align: 'center',
				width: 60,
				align: 'center',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize_plan * (vm_app.pagecurrent_plan - 1)
				}
			},
			{
				title: '所属日期',
				key: 'suoshuriqi',
				align: 'center',
				width: 110,
				sortable: true,
				// fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.suoshuriqi.substring(0,10)
					]);
				}
			},
			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80,
				// fixed: 'left',
			},
			{
				title: '班次',
				key: 'banci',
				align: 'center',
				width: 70,
				// fixed: 'left',
			},
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 120,
				// fixed: 'left',
			},
			{
				title: 'SP NO',
				key: 'spno',
				align: 'center',
				width: 130,
				// fixed: 'left',
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 80,
				// fixed: 'left',
			},
			{
				title: '工序',
				key: 'gongxu',
				align: 'center',
				width: 70,
				// fixed: 'left',
			},
			{
				title: 'LOT数',
				key: 'lotshu',
				align: 'center',
				width: 80,
				// fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.lotshu.toLocaleString()
					]);
				}
			},
			{
				title: '计划产量',
				key: 'jihuachanliang',
				align: 'center',
				width: 90,
				// fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.jihuachanliang.toLocaleString()
					]);
				}
			},

		],
		tabledata_plan: [],

		// 表头 planresult
		tablecolumns_planresult: [
			{
				type: 'index',
				align: 'center',
				width: 60,
				align: 'center',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize_plan * (vm_app.pagecurrent_plan - 1)
				}
			},
			{
				title: '所属日期',
				key: 'suoshuriqi',
				align: 'center',
				width: 110,
				// fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.suoshuriqi.substring(0,10)
					]);
				}
			},
			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80,
			},
			{
				title: '班次',
				key: 'banci',
				align: 'center',
				width: 70,
			},
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 120,
			},
			{
				title: 'SP NO',
				key: 'spno',
				align: 'center',
				width: 130,
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 80,
				// fixed: 'left',
			},
			{
				title: '工序',
				key: 'gongxu',
				align: 'center',
				width: 70,
				// fixed: 'left',
			},
			{
				title: 'LOT数',
				key: 'lotshu',
				align: 'center',
				width: 80,
				// fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.lotshu.toLocaleString()
					]);
				}
			},
			{
				title: '计划产量',
				key: 'jihuachanliang',
				align: 'center',
				width: 90,
				// fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.jihuachanliang.toLocaleString()
					]);
				}
			},

		],
		tabledata_planresult: [],
		rowClassName_planresultX: -1,
		
		// 删除disabled
		boo_delete: true,

		// 更新disabled
		boo_update: true,

		// 过滤变量
		date_filter: [],//new Date(),
		date_filter_options: {
			shortcuts: [
				{
					text: '今天',
					value () {
						return [new Date(), new Date()];
					},
					// onClick: (picker) => {
					// 	this.$Message.info('Click today');
					// },
				},
				{
					text: '前 1 周',
					value () {
						const end = new Date();
						const start = new Date();
						// start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
						start.setDate(start.getDate() - 7);
						return [start, end];
					}
				},
				{
					text: '前 1 月',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 30);
						return [start, end];
					}
				},
				{
					text: '前 3 月',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 90);
						return [start, end];
					}
				},
				{
					text: '前 6 月',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 180);
						return [start, end];
					}
				},
				{
					text: '前 1 年',
					value () {
						const end = new Date();
						const start = new Date();
						// start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);
						start.setDate(start.getDate() - 365);
						return [start, end];
					}
				},
			]
		},		
		xianti_filter: '',
		banci_filter: '',
		option_banci_filter: [
			{
				value: 'A',
				label: 'A'
			},
			{
				value: 'B',
				label: 'B'
			}
		],
		jizhongming_filter: '',
		
		// 小计
		xiaoji_chajiandianshu: 0,
		xiaoji_meishu: 0,
		xiaoji_jiadonglv: 0,
		hejifen: 0,

		// 单位秒
		xiaoji_shengchanshijian: 0,
		xiaoji_langfeishijian: 0,
		xiaoji_bupinbuchongshijian: 0,

		
		//分页
		pagecurrent: 1,
		pagetotal: 0,
		pagepagesize: 5,
		pagelast: 1,

		// tabs索引
		currenttabs: 1,

		// 上传，批量导入
		file: null,
		loadingStatus: false,
		uploaddisabled: false,
		loadingStatus_table: false,
		uploaddisabled_table: false,

		// 刷新生产计划
		loadingStatus_refreshplan: false,
		uploaddisabled_refreshplan: false,

		disabled_create: false,

		//分页计划
		pagecurrent_plan: 1,
		pagetotal_plan: 0,
		pagepagesize_plan: 10,
		pagelast_plan: 1,


		// 生产计划导入过滤
		date_plan_suoshuriqi: [],
		date_plan_suoshuriqi_options: {
			shortcuts: [
				{
					text: '今天',
					value () {
						return [new Date(), new Date()];
					},
					// onClick: (picker) => {
					// 	this.$Message.info('Click today');
					// },
				},
				{
					text: '前后1天',
					value () {
						const end = new Date();
						const start = new Date();
						// start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
						start.setDate(start.getDate() - 1);
						end.setDate(end.getDate() + 1);
						return [start, end];
					}
				},
				{
					text: '前后2天',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 2);
						end.setDate(end.getDate() + 2);
						return [start, end];
					}
				},
				{
					text: '前后3天',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 3);
						end.setDate(end.getDate() + 3);
						return [start, end];
					}
				},
				{
					text: '前后5天',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 5);
						end.setDate(end.getDate() + 5);
						return [start, end];
					}
				},
			]
		},
		xianti_plan_filter: '',
		banci_plan_filter: '',

		// 统计日期过滤（月）
		tongji_date_filter: '',

		// 表头tongji
		tablecolumns_tongji: [
 			{
				title: '生产日期',
				key: 'shengchanriqi',
				align: 'center',
				width: 90,
				fixed: 'left'
			},
 			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 90,
				fixed: 'left'
				// render: (h, params) => {
				// 	return h('div', [
				// 		params.row.jianchariqi.substring(0,10)
				// 	]);
				// }
			},
 			{
				title: ' ',
				key: 'shebei',
				// align: 'center',
				width: 200,
				// render: (h, params) => {
				// 	return h('div', [
				// 		params.row.jianchariqi.substring(0,10)
				// 	]);
				// }
			},
 			{
				title: '持续时间',
				key: 'chixushijian',
				align: 'center',
				width: 90,
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, [
							h('Poptip', {
								props: {
									'word-wrap': true,
									'trigger': 'hover',
									'content': '全月的稼动天数 * 1440分/天'
								}
							}, '持续时间')
						])
					]);
				},
				render: (h, params) => {
					return h('div', [
						// params.row.chixushijian.toLocaleString()
						h('span', {
						}, [
							h('Poptip', {
								props: {
									'word-wrap': true,
									'trigger': 'hover',
									'content': '全月的稼动天数 * 1440分/天'
								}
							}, params.row.chixushijian.toLocaleString())
						])
					]);
				}
			},
 			{
				title: '实际时间',
				key: 'shijishijian',
				align: 'center',
				width: 90,
				render: (h, params) => {
					return h('div', [
						params.row.shijishijian.toLocaleString()
					]);
				}
			},
 			{
				title: '稼动率（无计划除外）',
				key: 'jiadonglv',
				align: 'center',
				width: 120,
				className: 'table-info-column',
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '稼动率'),
						h('br', {
						}, ''),
						h('span', {
						}, '（除无计划）')
					]);
				},
				render: (h, params) => {
					return h('div', [
						(params.row.jiadonglv * 100).toLocaleString() + '%'
					]);
				}
			},
 			{
				title: '实际点数',
				key: 'shijidianshu',
				align: 'center',
				width: 100,
				render: (h, params) => {
					return h('div', [
						params.row.shijidianshu.toLocaleString()
					]);
				}
			},
 			{
				title: '打点稼动率',
				key: 'dadianjiadonglv',
				align: 'center',
				width: 100,
				className: 'table-info-column',
				render: (h, params) => {
					return h('div', [
						(params.row.dadianjiadonglv * 100).toLocaleString() + '%'
					]);
				}
			},
 			{
				title: '机种切换（次数）',
				key: 'jizhongqiehuancishu',
				align: 'center',
				width: 110,
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '机种切换'),
						h('br', {
						}, ''),
						h('span', {
						}, '（次数）')
					]);
				},
				render: (h, params) => {
					return h('div', [
						params.row.jizhongqiehuancishu.toLocaleString()
					]);
				}
			},
 			{
				title: '机种切换（时间）',
				key: 'jizhongqiehuanshijian',
				align: 'center',
				width: 110,
				className: 'table-info-column-tingzhishijian',
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '机种切换'),
						h('br', {
						}, ''),
						h('span', {
						}, '（时间）')
					]);
				},
				render: (h, params) => {
					return h('div', [
						params.row.jizhongqiehuanshijian.toLocaleString()
					]);
				}
			},
 			{
				title: '机种切换（1次）',
				key: 'jizhongqiehuanyici',
				align: 'center',
				width: 110,
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '机种切换'),
						h('br', {
						}, ''),
						h('span', {
						}, '（1次）')
					]);
				},
				render: (h, params) => {
					return h('div', [
						params.row.jizhongqiehuanyici.toLocaleString()
					]);
				}
			},
 			{
				title: '等待部品',
				key: 'dengdaibupin',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.dengdaibupin.toLocaleString()
					]);
				}
			},
 			{
				title: '无计划',
				key: 'wujihua',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.wujihua.toLocaleString()
					]);
				}
			},
 			{
				title: '前后工程等待',
				key: 'qianhougongchengdengdai',
				align: 'center',
				width: 110,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.qianhougongchengdengdai.toLocaleString()
					]);
				}
			},
 			{
				title: '无部品',
				key: 'wubupin',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.wubupin.toLocaleString()
					]);
				}
			},
 			{
				title: '部品安排等待',
				key: 'bupinanpaidengdai',
				align: 'center',
				width: 110,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.bupinanpaidengdai.toLocaleString()
					]);
				}
			},
 			{
				title: '定期点检',
				key: 'dingqidianjian',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.dingqidianjian.toLocaleString()
					]);
				}
			},
 			{
				title: '故障',
				key: 'guzhang',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.guzhang.toLocaleString()
					]);
				}
			},
 			{
				title: '部品补充',
				key: 'bupinbuchong',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.bupinbuchong.toLocaleString()
					]);
				}
			},
 			{
				title: '试作',
				key: 'shizuo',
				align: 'center',
				width: 100,
				className: 'table-info-column-tingzhishijian',
				render: (h, params) => {
					return h('div', [
						params.row.shizuo.toLocaleString()
					]);
				}
			},
 			{
				title: '合计',
				key: 'heji',
				align: 'center',
				width: 100,
				className: 'table-info-column',
				render: (h, params) => {
					return h('div', [
						params.row.heji.toLocaleString()
					]);
				}
			},
		],
		tabledata_tongji: [],
		tableselect_tongji: [],

		// 表头dadianjiadonglv
		tablecolumns_dadianjiadonglv: [
 			{
				title: '生产日期',
				key: 'shengchanriqi',
				align: 'center',
				width: 90,
			},
 			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 90,
			},
 			{
				title: '稼动时间 (js)',
				key: 'jiadongshijian',
				align: 'center',
				width: 110,
				render: (h, params) => {
					return h('div', [
						params.row.jiadongshijian.toLocaleString()
					]);
				}
			},
 			{
				title: '稼动天数 (jt)',
				key: 'jiadongtianshu',
				align: 'center',
				width: 110,
				render: (h, params) => {
					return h('div', [
						params.row.jiadongtianshu.toLocaleString()
					]);
				}
			},
 			{
				title: '设备能力 (N)',
				key: 'shebeinengli',
				align: 'center',
				width: 120,
				render: (h, params) => {
					return h('div', [
						params.row.shebeinengli.toLocaleString()
					]);
				}
			},
 			{
				title: '理论打点数 (ld)',
				key: 'lilundadianshu',
				align: 'center',
				width: 120,
				render: (h, params) => {
					return h('div', [
						params.row.lilundadianshu.toLocaleString()
					]);
				}
			},
 			{
				title: '实际打点数 (sd)',
				key: 'shijidadianshu',
				align: 'center',
				width: 130,
				render: (h, params) => {
					return h('div', [
						params.row.shijidadianshu.toLocaleString()
					]);
				}
			},
 			{
				title: '打点稼动率 (v)',
				key: 'dadianjiadonglv',
				align: 'center',
				width: 120,
				className: 'table-info-column',
				render: (h, params) => {
					return h('div', [
						(params.row.dadianjiadonglv * 100).toLocaleString() + '%'
					]);
				}
			},
		],
		tabledata_dadianjiadonglv: [],
		tableselect_dadianjiadonglv: [],

		// 编辑
		modal_pdreport_edit1: false,
		modal_pdreport_edit2: false,
		shengchanriqi_edit: '',
		created_at_edit: '',
		updated_at_edit: '',

		id_edit: '',
		spno_edit: '',
		jizhongming_edit: '',
		pinming_edit: '',
		lotshu_edit: '',
		gongxu_edit: '',
		meimiao_edit: '',
		taishu_edit: '',
		shoudongshengchanshijian_edit: '',

		xinchan_edit: '',
		liangchan_edit: '',
		dengdaibupin_edit: '',
		wujihua_edit: '',
		qianhougongchengdengdai_edit: '',
		wubupin_edit: '',
		bupinanpaidengdai_edit: '',
		dingqidianjian_edit: '',
		guzhang_edit: '',
		shizuo_edit: '',
		jizaishixiang_edit1: '',
		jizaishixiang_edit2: '',
		jizaishixiang_edit3: '',
		jizaishixiang_edit4: '',
		jizaishixiang_edit5: '',
		jizaishixiang_edit6: '',
		jizaishixiang_edit7: '',
		jizaishixiang_edit8: '',
		jizaishixiang_edit9: '',
		jizaishixiang_edit: '',

		// 汇总公式下拉
		collapse_tongji: '',

		// 打点稼动率下拉
		collapse_dadianjiadonglv: '',



	},
	methods: {
		// 2.Notice 通知提醒
		info (nodesc, title, content) {
			this.$Notice.info({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		success (nodesc, title, content) {
			this.$Notice.success({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		warning (nodesc, title, content) {
			this.$Notice.warning({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		error (nodesc, title, content) {
			this.$Notice.error({
				title: title,
				desc: nodesc ? '' : content
			});
		},

		alert_logout () {
			this.error(false, '会话超时', '会话超时，请重新登录！');
			window.setTimeout(function(){
				window.location.href = "{{ route('portal') }}";
			}, 2000);
			return false;
		},
		
		// 切换当前页
		oncurrentpagechange (currentpage) {
			this.dailyreportgets(currentpage, this.pagelast);
		},

		// 切换当前页计划
		oncurrentpagechange_plan (currentpage) {
			this.pdplangets(currentpage, this.pagelast_plan);
		},

		// 把laravel返回的结果转换成select能接受的格式
		json2select (value) {
			var arr = value.split(/[\s\n]/);
			var arr_result = [];

			arr.map(function (v, i) {
				arr_result.push({ value: v, label: v });
			});

			return arr_result;
		},

		configgets () {
			var _this = this;

			var url = "{{ route('smt.configgetspdreport') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					response.data.map(function (v, i) {
						
						if (v.name == 'dandangzhe') {
							_this.option_dandangzhe = _this.json2select(v.value);
						}
						else if (v.name == 'querenzhe') {
							_this.option_querenzhe = _this.json2select(v.value);
						}
					
					});

				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},

		datepickerchange (date) {
			if (typeof(date)=='string') {
				return date;
			} else {
				return date.Format("yyyy-MM-dd");
			}
		},
		
		// 使用计划表，功能暂停使用
		load_jizhongming () {
			var _this = this;
			if (_this.jizhongming.trim() == '') {
				_this.jizhongming = '';
				return false;
			}
			
			var jizhongming = _this.jizhongming;
			
			var url = "{{ route('smt.pdreport.getjizhongming') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					jizhongming: jizhongming
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.error(false, '错误', '登录失效，请重新登录！');
					window.setTimeout(function(){
						window.location.href = "{{ route('portal') }}";
					}, 2000);
				}
				
				if (response.data) {
					var tmp_pinming = '';
					var tmp_gongxu = '';
					var boo_flag = false;
					
					_this.pinming = '';
					_this.gongxu = '';
					_this.option_pinming = [];
					_this.option_gongxu = [];
					for (var i in response.data) {
						// pinming
						tmp_pinming = response.data[i].pinming;
						
						if (_this.option_pinming.length != 0) {
							for (var j in _this.option_pinming) {
								if (_this.option_pinming[j].value == tmp_pinming) {
									boo_flag = true;
									break;
								} else {
									boo_flag = false;
								}
							}
							if (boo_flag == false) {
								_this.option_pinming.push({value: tmp_pinming, label: tmp_pinming});
							}
						} else {
							_this.option_pinming.push({value: tmp_pinming, label: tmp_pinming});
						}
						
						// gongxu
						tmp_gongxu = response.data[i].gongxu;
						
						if (_this.option_gongxu.length != 0) {
							for (var j in _this.option_gongxu) {
								if (_this.option_gongxu[j].value == tmp_gongxu) {
									boo_flag = true;
									break;
								} else {
									boo_flag = false;
								}
							}
							if (boo_flag == false) {
								_this.option_gongxu.push({value: tmp_gongxu, label: tmp_gongxu});
							}
						} else {
							_this.option_gongxu.push({value: tmp_gongxu, label: tmp_gongxu});
						}
						
					}
				}
			})
			.catch(function (error) {
				// console.log(error);
			})				
		},
		
		//
		clear () {
			var _this = this;
			_this.jizhongming = '';
			_this.spno = '';
			_this.pinming = '';
			_this.lotshu = '';
			_this.meimiao = '';
			// _this.meishu = '';
			_this.shoudongshengchanshijian = '';
			_this.taishu = '';
			_this.gongxu = '';
			_this.xinchan = '';
			_this.liangchan = '';
			_this.dengdaibupin = '';
			_this.wujihua = '';
			_this.qianhougongchengdengdai = '';
			_this.wubupin = '';
			_this.bupinanpaidengdai = '';
			_this.dingqidianjian = '';
			_this.guzhang = '';
			// _this.xinjizhongshengchanshijian = '';
			_this.shizuo = '';
			_this.jizaishixiang1 = '';
			_this.jizaishixiang2 = '';
			_this.jizaishixiang3 = '';
			_this.jizaishixiang4 = '';
			_this.jizaishixiang5 = '';
			_this.jizaishixiang6 = '';
			_this.jizaishixiang7 = '';
			_this.jizaishixiang8 = '';
			_this.jizaishixiang9 = '';
			_this.jizaishixiang = '';
			// _this.$refs.planresult.clearCurrentRow();
			_this.rowClassName_planresultX = -1;
			_this.rowClassName_planresult();

		},
		
		// create
		create () {
			var _this = this;

			_this.disabled_create = true;
			
			var shengchanriqi = _this.shengchanriqi;
			var xianti = _this.xianti;
			var banci = _this.banci;
			var jizhongming = _this.jizhongming;
			var spno = _this.spno;
			var pinming = _this.pinming;
			var lotshu = _this.lotshu;
			var gongxu = _this.gongxu;
			var meimiao = _this.meimiao;
			var taishu = _this.taishu;
			var shoudongshengchanshijian = _this.shoudongshengchanshijian;

			var xinchan = _this.xinchan;
			var liangchan = _this.liangchan;

			// var qiehuancishu = 0;
			// if (xinchan!=='' && xinchan!==undefined && xinchan!==null) {qiehuancishu++;}
			// if (liangchan!=='' && liangchan!==undefined && liangchan!==null) {qiehuancishu++;}

			var dengdaibupin = _this.dengdaibupin;
			var wujihua = _this.wujihua;
			var qianhougongchengdengdai = _this.qianhougongchengdengdai;
			var wubupin = _this.wubupin;
			var bupinanpaidengdai = _this.bupinanpaidengdai;
			var dingqidianjian = _this.dingqidianjian;
			var guzhang = _this.guzhang;
			// var xinjizhongshengchanshijian = _this.xinjizhongshengchanshijian;
			var shizuo = _this.shizuo;
			var jizaishixiang1 = _this.jizaishixiang1;
			var jizaishixiang2 = _this.jizaishixiang2;
			var jizaishixiang3 = _this.jizaishixiang3;
			var jizaishixiang4 = _this.jizaishixiang4;
			var jizaishixiang5 = _this.jizaishixiang5;
			var jizaishixiang6 = _this.jizaishixiang6;
			var jizaishixiang7 = _this.jizaishixiang7;
			var jizaishixiang8 = _this.jizaishixiang8;
			var jizaishixiang9 = _this.jizaishixiang9;
			var jizaishixiang = _this.jizaishixiang;

			if (shengchanriqi == '' || xianti == '' || banci == ''
				|| shengchanriqi == undefined || xianti == undefined || banci == undefined) {
				_this.warning(false, '警告', '必填项输入内容为空或不正确！');
				_this.disabled_create = false;
				return false;
			}

			// 正则判断spno（10位数字 + 横杠 + 一位或两位数字）
			if (spno != undefined && spno != '' && spno != null) {
				// var pattern = /^\d{10}-\d{1,2}$/;
				var pattern = /^\d{10}-[0-9a-zA-Z]{1,2}$/;
				// console.log(pattern.test(spno));
				// return false;
				if (! pattern.test(spno)) {
					_this.warning(false, '警告', 'SP NO. 输入不正确！');
					_this.disabled_create = false;
					return false;
				}
			}

			var url = "{{ route('smt.pdreport.dailyreportcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				shengchanriqi: shengchanriqi.Format("yyyy-MM-dd 00:00:00"),
				xianti: xianti,
				banci: banci,
				jizhongming: jizhongming,
				spno: spno,
				pinming: pinming,
				lotshu: lotshu,
				gongxu: gongxu,
				meimiao: meimiao,
				taishu: taishu,
				shoudongshengchanshijian: shoudongshengchanshijian,
				xinchan: xinchan,
				liangchan: liangchan,
				// qiehuancishu: qiehuancishu,
				dengdaibupin: dengdaibupin,
				wujihua: wujihua,
				qianhougongchengdengdai: qianhougongchengdengdai,
				wubupin: wubupin,
				bupinanpaidengdai: bupinanpaidengdai,
				dingqidianjian: dingqidianjian,
				guzhang: guzhang,
				// xinjizhongshengchanshijian: xinjizhongshengchanshijian,
				shizuo: shizuo,
				jizaishixiang1: jizaishixiang1,
				jizaishixiang2: jizaishixiang2,
				jizaishixiang3: jizaishixiang3,
				jizaishixiang4: jizaishixiang4,
				jizaishixiang5: jizaishixiang5,
				jizaishixiang6: jizaishixiang6,
				jizaishixiang7: jizaishixiang7,
				jizaishixiang8: jizaishixiang8,
				jizaishixiang9: jizaishixiang9,
				jizaishixiang: jizaishixiang,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					_this.clear();
					_this.success(false, '成功', '记入成功！');
					// _this.dailyreportgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, '失败', '请确认MPoint表中机种信息是否存在或正确！');
				}
				
				window.setTimeout(function () {
					_this.disabled_create = false;
				}, 500);
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				window.setTimeout(function () {
					_this.disabled_create = false;
				}, 1000);
			})
		},
		
		//
		onselectchange (selection) {
			var _this = this;
			_this.tableselect = [];

			for (var i in selection) {
				_this.tableselect.push(selection[i].id);
			}
			
			_this.boo_delete = _this.tableselect[0] == undefined ? true : false;

			// 担当者
			_this.disabled_dandangzhe = _this.tableselect[0] == undefined ? true : false;
			
			// 确认者
			_this.disabled_querenzhe = _this.tableselect[0] == undefined ? true : false;
		},

		//
		onselectchange_planresult (selection, index) {
			var _this = this;

			_this.rowClassName_planresultX = index;
			_this.rowClassName_planresult();

			// console.log(selection);
			// return false;

			_this.jizhongming = selection.jizhongming.substr(0, 8);
			_this.spno = selection.spno;
			_this.pinming = selection.pinming;
			_this.lotshu = selection.lotshu;
			_this.gongxu = selection.gongxu;

			// _this.$refs.ref_meimiao.focus();
			document.getElementById('id_meimiao').focus();
			
		},

		// 选择行的颜色
		rowClassName_planresult (row, index) {
			return this.rowClassName_planresultX == index ? 'table-info-row' : '';
		},
		
		//
		ondelete (selection) {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) {
				return false;
			}

			var url = "{{ route('smt.pdreport.dailyreportdelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect: tableselect
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.tableselect = [];
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},

		// dailyreport列表
		dailyreportgets (page, last_page){
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}

			var date_filter = [];
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;

			if (_this.date_filter[0] == '' || _this.date_filter == undefined) {
				_this.tabledata1 = [];
				_this.tabledata2 = [];
				_this.pagecurrent = 1;
				_this.pagetotal = 0;
				_this.pagelast = 1
				
				_this.warning(false, '警告', '请先选择日期范围！');
				return false;
			} else {
				date_filter = [_this.date_filter[0].Format("yyyy-MM-dd 00:00:00"), _this.date_filter[1].Format("yyyy-MM-dd 23:59:59")];
			}

			var url = "{{ route('smt.pdreport.dailyreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize,
					page: page,
					date_filter: date_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
				}
			})
			.then(function (response) {
				// console.log(response.data.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {

					_this.pagecurrent = response.data.paginate.current_page;
					_this.pagetotal = response.data.paginate.total;
					_this.pagelast = response.data.paginate.last_page
					
					_this.tabledata1 = response.data.paginate.data;
					_this.tabledata2 = response.data.paginate.data;

					var tabledata_total = response.data.total;
					
					// 合计 暂未用于显示
					// _this.xiaoji_chajiandianshu = 0;
					// _this.xiaoji_jiadonglv = 0;

					// for (var i in _this.tabledata1) {
					// 	_this.xiaoji_chajiandianshu += _this.tabledata1[i].chajiandianshu;
					// 	_this.xiaoji_jiadonglv += _this.tabledata1[i].jiadonglv;
					// }
					// _this.hejifen = 720 * _this.xiaoji_jiadonglv;

					// 计算 部品补充时间 = 一个班次12小时 - 机种生产时间 - 浪费时间
					// console.log(date_filter);
					if (date_filter[0].substring(0,10) == date_filter[1].substring(0,10)
						&& xianti_filter != '' && xianti_filter != undefined
						// && banci_filter != '' && banci_filter != undefined) {
						) {

						_this.xiaoji_shengchanshijian = 0;
						_this.xiaoji_langfeishijian = 0;
						_this.xiaoji_chajiandianshu = 0;
						_this.xiaoji_meishu = 0;

						// console.log(tabledata_total);
						tabledata_total.map(function (v, i) {
							_this.xiaoji_shengchanshijian += v.meimiao * v.meishu;
							_this.xiaoji_langfeishijian += 60 * (v.xinchan + v.liangchan + v.dengdaibupin + v.wujihua + v.qianhougongchengdengdai + v.wubupin + v.bupinanpaidengdai + v.dingqidianjian + v.guzhang + v.shizuo);
							_this.xiaoji_chajiandianshu += v.chajiandianshu;
							_this.xiaoji_meishu += v.meishu;
						});

						let xiaoji_bupinbuchongshijian = 0;

						if (banci_filter) {
							xiaoji_bupinbuchongshijian = (12*60*60 - _this.xiaoji_shengchanshijian - _this.xiaoji_langfeishijian);
						} else{
							xiaoji_bupinbuchongshijian = (24*60*60 - _this.xiaoji_shengchanshijian - _this.xiaoji_langfeishijian);
						}
						_this.xiaoji_bupinbuchongshijian = xiaoji_bupinbuchongshijian;

						if (_this.xiaoji_bupinbuchongshijian == 43200) {
							_this.xiaoji_bupinbuchongshijian = 0;
						}

						// console.log('生产时间：' + _this.xiaoji_shengchanshijian);
						// console.log('浪费时间：' + _this.xiaoji_langfeishijian);
						// console.log('部品补充时间：' + _this.xiaoji_bupinbuchongshijian);
					} else {
						_this.xiaoji_shengchanshijian = 0;
						_this.xiaoji_langfeishijian = 0;
						_this.xiaoji_bupinbuchongshijian = 0;
						_this.xiaoji_chajiandianshu = 0;
						_this.xiaoji_meishu = 0;
					}

				} else {
					_this.tabledata1 = [];
					_this.tabledata2 = [];
				}
				
				// 恢复禁用状态
				_this.boo_delete = true;
				_this.disabled_dandangzhe = true;
				_this.disabled_querenzhe = true;
				_this.select_dandangzhe = '';
				_this.select_querenzhe = '';
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
			})
		},
		
		// 担当者变更
		dandangzhechange: function (value) {
			if (value == undefined) return false;
			var _this = this;
			var tableselect = _this.tableselect;
			// console.log(tableselect);return false;
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('smt.pdreport.dandangzhechange') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: tableselect,
				dandangzhe : value
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
					
					// _this.boo_delete = true;
					// _this.disabled_dandangzhe = true;
					// _this.disabled_querenzhe = true;
					// _this.select_dandangzhe = '';
				} else {
					_this.error(false, '失败', '更新失败！');
				}
			})
			.catch(function (error) {
				// console.log(error);
				_this.error(false, '错误', '更新失败！');
			})			
		},

		// 确认者变更
		querenzhechange (value) {
			if (value == undefined ) return false;
			var _this = this;
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('smt.pdreport.querenzhechange') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: tableselect,
				querenzhe: value
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
					
					// _this.boo_delete = true;
					// _this.disabled_dandangzhe = true;
					// _this.disabled_querenzhe = true;
					// _this.select_querenzhe = '';
				} else {
					_this.error(false, '失败', '更新失败！');
				}
			})
			.catch(function (error) {
				// console.log(error);
				_this.error(false, '错误', '更新失败！');
			})			
		},
		
		
		// 结果表数据导出
		exportData_pdreport () {
			var _this = this;
			
			if (_this.date_filter[0] == '' || _this.date_filter[0] == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var queryfilter_datefrom = _this.date_filter[0].Format("yyyy-MM-dd 00:00:00");
			var queryfilter_dateto = _this.date_filter[1].Format("yyyy-MM-dd 23:59:59");
			
			var url = "{{ route('smt.pdreport.pdreportexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto;
				
			// console.log(url);
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},
		

		// upload function
		handleFormatError (file) {
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is incorrect, please select <strong>xls</strong> or <strong>xlsx</strong>.'
			});
		},
		handleMaxSize (file) {
			this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File  ' + file.name + ' is too large, no more than <strong>2M</strong>.'
			});
		},
		handleUpload: function (file) {
			this.file = file;
			return false;
		},
		uploadstart_plan: function (file) {
			var _this = this;
			_this.file = file;
			_this.uploaddisabled = true;
			_this.loadingStatus = true;

			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',_this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('smt.pdreport.pdplanimport') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
			axios({
				url: url,
				method: 'post',
				data: formData,
				processData: false,// 告诉axios不要去处理发送的数据(重要参数)
				contentType: false, // 告诉axios不要去设置Content-Type请求头
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '导入成功！');
				} else {
					_this.error(false, '失败', '注意内容文本格式并且内容不能为空！');
				}
				
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
				
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
			})
		},
		uploadcancel: function () {
			this.file = null;
			// this.loadingStatus = false;
		},

		// 计划上传模板下载
		download_plan: function () {
			var url = "{{ route('smt.pdreport.pdplandownload') }}";
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},

		uploadstart_plan_table (file) {
			var _this = this;
			_this.file = file;
			_this.uploaddisabled_table = true;
			_this.loadingStatus_table = true;

			let formData = new FormData()
			formData.append('myfile',_this.file)
			
			var url = "{{ route('smt.pdreport.pdplantableupload') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
			axios({
				url: url,
				method: 'post',
				data: formData,
				processData: false,// 告诉axios不要去处理发送的数据(重要参数)
				contentType: false, // 告诉axios不要去设置Content-Type请求头
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '上传成功！');
				} else {
					_this.error(false, '失败', '上传失败，请重试！');
				}
				
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus_table = false;
					_this.uploaddisabled_table = false;
				}, 1500);
				
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus_table = false;
					_this.uploaddisabled_table = false;
				}, 1500);
			})
		},
		uploadcancel () {
			this.file = null;
			// this.loadingStatus = false;
		},

		// 计划表文件下载
		download_plan_table () {
			var url = "{{ route('smt.pdreport.pdplantabledownload') }}";
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},

		// 刷新生产计划
		refreshplan () {
			var _this = this;

			_this.uploaddisabled_refreshplan = true;
			_this.loadingStatus_refreshplan = true;
			
			var url = "{{ route('smt.pdreport.pdplandrefresh') }}";
			axios.get(url,{
				params: {
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '刷新成功！');
				} else {
					_this.error(false, '失败', '刷新失败！');
				}
				
				setTimeout( function () {
					_this.loadingStatus_refreshplan = false;
					_this.uploaddisabled_refreshplan = false;
				}, 500);
				
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
				setTimeout( function () {
					_this.loadingStatus_refreshplan = false;
					_this.uploaddisabled_refreshplan = false;
				}, 1000);
			})
		},

		// 清空生产计划
		truncateplan () {
			var _this = this;

			var url = "{{ route('smt.pdreport.pdplandtruncate') }}";
			axios.get(url,{
				params: {
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '清空成功！');
				} else {
					_this.error(false, '失败', '清空失败！');
				}
				
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
			})
		},

		// plan列表
		pdplangets (page, last_page) {
			var _this = this;

			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var date_filter = [];

			if (_this.date_plan_suoshuriqi[0] == '' || _this.date_plan_suoshuriqi == undefined) {
				_this.tabledata_plan = [];
				_this.pagecurrent_plan = 1;
				_this.pagetotal_plan = 0;
				_this.pagelast_plan = 1;

				_this.warning(false, '警告', '请先选择日期范围！');
				return false;
			} else {
				date_filter =  _this.date_plan_suoshuriqi;
			}
			// console.log(date_filter);return false;
			date_filter = [date_filter[0].Format("yyyy-MM-dd 00:00:00"), date_filter[1].Format("yyyy-MM-dd 23:59:59")];

			var xianti_filter = _this.xianti_plan_filter;
			var banci_filter = _this.banci_plan_filter;

			var url = "{{ route('smt.pdreport.pdplangets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize_plan,
					page: page,
					date_filter: date_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					// jizhongming_filter: jizhongming_filter,
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {

					_this.pagecurrent_plan = response.data.current_page;
					_this.pagetotal_plan = response.data.total;
					_this.pagelast_plan = response.data.last_page
					
					_this.tabledata_plan = response.data.data;

				} else {
					_this.tabledata_plan = [];
				}
				
				
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				_this.tabledata_plan = [];
			})
		},


		// planresult列表
		pdplanresultgets (page, last_page) {
			var _this = this;

			var date_filter = _this.jihuariqi;
			var xianti_filter = _this.xianti;
			var banci_filter = _this.banci;

			if (date_filter == '' || date_filter == undefined
				|| xianti_filter == '' || xianti_filter == undefined
				|| banci_filter == '' || banci_filter == undefined) {
				_this.tabledata_planresult = [];
				// _this.warning(false, '警告', '请先选择日期范围！');
				return false;
			}

			date_filter = [date_filter.Format("yyyy-MM-dd 00:00:00"), date_filter.Format("yyyy-MM-dd 23:59:59")];
			// console.log(date_filter);return false;

			var url = "{{ route('smt.pdreport.pdplanresultgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagepagesize_plan,
					// page: page,
					date_filter: date_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					// jizhongming_filter: jizhongming_filter,
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.tabledata_planresult = response.data;
				} else {
					_this.tabledata_plan = [];
				}
				
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				_this.tabledata_plan = [];
			})
		},


		// 统计列表查询
		tongjigets (month) {
			var _this = this;

			if (month == undefined || month == '') {
				_this.tabledata_tongji = [];
				_this.tabledata_dadianjiadonglv = [];
				return false;
			}

			var myyear = month.Format("yyyy");
			var mymonth = month.Format("MM");

			// var days = new Date(myyear, mymonth, 0);
			// var mydays = days.getDate();

			var tongji_date_filter = [month.Format("yyyy-MM-01 00:00:00"), month.Format("yyyy-MM-31 23:59:59")];
			// console.log(mydays);return false;

			var url = "{{ route('smt.pdreport.tongjigets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					tongji_date_filter: tongji_date_filter,
				}
			})
			.then(function (response) {
				// console.log(response.data);return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var tongji = response.data.tongji;
					var shebeinengli = response.data.shebeinengli;
					var shebei = response.data.shebei;
					var shengchantianshu = response.data.shengchantianshu;
					// var chixushijian = 1440 * mydays;
					var chixushijian = 1440 * shengchantianshu;

					var res = [
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-1', 'shebei': shebei.shebei_smt1,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-2', 'shebei': shebei.shebei_smt2,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-3', 'shebei': shebei.shebei_smt3,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-4', 'shebei': shebei.shebei_smt4,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-5', 'shebei': shebei.shebei_smt5,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-6', 'shebei': shebei.shebei_smt6,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-7', 'shebei': shebei.shebei_smt7,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-8', 'shebei': shebei.shebei_smt8,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-9', 'shebei': shebei.shebei_smt9,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': month.Format("yyyy-MM"), 'xianti': 'SMT-10', 'shebei': shebei.shebei_smt10,
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
						{
							'shengchanriqi': '合计', 'xianti': '', 'shebei': '',
							'chixushijian': 0, 'shijishijian': 0, 'jiadonglv': 0,
							'shijidianshu': 0, 'dadianjiadonglv': 0,
							'jizhongqiehuancishu': 0, 'jizhongqiehuanshijian': 0,  'jizhongqiehuanyici': 0,
							'dengdaibupin': 0, 'wujihua': 0, 'qianhougongchengdengdai': 0,
							'wubupin': 0, 'bupinanpaidengdai': 0, 'dingqidianjian': 0,
							'guzhang': 0, 'bupinbuchong': 0, 'shizuo': 0, 'heji': 0,
						},
					];

					var dadianjiadonglv = [
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt1, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt2, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt3, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt4, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt5, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt6, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt7, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt8, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt9, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
						{'shengchanriqi': month.Format("yyyy-MM"), 'xianti': '', 'jiadongshijian': 0, 'jiadongtianshu': 0, 'shebeinengli': shebeinengli.shebeinengli_smt10, 'lilundadianshu': 0, 'shijidadianshu': 0, 'dadianjiadonglv': 0,},
					];
					// console.log(dadianjiadonglv);return false;

					tongji.map(function (v,k) {

						// 按线体区分统计
						switch(v.xianti.trim())
						{
							case 'SMT-1':
								i = 0;
								break;
							case 'SMT-2':
								i = 1;
								break;
							case 'SMT-3':
								i = 2;
								break;
							case 'SMT-4':
								i = 3;
								break;
							case 'SMT-5':
								i = 4;
								break;
							case 'SMT-6':
								i = 5;
								break;
							case 'SMT-7':
								i = 6;
								break;
							case 'SMT-8':
								i = 7;
								break;
							case 'SMT-9':
								i = 8;
								break;
							case 'SMT-10':
								i = 9;
								break;
							default:
						}

						// 生产时间
						res[i].shijishijian = parseInt(v.shijishijian) || 0;
						res[i].shijidianshu = parseInt(v.shijidianshu) || 0;

						// 机种切换
						res[i].jizhongqiehuanshijian = parseInt(v.jizhongqiehuanshijian) || 0;
						res[i].jizhongqiehuancishu = parseInt(v.jizhongqiehuancishu) || 0;
						res[i].jizhongqiehuanyici = parseInt(v.jizhongqiehuanyici) || 0;
						
						// 停止时间
						res[i].dengdaibupin = parseInt(v.dengdaibupin) || 0;
						res[i].wujihua = parseInt(v.wujihua) || 0;
						res[i].qianhougongchengdengdai = parseInt(v.qianhougongchengdengdai) || 0;
						res[i].wubupin = parseInt(v.wubupin) || 0;
						res[i].bupinanpaidengdai = parseInt(v.bupinanpaidengdai) || 0;
						res[i].dingqidianjian = parseInt(v.dingqidianjian) || 0;
						res[i].guzhang = parseInt(v.guzhang) || 0;
						res[i].shizuo = parseInt(v.shizuo) || 0;

						// 部品补充
						res[i].bupinbuchong = parseInt(v.bupinbuchong) || 0;

						// 稼动率
						res[i].jiadonglv = res[i].shijishijian != 0 ? (res[i].shijishijian / (chixushijian - res[i].wujihua)).toFixed(2) : 0;

						// 合计
						res[i].heji = res[i].jizhongqiehuanshijian + res[i].dengdaibupin + res[i].wujihua + res[i].qianhougongchengdengdai + res[i].wubupin + res[i].bupinanpaidengdai + res[i].dingqidianjian + res[i].guzhang + res[i].shizuo + res[i].bupinbuchong;

					});

					for (var i=0;i<10;i++) {
						// 持续时间
						res[i].chixushijian = res[i].shijishijian !=0 ? chixushijian : 0;
						res[10].chixushijian += res[i].chixushijian;
						// 实际时间
						res[10].shijishijian += res[i].shijishijian;
						// 实际点数
						res[10].shijidianshu += res[i].shijidianshu;
						// 机种切换
						res[10].jizhongqiehuanshijian += res[i].jizhongqiehuanshijian;
						res[10].jizhongqiehuancishu += res[i].jizhongqiehuancishu;

						// 停止时间
						res[10].dengdaibupin += res[i].dengdaibupin;
						res[10].wujihua += res[i].wujihua;
						res[10].qianhougongchengdengdai += res[i].qianhougongchengdengdai;
						res[10].wubupin += res[i].wubupin;
						res[10].bupinanpaidengdai += res[i].bupinanpaidengdai;
						res[10].dingqidianjian += res[i].dingqidianjian;
						res[10].guzhang += res[i].guzhang;
						res[10].shizuo += res[i].shizuo;
						res[10].bupinbuchong += res[i].bupinbuchong;

					}

					// 稼动率
					res[10].jiadonglv = (res[10].shijishijian / res[10].chixushijian).toFixed(2);
					// 机种切换1次
					res[10].jizhongqiehuanyici = res[10].jizhongqiehuancishu != 0 ? Math.round(res[10].jizhongqiehuanshijian / res[10].jizhongqiehuancishu) : 0;

					// 停止时间合计
					res[10].heji = res[10].jizhongqiehuanshijian
						+ res[10].dengdaibupin + res[10].wujihua + res[10].qianhougongchengdengdai
						+ res[10].wubupin + res[10].bupinanpaidengdai + res[10].dingqidianjian
						+ res[10].guzhang + res[10].shizuo + res[10].bupinbuchong;


					_this.tabledata_tongji = res;


					// 打点稼动率
					for (i=0;i<10;i++) {
						// 线体
						dadianjiadonglv[i].xianti = res[i].xianti;
						// 稼动时间
						dadianjiadonglv[i].jiadongshijian = res[i].chixushijian - res[i].wujihua - res[i].shizuo - res[i].dingqidianjian;
						
						if (dadianjiadonglv[i].jiadongshijian != 0) {
							// 稼动天数
							dadianjiadonglv[i].jiadongtianshu = (dadianjiadonglv[i].jiadongshijian/1440).toFixed(2);
							// 设备能力
							// dadianjiadonglv[i].shebeinengli = 
							
							// 理论打点数
							dadianjiadonglv[i].lilundadianshu = dadianjiadonglv[i].shebeinengli != 0 ? Math.round(dadianjiadonglv[i].shebeinengli * dadianjiadonglv[i].jiadongtianshu) : 0;
							// 实际打点数
							dadianjiadonglv[i].shijidadianshu = res[i].shijidianshu;
							// 打点稼动率
							dadianjiadonglv[i].dadianjiadonglv = dadianjiadonglv[i].lilundadianshu != 0 ? (dadianjiadonglv[i].shijidadianshu / dadianjiadonglv[i].lilundadianshu / 1000).toFixed(2) : 0;
							res[i].dadianjiadonglv = dadianjiadonglv[i].dadianjiadonglv;
						}


					}

					_this.tabledata_dadianjiadonglv = dadianjiadonglv;

				} else {
					_this.tabledata_tongji = [];
					_this.tabledata_dadianjiadonglv = [];
				}
				
			})
			.catch(function (error) {
			})
		},


		exportData_tongji () {
			var _this = this;

			var tabledata = this.tabledata_tongji;

			_this.$refs.table_tongji.exportCsv({
				filename: '生产月报汇总' + new Date().Format("yyyyMMddhhmmss"),
				columns: this.tablecolumns_tongji,
				data: this.tabledata_tongji,

			});
		},


		// 编辑前查看
		pdreport_edit1 (row) {
			var _this = this;

			_this.shengchanriqi_edit = row.shengchanriqi.substr(0, 10);
			_this.created_at_edit = row.created_at;
			_this.updated_at_edit = row.updated_at;

			_this.id_edit = row.id;
			_this.spno_edit = row.spno;
			_this.jizhongming_edit = row.jizhongming;
			_this.pinming_edit = row.pinming;
			_this.lotshu_edit = row.lotshu;
			_this.gongxu_edit = row.gongxu;

			_this.meimiao_edit = row.meimiao;
			_this.taishu_edit = row.taishu;
			_this.shoudongshengchanshijian_edit = row.shoudongshengchanshijian;
			
			_this.modal_pdreport_edit1 = true;
		},

		// 主编辑后保存
		pdreport_edit_ok1 () {
			var _this = this;
			
			var created_at = _this.created_at_edit;
			var updated_at = _this.updated_at_edit;
			var id = _this.id_edit;
			var spno = _this.spno_edit;
			var jizhongming = _this.jizhongming_edit;
			var pinming = _this.pinming_edit;
			var lotshu = _this.lotshu_edit;
			var gongxu = _this.gongxu_edit;
			var meimiao = _this.meimiao_edit;
			var taishu = _this.taishu_edit;
			var shoudongshengchanshijian = _this.shoudongshengchanshijian_edit;
			
			var url = "{{ route('smt.pdreport.dailyreportupdate1') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				created_at: created_at,
				updated_at: updated_at,
				id: id,
				spno: spno,
				jizhongming: jizhongming,
				pinming: pinming,
				lotshu: lotshu,
				gongxu: gongxu,
				meimiao: meimiao,
				taishu: taishu,
				shoudongshengchanshijian: shoudongshengchanshijian
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');

					_this.created_at_edit = '';
					_this.updated_at_edit = '';
					_this.id_edit = '';
					_this.spno_edit = '';
					_this.jizhongming_edit = '';
					_this.pinming_edit = '';
					_this.lotshu_edit = '';
					_this.gongxu_edit = '';
					_this.meimiao_edit = '';
					_this.taishu_edit = '';
					_this.shoudongshengchanshijian_edit = '';

				} else {
					_this.error(false, '失败', '更新失败！请确认录入是否正确！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},

		// 子编辑前查看
		pdreport_edit2 (row) {
			var _this = this;
			
			_this.shengchanriqi_edit = row.shengchanriqi.substr(0, 10);
			_this.created_at_edit = row.created_at;
			_this.updated_at_edit = row.updated_at;

			_this.id_edit = row.id;
			_this.spno_edit = row.spno;
			_this.jizhongming_edit = row.jizhongming;
			_this.pinming_edit = row.pinming;
			_this.gongxu_edit = row.gongxu;

			_this.xinchan_edit = row.xinchan;
			_this.liangchan_edit = row.liangchan;
			_this.dengdaibupin_edit = row.dengdaibupin;
			_this.wujihua_edit = row.wujihua;
			_this.qianhougongchengdengdai_edit = row.qianhougongchengdengdai;
			_this.wubupin_edit = row.wubupin;
			_this.bupinanpaidengdai_edit = row.bupinanpaidengdai;
			_this.dingqidianjian_edit = row.dingqidianjian;
			_this.guzhang_edit = row.guzhang;
			_this.shizuo_edit = row.shizuo;
			_this.jizaishixiang_edit1 = row.jizaishixiang1;
			_this.jizaishixiang_edit2 = row.jizaishixiang2;
			_this.jizaishixiang_edit3 = row.jizaishixiang3;
			_this.jizaishixiang_edit4 = row.jizaishixiang4;
			_this.jizaishixiang_edit5 = row.jizaishixiang5;
			_this.jizaishixiang_edit6 = row.jizaishixiang6;
			_this.jizaishixiang_edit7 = row.jizaishixiang7;
			_this.jizaishixiang_edit8 = row.jizaishixiang8;
			_this.jizaishixiang_edit9 = row.jizaishixiang9;
			_this.jizaishixiang_edit = row.jizaishixiang;

			_this.modal_pdreport_edit2 = true;
		},

		// 子编辑后保存
		pdreport_edit_ok2 () {
			var _this = this;
			
			var created_at = _this.created_at_edit;
			var updated_at = _this.updated_at_edit;
			var id = _this.id_edit;
			var spno = _this.spno_edit;
			var jizhongming = _this.jizhongming_edit;
			var pinming = _this.pinming_edit;
			var gongxu = _this.gongxu_edit;

			var xinchan = _this.xinchan_edit;
			var liangchan = _this.liangchan_edit;
			var dengdaibupin = _this.dengdaibupin_edit;
			var wujihua = _this.wujihua_edit;
			var qianhougongchengdengdai = _this.qianhougongchengdengdai_edit;
			var wubupin = _this.wubupin_edit;
			var bupinanpaidengdai = _this.bupinanpaidengdai_edit;
			var dingqidianjian = _this.dingqidianjian_edit;
			var guzhang = _this.guzhang_edit;
			var shizuo = _this.shizuo_edit;
			var jizaishixiang = _this.jizaishixiang_edit;
			var jizaishixiang1 = _this.jizaishixiang_edit1;
			var jizaishixiang2 = _this.jizaishixiang_edit2;
			var jizaishixiang3 = _this.jizaishixiang_edit3;
			var jizaishixiang4 = _this.jizaishixiang_edit4;
			var jizaishixiang5 = _this.jizaishixiang_edit5;
			var jizaishixiang6 = _this.jizaishixiang_edit6;
			var jizaishixiang7 = _this.jizaishixiang_edit7;
			var jizaishixiang8 = _this.jizaishixiang_edit8;
			var jizaishixiang9 = _this.jizaishixiang_edit9;

			var url = "{{ route('smt.pdreport.dailyreportupdate2') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				created_at: created_at,
				updated_at: updated_at,
				id: id,
				xinchan: xinchan,
				liangchan: liangchan,
				dengdaibupin: dengdaibupin,
				wujihua: wujihua,
				qianhougongchengdengdai: qianhougongchengdengdai,
				wubupin: wubupin,
				bupinanpaidengdai: bupinanpaidengdai,
				dingqidianjian: dingqidianjian,
				guzhang: guzhang,
				shizuo: shizuo,
				jizaishixiang1: jizaishixiang1,
				jizaishixiang2: jizaishixiang2,
				jizaishixiang3: jizaishixiang3,
				jizaishixiang4: jizaishixiang4,
				jizaishixiang5: jizaishixiang5,
				jizaishixiang6: jizaishixiang6,
				jizaishixiang7: jizaishixiang7,
				jizaishixiang8: jizaishixiang8,
				jizaishixiang9: jizaishixiang9,
				jizaishixiang: jizaishixiang
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');

					_this.created_at_edit = '';
					_this.updated_at_edit = '';

					_this.id_edit = '';
					_this.spno_edit = '';
					_this.jizhongming_edit = '';
					_this.pinming_edit = '';
					_this.gongxu_edit = '';

					_this.xinchan_edit = '';
					_this.liangchan_edit = '';
					_this.dengdaibupin_edit = '';
					_this.wujihua_edit = '';
					_this.qianhougongchengdengdai_edit = '';
					_this.wubupin_edit = '';
					_this.bupinanpaidengdai_edit = '';
					_this.dingqidianjian_edit = '';
					_this.guzhang_edit = '';
					_this.shizuo_edit = '';
					_this.jizaishixiang_edit1 = '';
					_this.jizaishixiang_edit2 = '';
					_this.jizaishixiang_edit3 = '';
					_this.jizaishixiang_edit4 = '';
					_this.jizaishixiang_edit5 = '';
					_this.jizaishixiang_edit6 = '';
					_this.jizaishixiang_edit7 = '';
					_this.jizaishixiang_edit8 = '';
					_this.jizaishixiang_edit9 = '';
					_this.jizaishixiang_edit = '';
					
				} else {
					_this.error(false, '失败', '更新失败！请确认录入是否正确！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},

		
	},
	mounted () {
		@hasanyrole('role_smt_pdreport_write|role_super_admin')
		// 设置input背景色
		var id_meimiao = document.getElementById("id_meimiao");
		id_meimiao.style.background = "#E1F5FE";

		var id_taishu = document.getElementById("id_taishu");
		id_taishu.style.background = "#E1F5FE";

		var id_shoudongshengchanshijian = document.getElementById("id_shoudongshengchanshijian");
		id_shoudongshengchanshijian.style.background = "#E1F5FE";

		var id_xinchan = document.getElementById("id_xinchan");
		id_xinchan.style.background = "#E1F5FE";

		var id_liangchan = document.getElementById("id_liangchan");
		id_liangchan.style.background = "#E1F5FE";

		var id_dengdaibupin = document.getElementById("id_dengdaibupin");
		id_dengdaibupin.style.background = "#E1F5FE";

		var id_wujihua = document.getElementById("id_wujihua");
		id_wujihua.style.background = "#E1F5FE";

		var id_qianhougongchengdengdai = document.getElementById("id_qianhougongchengdengdai");
		id_qianhougongchengdengdai.style.background = "#E1F5FE";

		var id_wubupin = document.getElementById("id_wubupin");
		id_wubupin.style.background = "#E1F5FE";

		var id_bupinanpaidengdai = document.getElementById("id_bupinanpaidengdai");
		id_bupinanpaidengdai.style.background = "#E1F5FE";

		var id_dingqidianjian = document.getElementById("id_dingqidianjian");
		id_dingqidianjian.style.background = "#E1F5FE";

		var id_guzhang = document.getElementById("id_guzhang");
		id_guzhang.style.background = "#E1F5FE";

		var id_shizuo = document.getElementById("id_shizuo");
		id_shizuo.style.background = "#E1F5FE";

		var id_jizaishixiang1 = document.getElementById("id_jizaishixiang1");
		id_jizaishixiang1.style.background = "#E1F5FE";
		var id_jizaishixiang2 = document.getElementById("id_jizaishixiang2");
		id_jizaishixiang2.style.background = "#E1F5FE";
		var id_jizaishixiang3 = document.getElementById("id_jizaishixiang3");
		id_jizaishixiang3.style.background = "#E1F5FE";
		var id_jizaishixiang4 = document.getElementById("id_jizaishixiang4");
		id_jizaishixiang4.style.background = "#E1F5FE";
		var id_jizaishixiang5 = document.getElementById("id_jizaishixiang5");
		id_jizaishixiang5.style.background = "#E1F5FE";
		var id_jizaishixiang6 = document.getElementById("id_jizaishixiang6");
		id_jizaishixiang6.style.background = "#E1F5FE";
		var id_jizaishixiang7 = document.getElementById("id_jizaishixiang7");
		id_jizaishixiang7.style.background = "#E1F5FE";
		var id_jizaishixiang8 = document.getElementById("id_jizaishixiang8");
		id_jizaishixiang8.style.background = "#E1F5FE";
		var id_jizaishixiang9 = document.getElementById("id_jizaishixiang9");
		id_jizaishixiang9.style.background = "#E1F5FE";
		var id_jizaishixiang = document.getElementById("id_jizaishixiang");
		id_jizaishixiang.style.background = "#E1F5FE";
		@endhasanyrole

		var _this = this;
		_this.configgets();
	}
})
</script>
@endsection