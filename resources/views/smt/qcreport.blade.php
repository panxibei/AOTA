@extends('smt.layouts.mainbase')

@section('my_title')
SMT(QC report) - 
@parent
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_project')
<strong>SMT QC Report</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Tabs type="card" v-model="currenttabs">
		<Tab-pane label="工程内不良录入">

			<br>
			<i-row :gutter="16">
				<i-col span="8">
					* <strong>扫描</strong>&nbsp;&nbsp;
					<i-input ref="saomiao" v-model.lazy="saomiao" @on-keyup="saomiao=saomiao.toUpperCase()" placeholder="例：MRAP808A/5283600121-51/MAIN/900" size="large" clearable autofocus style="width: 320px"></i-input>
				</i-col>
				<i-col span="3">
					* 线体&nbsp;&nbsp;
					<i-select v-model.lazy="xianti" clearable style="width:80px" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					* 班次&nbsp;&nbsp;
					<i-select v-model.lazy="banci" clearable style="width:80px" placeholder="">
						<i-option v-for="item in option_banci" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					* 工序&nbsp;&nbsp;
					<i-select v-model.lazy="gongxu" @on-change="onchangegongxu" clearable style="width:80px" placeholder="">
						<i-option v-for="item in option_gongxu" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					* 点/枚&nbsp;&nbsp;
					<Input-number v-model.lazy="dianmei" :min="1" readonly style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					* 枚数&nbsp;&nbsp;
					<Input-number v-model.lazy="meishu" :min="1" style="width: 80px"></Input-number>
				</i-col>
				<input v-model.lazy="shengchanriqi" hidden="hidden"></input>
			</i-row>

			<br><br><br>
			
			<i-row :gutter="16">
				<i-col span="24">
					↓ 批量录入&nbsp;&nbsp;
					<Input-number v-model.lazy="piliangluruxiang" @on-change="value=>piliangluru_generate(value)" :min="1" :max="10" size="small" style="width: 60px"></Input-number>
					&nbsp;项（最多10项）&nbsp;&nbsp;
					<i-switch v-model="piliangluru_keep" size="small"></i-switch>&nbsp;保持批量录入数
				</i-col>
			</i-row>
			
			&nbsp;

			<span v-for="(item, index) in piliangluru">
			<br>
			<i-row :gutter="16">
				<i-col span="1">
					&nbsp;No.@{{index+1}}
				</i-col>
				<i-col span="5">
					检查机类型&nbsp;&nbsp;
					<i-select v-model.lazy="item.jianchajileixing" size="small" clearable style="width:120px" placeholder="">
						<i-option v-for="item in option_jianchajileixing" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="6">
					不良内容&nbsp;&nbsp;
					<i-select v-model.lazy="item.buliangneirong" size="small" clearable style="width:200px" placeholder="例：部品不良">
						<Option-group label="****** 印刷系 ******">
							<i-option v-for="item in option_buliangneirong1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 装着系 ******">
							<i-option v-for="item in option_buliangneirong2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 异物系 ******">
							<i-option v-for="item in option_buliangneirong3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 人系 ******">
							<i-option v-for="item in option_buliangneirong4" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 部品系 ******">
							<i-option v-for="item in option_buliangneirong5" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 其他 ******">
							<i-option v-for="item in option_buliangneirong6" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
					</i-select>
				</i-col>
				<i-col span="4">
					位号&nbsp;&nbsp;
					<i-input v-model.lazy="item.weihao" @on-keyup="item.weihao=item.weihao.toUpperCase()" placeholder="例：IC801" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="3">
					数量&nbsp;&nbsp;
					<Input-number v-model.lazy="item.shuliang" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="5">
					检查者&nbsp;&nbsp;
					<i-select v-model.lazy="item.jianchazhe" size="small" clearable style="width:140px" placeholder="">
						<Option-group label="****** 一组 ******">
							<i-option v-for="item in option_jianchazhe1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 二组 ******">
							<i-option v-for="item in option_jianchazhe2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 三组 ******">
							<i-option v-for="item in option_jianchazhe3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
					</i-select>
				</i-col>
				
			</i-row>
			<br>
			</span>

			<br>

			<i-row :gutter="16">
				<i-col span="24">
					&nbsp;&nbsp;<i-button @click="oncreate()" type="primary">记入</i-button>
					&nbsp;&nbsp;<i-button @click="onclear()">清除</i-button>
				</i-col>
			</i-row>

			
			<br><br><br>

		</Tab-pane>

		<Tab-pane label="品质管理日报">

			<br>
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="6">
					* 日期范围&nbsp;&nbsp;
					<Date-picker v-model.lazy="qcdate_filter" :options="qcdate_filter_options" @on-change="qcreportgets(pagecurrent, pagelast);onselectchange1();" type="daterange" size="small" style="width:200px"></Date-picker>
				</i-col>
				<i-col span="3">
					线体&nbsp;&nbsp;
					<i-select v-model.lazy="xianti_filter" @on-change="qcreportgets(pagecurrent, pagelast);onselectchange1();" clearable size="small" style="width:80px" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					班次&nbsp;&nbsp;
					<i-select v-model.lazy="banci_filter" @on-change="qcreportgets(pagecurrent, pagelast);onselectchange1();" clearable size="small" style="width:80px" placeholder="">
						<i-option v-for="item in option_banci" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="9">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="3">
					&nbsp;
				</i-col>
				<i-col span="4">
					机种名&nbsp;&nbsp;
					<i-input v-model.lazy="jizhongming_filter" @on-change="qcreportgets(pagecurrent, pagelast)" @on-keyup="jizhongming_filter=jizhongming_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
					品名&nbsp;&nbsp;
					<i-select v-model.lazy="pinming_filter" @on-change="qcreportgets(pagecurrent, pagelast)" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_pinming" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					工序&nbsp;&nbsp;
					<i-select v-model.lazy="gongxu_filter" @on-change="qcreportgets(pagecurrent, pagelast)" clearable style="width:80px" size="small" placeholder="">
						<i-option v-for="item in option_gongxu" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="10">
					不良内容&nbsp;&nbsp;
					<i-select v-model.lazy="buliangneirong_filter" @on-change="qcreportgets(pagecurrent, pagelast);onselectchange1();" multiple size="small" clearable style="width:200px" placeholder="例：连焊">
						<Option-group label="****** 印刷系 ******">
							<i-option v-for="item in option_buliangneirong1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 装着系 ******">
							<i-option v-for="item in option_buliangneirong2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 异物系 ******">
							<i-option v-for="item in option_buliangneirong3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 人系 ******">
							<i-option v-for="item in option_buliangneirong4" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 部品系 ******">
							<i-option v-for="item in option_buliangneirong5" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
						<Option-group label="****** 其他 ******">
							<i-option v-for="item in option_buliangneirong6" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</Option-group>
					</i-select>
				</i-col>
			</i-row>
			<br><br><br>

			<Tabs type="card" v-model="currentsubtabs">
				<Tab-pane label="品质管理日报">

					<i-row :gutter="16">
						<br>
						<i-col span="2">
							<i-button @click="ondelete()" :disabled="boo_delete" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;
						</i-col>
						<i-col span="8">
							导出：<!--&nbsp;&nbsp;&nbsp;&nbsp;
							<i-button type="default" size="small" @click="exportData_table()"><Icon type="ios-download-outline"></Icon> 导出当前显示数据</i-button>-->
							&nbsp;&nbsp;
							<i-button type="default" size="small" @click="exportData_db()"><Icon type="ios-download-outline"></Icon> 导出后台数据</i-button>
						</i-col>
						<i-col span="10">
							&nbsp;
						</i-col>
						<i-col span="4">
							<!-- &nbsp;&nbsp;&nbsp;<strong>不良件数小计：@{{ buliangjianshuheji.toLocaleString() }} </strong>&nbsp;&nbsp; -->
						</i-col>
					</i-row>

					<i-row :gutter="16">
						<i-col span="24">
							<i-table ref="table1" height="400" size="small" border :columns="tablecolumns1" :data="tabledata1" @on-selection-change="selection => onselectchange1(selection)"></i-table>
							<br><Page :current="pagecurrent" :total="pagetotal" :page-size="pagepagesize" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total show-elevator></Page><br><br>
						</i-col>
					</i-row>

					<Modal v-model="modal_qcreport_edit" @on-ok="qcreport_edit_ok" ok-text="保存" title="工程内不良记录编辑" width="540">
						<div style="text-align:left">
							<p>
								机种名：@{{ jizhongming_edit }}
							
								&nbsp;&nbsp;&nbsp;&nbsp;
								
								创建时间：@{{ created_at_edit }}
								
								&nbsp;&nbsp;&nbsp;&nbsp;
								
								更新时间：@{{ updated_at_edit }}
							
							</p>
							<br>
							
							<!--<span v-for="(item, index) in piliangbianji">-->
							<p>
								枚数&nbsp;&nbsp;
								<Input-number v-model.lazy="meishu_edit" :min="1" size="small" style="width: 80px"></Input-number>

								&nbsp;&nbsp;&nbsp;&nbsp;
							
								检查机类型&nbsp;&nbsp;
								<i-select v-model.lazy="jianchajileixing_edit" size="small" clearable style="width:120px" placeholder="">
									<i-option v-for="item in option_jianchajileixing" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
								</i-select>

								&nbsp;&nbsp;&nbsp;&nbsp;
								
								检查者&nbsp;&nbsp;
								<i-select v-model.lazy="jianchazhe_edit" size="small" clearable style="width:100px" placeholder="">
									<Option-group label="*** 一组 ***">
										<i-option v-for="item in option_jianchazhe1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="*** 二组 ***">
										<i-option v-for="item in option_jianchazhe2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="*** 三组 ***">
										<i-option v-for="item in option_jianchazhe3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
								</i-select>
							</p>
							<br>
							
							<p>
								不良内容&nbsp;&nbsp;
								<i-select v-model.lazy="buliangneirong_edit" size="small" clearable style="width:200px" placeholder="例：部品不良">
									<Option-group label="****** 印刷系 ******">
										<i-option v-for="item in option_buliangneirong1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="****** 装着系 ******">
										<i-option v-for="item in option_buliangneirong2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="****** 异物系 ******">
										<i-option v-for="item in option_buliangneirong3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="****** 人系 ******">
										<i-option v-for="item in option_buliangneirong4" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="****** 部品系 ******">
										<i-option v-for="item in option_buliangneirong5" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
									<Option-group label="****** 其他 ******">
										<i-option v-for="item in option_buliangneirong6" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
									</Option-group>
								</i-select>
							</p>
							<br>

							<p>
								位号&nbsp;&nbsp;
								<i-input v-model.lazy="weihao_edit" @on-keyup="weihao_edit=weihao_edit.toUpperCase()" placeholder="例：IC801" size="small" clearable style="width: 120px"></i-input>

								&nbsp;&nbsp;&nbsp;&nbsp;
								
								数量&nbsp;&nbsp;
								<Input-number v-model.lazy="shuliang_edit[1]" :min="0" size="small" style="width: 80px"></Input-number>

								&nbsp;&nbsp;&nbsp;&nbsp;
								

							</p>
							<br>
							<!--</span>-->
							
							&nbsp;
						
							<p>
							※ 数量为 0 保存时，自动清除 “不良内容” 和 “位号” 的内容。
							</p>
						
						</div>	
					</Modal>

				</Tab-pane>

				<Tab-pane label="图表 - 工程内不良记录（PPM）">

					<i-button @click="onchart1()" type="info" size="small">刷新图表 ↘</i-button>
					<!--
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<Upload
						:before-upload="handleUpload"
						action="{{ route('smt.qcreport.qcreportimport') }}">
						<i-button icon="ios-cloud-upload-outline">Upload files</i-button>
					</Upload>
					<div v-if="file !== null">Upload file: @{{ file.name }} <i-button @click="upload" :loading="loadingStatus" size="small">@{{ loadingStatus ? 'Uploading' : 'Click to upload' }}</i-button></div>
					-->

					<br><br>
					<i-row :gutter="16">
						<i-col span="24">
							<div id="chart1" style="height:600px"></div>
						</i-col>
					</i-row>

				</Tab-pane>

				<Tab-pane label="图表 - 按不良内容统计不良占有率">

					<i-button @click="onchart2()" type="info" size="small">刷新图表 ↘</i-button>
					<br><br>
					<i-row :gutter="16">
						<i-col span="24">
							<div id="chart2" style="height:500px"></div>
						</i-col>
					</i-row>

				</Tab-pane>

				<Tab-pane label="图表 - 按月份对比不良率和PPM">

					<i-button @click="onchart3()" type="info" size="small">刷新图表 ↘</i-button>
					<br><br>
					<i-row :gutter="16">
						<i-col span="24">
							<div id="chart3" style="height:600px"></div>
						</i-col>
					</i-row>

				</Tab-pane>

			</Tabs>







		</Tab-pane>




	</Tabs>
	
</div>
@endsection

@section('my_js_others')
@parent	
<script>
var vm_app = new Vue({
	el: '#app',
	data: {
		// 批量录入
		piliangluru: [
			{
				jianchajileixing: '',
				buliangneirong: '',
				weihao: '',
				shuliang: '',
				jianchazhe: ''
			},
		],
		piliangluru_keep: false,
		
		// 扫描
		// saomiao: 'MRAP808A/5283600121-51/MAIN/900',
		saomiao: '',
		
		// 批量录入项
		piliangluruxiang: 1,
		
		//工序
		gongxu: '',
		option_gongxu: [
			{value: 'A', label: 'A'},
			{value: 'B', label: 'B'},
			{value: 'CP', label: 'CP'},
			{value: 'RB', label: 'RB'},
			{value: 'RD', label: 'RD'},
			{value: 'RF', label: 'RF'},
		],
		
		// 点/枚
		dianmei: '',
		
		// 枚数
		meishu: '',
		
		// 检查机类型
		option_jianchajileixing: [
			{
				value: 'AOI-1',
				label: 'AOI-1'
			},
			{
				value: 'AOI-2',
				label: 'AOI-2'
			},
			{
				value: 'AOI-3',
				label: 'AOI-3'
			},
			{
				value: 'AOI-4',
				label: 'AOI-4'
			},
			{
				value: 'VQZ',
				label: 'VQZ'
			},
			{
				value: 'MD',
				label: 'MD'
			}
		],
		
		// 生产日期
		shengchanriqi: '',
		
		// 线体
		xianti: '',
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
		option_banci: [
			{
				value: 'A-1',
				label: 'A-1'
			},
			{
				value: 'A-2',
				label: 'A-2'
			},
			{
				value: 'A-3',
				label: 'A-3'
			},
			{
				value: 'B-1',
				label: 'B-1'
			},
			{
				value: 'B-2',
				label: 'B-2'
			},
			{
				value: 'B-3',
				label: 'B-3'
			}
		],
		
		// 不良内容
		// select_buliangneirong: '',
		option_buliangneirong1: [
			{value: '连焊', label: '连焊'}, {value: '引脚焊锡量少', label: '引脚焊锡量少'},
			{value: 'CHIP部品焊锡少', label: 'CHIP部品焊锡少'}, {value: '焊锡球', label: '焊锡球'}
		],
		option_buliangneirong2: [
			{value: '1005部品浮起.竖立', label: '1005部品浮起.竖立'}, {value: 'CHIP部品横立', label: 'CHIP部品横立'},
			{value: '部品浮起.竖立', label: '部品浮起.竖立'}, {value: '欠品', label: '欠品'},
			{value: '焊锡未熔解', label: '焊锡未熔解'}, {value: '位置偏移', label: '位置偏移'},
			{value: '部品打反', label: '部品打反'}, {value: '部品错误', label: '部品错误'},
			{value: '多余部品', label: '多余部品'}
		],
		option_buliangneirong3: [
			{value: '异物', label: '异物'},
		],
		option_buliangneirong4: [
			{value: '极性错误', label: '极性错误'},{value: '炉后部品破损', label: '炉后部品破损'},
			{value: '引脚弯曲', label: '引脚弯曲'},{value: '基板/部品变形后引脚浮起', label: '基板/部品变形后引脚浮起'},
		],
		option_buliangneirong5: [
			{value: '引脚不上锡', label: '引脚不上锡'},{value: '基板不上锡', label: '基板不上锡'},
			{value: 'CHIP部品不上锡', label: 'CHIP部品不上锡'},{value: '基板不良', label: '基板不良'},
			{value: '部品不良', label: '部品不良'}
		],
		option_buliangneirong6: [
			{value: '其他', label: '其他'},
		],

		// 品名
		option_pinming: [
			{
				value: 'MAIN',
				label: 'MAIN'
			},
			{
				value: 'DIGITAL',
				label: 'DIGITAL'
			}
		],
		
		// 检查者
		option_jianchazhe1: [
			{value: '许瑞萍', label: '许瑞萍'},
			{value: '李世英', label: '李世英'},
			{value: '张向果', label: '张向果'},
			{value: '第小霞', label: '第小霞'},
			{value: '蔡素英', label: '蔡素英'},
			{value: '孙吻茹', label: '孙吻茹'},
			{value: '葛敏', label: '葛敏'},
			{value: '陈小枝', label: '陈小枝'},
			{value: '李阳', label: '李阳'},
		],
		option_jianchazhe2: [
			{value: '贾东梅', label: '贾东梅'},
			{value: '蔡小红', label: '蔡小红'},
			{value: '黄俊英', label: '黄俊英'},
			{value: '黎小娟', label: '黎小娟'},
			{value: '张艳敏', label: '张艳敏'},
			{value: '杨晓娟', label: '杨晓娟'},
			{value: '朱风婷', label: '朱风婷'},
		],
		option_jianchazhe3: [
			{value: '王凤娇', label: '王凤娇'},
			{value: '肖厚春', label: '肖厚春'},
			{value: '朱建珊', label: '朱建珊'},
			{value: '李燕', label: '李燕'},
			{value: '张艳红', label: '张艳红'},
			{value: '贺转云', label: '贺转云'},
			{value: '曾加英', label: '曾加英'},
		],
		
		// 表头1
		tablecolumns1: [
			{
				type: 'selection',
				width: 50,
				align: 'center',
				fixed: 'left'
			},
			// {
				// type: 'index',
				// width: 60,
				// align: 'center'
			// },
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
									vm_app.qcreport_edit(params.row)
								}
							}
						}, 'Edit')
					]);
				},
				fixed: 'right'
			},			
/* 			{
				title: '生产日期',
				key: 'shengchanriqi',
				align: 'center',
				width: 160,
			},
 */			
			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80,
				// filters: [
					// {
						// label: 'SMT-1',
						// value: 'SMT-1'
					// },
					// {
						// label: 'SMT-2',
						// value: 'SMT-2'
					// },
					// {
						// label: 'SMT-3',
						// value: 'SMT-3'
					// },
					// {
						// label: 'SMT-4',
						// value: 'SMT-4'
					// },
					// {
						// label: 'SMT-5',
						// value: 'SMT-5'
					// },
					// {
						// label: 'SMT-6',
						// value: 'SMT-6'
					// },
					// {
						// label: 'SMT-7',
						// value: 'SMT-7'
					// },
					// {
						// label: 'SMT-8',
						// value: 'SMT-8'
					// },
					// {
						// label: 'SMT-9',
						// value: 'SMT-9'
					// },
					// {
						// label: 'SMT-10',
						// value: 'SMT-10'
					// },
				// ],
				// filterMultiple: false,
				// filterMethod: function (value, row) {
					// if (value === 'SMT-1') {
						// return row.xianti === 'SMT-1';
					// } else if (value === 'SMT-2') {
						// return row.xianti === 'SMT-2';
					// } else if (value === 'SMT-3') {
						// return row.xianti === 'SMT-3';
					// } else if (value === 'SMT-4') {
						// return row.xianti === 'SMT-4';
					// } else if (value === 'SMT-5') {
						// return row.xianti === 'SMT-5';
					// } else if (value === 'SMT-6') {
						// return row.xianti === 'SMT-6';
					// } else if (value === 'SMT-7') {
						// return row.xianti === 'SMT-7';
					// } else if (value === 'SMT-8') {
						// return row.xianti === 'SMT-8';
					// } else if (value === 'SMT-9') {
						// return row.xianti === 'SMT-9';
					// } else if (value === 'SMT-10') {
						// return row.xianti === 'SMT-10';
					// }
				// }
			},
			{
				title: '班次',
				key: 'banci',
				align: 'center',
				width: 80,
				// filters: [
					// {
						// label: 'A-1',
						// value: 'A-1'
					// },
					// {
						// label: 'A-2',
						// value: 'A-2'
					// },
					// {
						// label: 'A-3',
						// value: 'A-3'
					// },
					// {
						// label: 'B-1',
						// value: 'B-1'
					// },
					// {
						// label: 'B-2',
						// value: 'B-2'
					// },
					// {
						// label: 'B-3',
						// value: 'B-3'
					// }
				// ],
				// filterMultiple: false,
				// filterMethod: function (value, row) {
					// if (value === 'A-1') {
						// return row.banci === 'A-1';
					// } else if (value === 'A-2') {
						// return row.banci === 'A-2';
					// } else if (value === 'A-3') {
						// return row.banci === 'A-3';
					// } else if (value === 'B-1') {
						// return row.banci === 'B-1';
					// } else if (value === 'B-2') {
						// return row.banci === 'B-2';
					// } else if (value === 'B-3') {
						// return row.banci === 'B-3';
					// }
				// }
			},
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 120,
				// sortable: true
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 100,
				// sortable: true
			},
			{
				title: '工序',
				key: 'gongxu',
				align: 'center',
				width: 80
			},
			{
				title: 'SP NO.',
				key: 'spno',
				align: 'center',
				width: 140,
				// sortable: true
			},
			{
				title: 'LOT数',
				key: 'lotshu',
				align: 'center',
				width: 100,
				// sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.lotshu.toLocaleString()
					]);
				}
			},
			{
				title: '点/枚',
				key: 'dianmei',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.dianmei.toLocaleString()
					]);
				}
			},
			{
				title: '枚数',
				key: 'meishu',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.meishu.toLocaleString()
					]);
				}
			},
			{
				title: '合计点数',
				key: 'hejidianshu',
				align: 'center',
				width: 100,
				render: (h, params) => {
					return h('div', [
						// parseFloat(params.row.hejidianshu * 100) + '%'
						params.row.hejidianshu.toLocaleString()
					]);
				}
			},
			{
				title: '不适合件数合计',
				key: 'bushihejianshuheji',
				align: 'center',
				width: 100
			},
			{
				title: 'PPM',
				key: 'ppm',
				align: 'center',
				width: 100
			},
			{
				title: '不良内容',
				key: 'buliangneirong',
				align: 'center',
				width: 120,
				// filters: [
					// {value: '连焊', label: '连焊'}, 
					// {value: '引脚焊锡量少', label: '引脚焊锡量少'},
					// {value: 'CHIP部品焊锡少', label: 'CHIP部品焊锡少'},
					// {value: '焊锡球', label: '焊锡球'},
					// {value: '1005部品浮起.竖立', label: '1005部品浮起.竖立'},
					// {value: 'CHIP部品横立', label: 'CHIP部品横立'},
					// {value: '部品浮起.竖立', label: '部品浮起.竖立'},
					// {value: '欠品', label: '欠品'},
					// {value: '焊锡未熔解', label: '焊锡未熔解'},
					// {value: '位置偏移', label: '位置偏移'},
					// {value: '部品打反', label: '部品打反'},
					// {value: '部品错误', label: '部品错误'},
					// {value: '多余部品', label: '多余部品'},
					// {value: '异物', label: '异物'}, 
					// {value: '极性错误', label: '极性错误'},
					// {value: '炉后部品破损', label: '炉后部品破损'},
					// {value: '引脚弯曲', label: '引脚弯曲'},
					// {value: '基板/部品变形后引脚浮起', label: '基板/部品变形后引脚浮起'},
					// {value: '引脚不上锡', label: '引脚不上锡'},
					// {value: '基板不上锡', label: '基板不上锡'},
					// {value: 'CHIP部品不上锡', label: 'CHIP部品不上锡'},
					// {value: '基板不良', label: '基板不良'},
					// {value: '部品不良', label: '部品不良'},
					// {value: '其他', label: '其他'},
				// ],
				// filterMultiple: false,
				// filterMethod: function (value, row) {
					// var result = '';
					// if (value === '连焊') {
						// result = row.buliangneirong === '连焊';
					// } else if (value === '引脚焊锡量少') {
						// result = row.buliangneirong === '引脚焊锡量少';
					// } else if (value === 'CHIP部品焊锡少') {
						// result = row.buliangneirong === 'CHIP部品焊锡少';
					// } else if (value === '焊锡球') {
						// result = row.buliangneirong === '焊锡球';
					// } else if (value === '1005部品浮起.竖立') {
						// result = row.buliangneirong === '1005部品浮起.竖立';
					// } else if (value === 'CHIP部品横立') {
						// result = row.buliangneirong === 'CHIP部品横立';
					// } else if (value === '部品浮起.竖立') {
						// result = row.buliangneirong === '部品浮起.竖立';
					// } else if (value === '欠品') {
						// result = row.buliangneirong === '欠品';
					// } else if (value === '焊锡未熔解') {
						// result = row.buliangneirong === '焊锡未熔解';
					// } else if (value === '位置偏移') {
						// result = row.buliangneirong === '位置偏移';
					// } else if (value === '部品打反') {
						// result = row.buliangneirong === '部品打反';
					// } else if (value === '部品错误') {
						// result = row.buliangneirong === '部品错误';
					// } else if (value === '多余部品') {
						// result = row.buliangneirong === '多余部品';
					// } else if (value === '异物') {
						// result = row.buliangneirong === '异物';
					// } else if (value === '极性错误') {
						// result = row.buliangneirong === '极性错误';
					// } else if (value === '炉后部品破损') {
						// result = row.buliangneirong === '炉后部品破损';
					// } else if (value === '引脚弯曲') {
						// result = row.buliangneirong === '引脚弯曲';
					// } else if (value === '基板/部品变形后引脚浮起') {
						// result = row.buliangneirong === '基板/部品变形后引脚浮起';
					// } else if (value === '引脚不上锡') {
						// result = row.buliangneirong === '引脚不上锡';
					// } else if (value === '基板不上锡') {
						// result = row.buliangneirong === '基板不上锡';
					// } else if (value === 'CHIP部品不上锡') {
						// result = row.buliangneirong === 'CHIP部品不上锡';
					// } else if (value === '基板不良') {
						// result = row.buliangneirong === '基板不良';
					// } else if (value === '部品不良') {
						// result = row.buliangneirong === '部品不良';
					// } else if (value === '其他') {
						// result = row.buliangneirong === '其他';
					// }
					// return result;
				// }
			},
			{
				title: '位号',
				key: 'weihao',
				align: 'center',
				width: 120
			},
			{
				title: '数量',
				key: 'shuliang',
				align: 'center',
				width: 80
			},
			{
				title: '检查机类型',
				key: 'jianchajileixing',
				align: 'center',
				width: 120
			},
			{
				title: '检查者',
				key: 'jianchazhe',
				align: 'center',
				width: 120
			},
			{
				title: '创建日期',
				key: 'created_at',
				align: 'center',
				width: 160,
			},
			{
				title: '更新日期',
				key: 'updated_at',
				align: 'center',
				width: 160,
			},
		],
		tabledata1: [],
		tableselect1: [],
		

		
		// 日期范围过滤
		qcdate_filter: [], //new Date(),
		qcdate_filter_options: {
			shortcuts: [
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

		// 机种名
		jizhongming_filter: '',
		
		// 线体过滤
		xianti_filter: '',

		// 班次过滤
		banci_filter: '',

		// 品名过滤
		pinming_filter: '',

		// 工序过滤
		gongxu_filter: '',
		
		// 不良内容过滤
		buliangneirong_filter: '',
		
		// 删除disabled
		boo_delete: true,
		
		// dailyproductionreport 暂未用到
		saomiao_data: '',
		
		// 不良件数小计
		buliangjianshuheji: 0,
		
		
		// echarts ajax使用 这个才是实际使用的
		// chart1_type: 'bar',
		
		// chart1_option_tooltip_show: true,
		
		// chart1_option_legend_data: ['不适合件数合计', '合计点数', 'PPM'],
		chart1_option_legend_data: ['不良件数', '合计点数', 'PPM'],
		
		chart1_option_xAxis_data: ['SMT-1','SMT-2','SMT-3','SMT-4','SMT-5','SMT-6','SMT-7','SMT-8','SMT-9','SMT-10'],
		
		chart1_option_series: [
			// {
				// name: '销量1',
				// type: 'line',
				// data: [5, 20, 40, 10, 10, 20],
				// markLine: {
					// data: [
						// {type: 'average', name: '平均值'}
					// ]
				// }
			// },
			// {
				// 'name': '销量2',
				// 'type': 'line',
				// 'data': [15, 120, 140,110, 110, 123]
			// }
		],
		
		// chart2参数
		chart2_option_title_text: '按不良内容统计不良占有率',
		chart2_option_legend_data: [
			'连焊','引脚焊锡量少','CHIP部品焊锡少','焊锡球',
			'1005部品浮起.竖立','CHIP部品横立','部品浮起.竖立','欠品','焊锡未熔解','位置偏移','部品打反','部品错误','多余部品',
			'异物',
			'极性错误','炉后部品破损','引脚弯曲','基板/部品变形后引脚浮起',
			'引脚不上锡','基板不上锡','CHIP部品不上锡','基板不良','部品不良',
			'其他',
		],
		
		chart2_option_series_data: [
			{value:335, name:'连焊'},
			{value:310, name:'引脚焊锡量少'},
			{value:335, name:'CHIP部品焊锡少'},
			{value:310, name:'焊锡球'},
			{value:234, name:'1005部品浮起.竖立'},
			{value:135, name:'CHIP部品横立'},
			{value:154, name:'部品浮起.竖立'},
			{value:335, name:'欠品'},
			{value:310, name:'焊锡未熔解'},
			{value:234, name:'位置偏移'},
			{value:236, name:'部品打反'},
			{value:274, name:'部品错误'},
			{value:294, name:'多余部品'},
			{value:334, name:'异物'},
			{value:134, name:'极性错误'},
			{value:214, name:'炉后部品破损'},
			{value:24, name:'引脚弯曲'},
			{value:68, name:'基板/部品变形后引脚浮起'},
			{value:32, name:'引脚不上锡'},
			{value:99, name:'基板不上锡'},
			{value:165, name:'CHIP部品不上锡'},
			{value:256, name:'基板不良'},
			{value:290, name:'部品不良'},
			{value:50, name:'其他'},
		],
		
		chart2_option_title_text_huizong: '按不良类别',
		
		chart2_option_series_data_huizong: [
			{value:335, name:'印刷系'},
			{value:679, name:'装着系'},
			{value:679, name:'异物系'},
			{value:679, name:'人系'},
			{value:679, name:'部品系'},
			{value:679, name:'其他系'},
			// {value:1548, name:'搜索引擎', selected:true}
		],
		
		// chart3
		chart3_option_xAxis_data: ['FY17平均','4月','5月','6月','7月','8月','9月','10月','11月','12月','1月','2月','3月',],
		
		chart3_option_title_text: '按不良内容统计不良占有率',
		chart3_option_legend_data: [
			'连焊','引脚焊锡量少','CHIP部品焊锡少','焊锡球',
			'1005部品浮起.竖立','CHIP部品横立','部品浮起.竖立','欠品','焊锡未熔解','位置偏移','部品打反','部品错误','多余部品',
			'异物',
			'极性错误','炉后部品破损','引脚弯曲','基板/部品变形后引脚浮起',
			'引脚不上锡','基板不上锡','CHIP部品不上锡','基板不良','部品不良',
			'其他',
		],
		// 以下按不良内容设定数组，下标24
		chart3_option_series_data: [
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0]
		],

		chart3_option_series_data_huizong: [0,0,0,0,0,0,0,0,0,0,0,0,0],
		chart3_option_series_data_hejidianshu: [0,0,0,0,0,0,0,0,0,0,0,0,0],
		chart3_option_series_data_ppm: [],
		
		
		//分页
		pagecurrent: 1,
		pagetotal: 1,
		pagepagesize: 10,
		pagelast: 1,
		
		file: null,
		loadingStatus: false,
		
		// 编辑
		modal_qcreport_edit: false,
		id_edit: '',
		jizhongming_edit: '',
		created_at_edit: '',
		updated_at_edit: '',
		jianchajileixing_edit: '',
		buliangneirong_edit: '',
		weihao_edit: '',
		shuliang_edit: [0, 0], //第一下标为原始值，第二下标为变化值
		jianchazhe_edit: '',
		dianmei_edit: '',
		meishu_edit: '',
		hejidianshu_edit: '',
		bushihejianshuheji_edit: '',
		ppm_edit: '',
		
		// tabs索引
		currenttabs: 0,
		currentsubtabs: 0,

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
		
		alert_logout: function () {
			this.error(false, '会话超时', '会话超时，请重新登录！');
			window.setTimeout(function(){
				window.location.href = "{{ route('portal') }}";
			}, 2000);
			return false;
		},
		
		// 切换当前页
		oncurrentpagechange: function (currentpage) {
			this.qcreportgets(currentpage, this.pagelast);
		},
		
		// 把laravel返回的结果转换成select能接受的格式
		json2select: function (value) {
			var arr = value.split(/[\s\n]/);
			var arr_result = [];

			arr.map(function (v, i) {
				arr_result.push({ value: v, label: v });
			});

			return arr_result;
		},
		
		
		configgets: function () {
			var _this = this;

			var url = "{{ route('smt.configgets') }}";
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
						
						if (v.name == 'xianti') {
							_this.option_xianti = _this.json2select(v.value);
						}
						else if (v.name == 'banci') {
							_this.option_banci = _this.json2select(v.value);
						}
						else if (v.name == 'gongxu') {
							_this.option_gongxu = _this.json2select(v.value);
						}
						else if (v.name == 'jianchajileixing') {
							_this.option_jianchajileixing = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong1') {
							_this.option_buliangneirong1 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong2') {
							_this.option_buliangneirong2 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong3') {
							_this.option_buliangneirong3 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong4') {
							_this.option_buliangneirong4 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong5') {
							_this.option_buliangneirong5 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong6') {
							_this.option_buliangneirong6 = _this.json2select(v.value);
						}
						else if (v.name == 'jianchazhe1') {
							_this.option_jianchazhe1 = _this.json2select(v.value);
						}
						else if (v.name == 'jianchazhe2') {
							_this.option_jianchazhe2 = _this.json2select(v.value);
						}
						else if (v.name == 'jianchazhe3') {
							_this.option_jianchazhe3 = _this.json2select(v.value);
						}
						else if (v.name == 'pinming') {
							_this.option_pinming = _this.json2select(v.value);
						}
					
					});

				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},		
		
		// qcreport列表
		qcreportgets: function (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			var qcdate_filter = [];

			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				
				// 日期范围优先
				_this.tabledata1 = [];
				// if (xianti_filter == '' && banci_filter == '' && jizhongming_filter == '' &&  pinming_filter == '' && gongxu_filter == '' && buliangneirong_filter == '') {
				// if (xianti_filter == '' || xianti_filter == undefined && banci_filter == '' || banci_filter != undefined && jizhongming_filter == '' || jizhongming_filter == undefined &&  pinming_filter == '' || pinming_filter == undefined && gongxu_filter == '' || gongxu_filter == undefined && buliangneirong_filter == '' || buliangneirong_filter == undefined) {
				// 	|| xianti_filter != undefined || banci_filter != undefined || jizhongming_filter != undefined ||  pinming_filter != undefined || gongxu_filter != undefined || buliangneirong_filter != undefined) {
					// _this.warning(false, '警告', '请先选择日期范围！');
				// }

				_this.warning(false, '警告', '请先选择日期范围！');
				return false;

				// 日期范围不需要优先
				/*
				if (jizhongming_filter == '' && pinfan_filter == '' && pinming_filter== '' && leibie_filter == '') {
					_this.tabledata_relation = [];
					return false;
				} else {
					const end = new Date();
					const start = new Date();
					// end.setTime(end.getTime() + 3600 * 1000 * 24 * 1);
					end.setDate(end.getDate());
					// start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);
					start.setDate(start.getDate() - 365);
					qcdate_filter = [start, end];
				}
				*/
			} else {
				qcdate_filter =  _this.qcdate_filter;
			}
			
			qcdate_filter = [qcdate_filter[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter[1].Format("yyyy-MM-dd 23:59:59")];
			
			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize,
					page: page,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter,
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.pagecurrent = response.data.current_page;
					_this.pagetotal = response.data.total;
					_this.pagelast = response.data.last_page
					
					_this.tabledata1 = response.data.data;
					
					// console.log(_this.tabledata1);
					
				} else {
					_this.tabledata1 = [];
				}
				
				// 合计
				_this.buliangjianshuheji = 0;
				for (var i in _this.tabledata1) {
					_this.buliangjianshuheji += _this.tabledata1[i].shuliang;
				}
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},		
		
		// 表1选择
		onselectchange1: function (selection) {
			var _this = this;
			_this.tableselect1 = [];

			for (var i in selection) {
				_this.tableselect1.push(selection[i].id);
			}
			
			_this.boo_delete = _this.tableselect1[0] == undefined ? true : false;
			
		},
		

		// 加载扫描相应信息（暂未用到）
		load_saomiao: function () {
			var _this = this;
			if (_this.saomiao.trim() == '') {
				_this.saomiao = '';
				return false;
			}
			
			var saomiao = _this.saomiao;
			
			var url = "{{ route('smt.qcreport.getsaomiao') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					saomiao: _this.saomiao
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
					_this.saomiao_data = response.data;
				} else {
					_this.saomiao_data = '';
					_this.warning(false, '警告', '输入内容不正确！未找到相应记录！');
					_this.$refs.saomiao.focus();
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
			})				
		},
		
		// onclear
		onclear: function () {
			var _this = this;

			// var piliangluruxiang = _this.piliangluruxiang;

			_this.saomiao = '';
			_this.shengchanriqi = '';
			_this.xianti = '';
			_this.banci = '';
			_this.gongxu = '';
			_this.dianmei = '';
			_this.meishu = '';
			
			if (_this.piliangluru_keep) {
				_this.piliangluru.map(function (v,i) {
					v.jianchajileixing = '';
					v.buliangneirong = '';
					v.weihao = '';
					v.shuliang = '';
					v.jianchazhe = '';
				});
			} else {
				_this.piliangluru = [
					{
						jianchajileixing: '',
						buliangneirong: '',
						weihao: '',
						shuliang: '',
						jianchazhe: '',
					}
				];
				_this.piliangluruxiang = 1;
			}
			
			_this.$refs.saomiao.focus();
		},
		
		// oncreate
		oncreate: function () {
			var _this = this;
			var saomiao = _this.saomiao;
			var shengchanriqi = _this.shengchanriqi;
			var xianti = _this.xianti;
			var banci = _this.banci;
			var gongxu = _this.gongxu;
			var dianmei = _this.dianmei;
			var meishu = _this.meishu;
			
			if (saomiao == '' || saomiao == undefined || xianti == '' || xianti == undefined
				|| banci == '' || banci == undefined || gongxu == '' || gongxu == undefined
				|| dianmei == '' || dianmei == undefined || dianmei == 0
				|| meishu == '' || meishu == undefined || meishu == 0) {
				_this.warning(false, '警告', '基本信息输入内容为空或不正确！');
				return false;
			}
			
			// 其他循环不支持跳出
			// var flag = true;
			// for (var v of _this.piliangluru) {
				// if (v.jianchajileixing == '' || v.buliangneirong == '' || v.weihao == ''  || v.shuliang == '' || v.jianchazhe == ''
					// || v.jianchajileixing == undefined || v.buliangneirong == undefined || v.weihao == undefined || v.shuliang == undefined || v.jianchazhe == undefined) {
					// flag = false;
					// break;
				// }
			// }
			
			// if (flag == false) {
				// _this.warning(false, '警告', '批量录入的不良内容为空或不正确！');
				// return false;
			// }
			
			var piliangluru = _this.piliangluru;
			var tableselect1 = _this.tableselect1;

			var url = "{{ route('smt.qcreport.qcreportcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				saomiao: saomiao,
				shengchanriqi: shengchanriqi,
				xianti: xianti,
				banci: banci,
				gongxu: gongxu,
				dianmei: dianmei,
				meishu: meishu,
				piliangluru: piliangluru
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.onclear();
					_this.success(false, '成功', '记入成功！');

					_this.boo_delete = true;
					_this.tableselect1 = [];

					if (_this.qcdate_filter[0] != '' && _this.qcdate_filter != undefined) {
						_this.qcreportgets(_this.pagecurrent, _this.pagelast);
					}

				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
			})
		},		
		
		// ondelete
		ondelete: function () {
			var _this = this;
			
			var tableselect1 = _this.tableselect1;
			
			if (tableselect1[0] == undefined) return false;

			var url = "{{ route('smt.qcreport.qcreportdelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect1: tableselect1
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.boo_delete = true;
					_this.tableselect1 = [];
					_this.qcreportgets(_this.pagecurrent, _this.pagelast);
					
					// var t = [];
					// for (var i in tableselect1) {
						// t.push({id: tableselect1[i]});
					// }
					// _this.onselectchange1(t);
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},
		
		
		// exportData_table 当前表数据导出
		exportData_table: function () {
			var _this = this;
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			_this.$refs.table1.exportCsv({
				filename: 'smt_qc_report_currentdata',
				original: false
			});
		},


		// exportData_db 当前表数据导出
		exportData_db: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}

			var queryfilter_datefrom = _this.qcdate_filter[0].Format("yyyy-MM-dd 00:00:00");
			var queryfilter_dateto = _this.qcdate_filter[1].Format("yyyy-MM-dd 23:59:59");
			
			var url = "{{ route('smt.qcreport.qcreportexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto;
				
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);

		},
		
		//
		// 生成piliangluru
		piliangluru_generate: function (counts) {
			if (counts == undefined) counts = 1;
			var len = this.piliangluru.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru.push({value: 'piliangluru'+parseInt(len+i+1)});
					this.piliangluru.push(
						{
							jianchajileixing: '',
							buliangneirong: '',
							weihao: '',
							shuliang: '',
							jianchazhe: ''
						}
					);
				}
			} else if (counts < len) {
				if (this.piliangluruxiang != '') {
					for (var i=counts;i<len;i++) {
						if (this.piliangluruxiang == this.piliangluru[i].value) {
							this.piliangluruxiang = '';
							break;
						}
					}
				}
				
				for (var i=0;i<len-counts;i++) {
					this.piliangluru.pop();
				}
			}			

		},

		// echarts public function 显示用的公共函数
		chart1_function: function () {
			// 路径配置
			require.config({
				paths: {
					// echarts: 'http://echarts.baidu.com/build/dist'
					echarts: "{{ asset('statics/echarts') }}"
				}
			});
			
			// 使用
			require(
				[
					'echarts',
					'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
					'echarts/chart/line'
					// 'echarts/chart/' + vm_app.chart1_type
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById('chart1'), 'macarons'); 
					
					var option = {
						title : {
							text: '工程内不良记录（PPM）',
							subtext: vm_app.qcdate_filter[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter[1].Format('yyyy-MM-dd'), //'2018.06-2018-07'
							x: 'center'
						},
						tooltip: {
							// show: vm_app.chart1_option_tooltip_show,
							show: true,
							trigger: 'axis'
						},
						legend: {
							data: vm_app.chart1_option_legend_data,
							x: 'left'
						},
						grid: {
							y: 80
						},
						xAxis : [
							{
								type : 'category',
								data : vm_app.chart1_option_xAxis_data
							}
						],
						yAxis : [
							{
								type : 'value',
								name : '件数',
								axisLabel : {
									formatter: '{value} 件'
								}
							},
							{
								type : 'value',
								name : 'PPM',
								axisLabel : {
									formatter: '{value} ppm'
								}
							}
						],
						calculable : true,
						toolbox: {
							show: true,
							feature: {
								mark: {show: true},
								dataView: {show: true, readOnly: false},
								restore: {show: true},
								saveAsImage: {show: true}
							}
						},
						series : vm_app.chart1_option_series
					};
			
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},

		chart2_function: function () {
			// 路径配置
			require.config({
				paths: {
					// echarts: 'http://echarts.baidu.com/build/dist'
					echarts: "{{ asset('statics/echarts') }}"
				}
			});
			
			// 使用
			require(
				[
					'echarts',
					'echarts/chart/pie', // 使用柱状图就加载bar模块，按需加载
					// 'echarts/chart/' + vm_app.chart1_type
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById('chart2'), 'macarons'); 
					
					var option = {
						title: {
							text: vm_app.chart2_option_title_text,
							subtext: vm_app.qcdate_filter[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter[1].Format('yyyy-MM-dd'),
							x:'center'
						},
						tooltip : {
							trigger: 'item',
							formatter: "{a} <br/>{b} : {c} ({d}%)"
						},
						legend: {
							orient : 'vertical',
							x : 'left',
							// data:['直达','营销广告','搜索引擎','邮件营销','联盟广告','视频广告','百度','谷歌','必应','其他']
							data: vm_app.chart2_option_legend_data
						},
						toolbox: {
							show : true,
							feature : {
								mark : {show: true},
								dataView : {show: true, readOnly: false},
								// magicType : {
									// show: true, 
									// type: ['pie', 'funnel']
								// },
								restore : {show: true},
								saveAsImage : {show: true}
							}
						},
						calculable : true,
						series : [
							{
								// name:'访问来源',
								name: vm_app.chart2_option_title_text_huizong,
								type:'pie',
								selectedMode: 'multiple',
								radius : [0, 70],
								center : ['50%', '66%'],
								
								itemStyle : {
									normal : {
										label : {
											position : 'inner',
											formatter : function (params) {                         
												return (params.percent - 0).toFixed(0) + '%'
											}
										},
										labelLine : {
											show : false
										}
									},
									emphasis: {
										label: {
											show: true,
											formatter: "{b}\n{d}%"
										}
									}
								},
								data: vm_app.chart2_option_series_data_huizong,
								// data:[
									// {value:335, name:'直达'},
									// {value:679, name:'营销广告'},
									// {value:1548, name:'搜索引擎', selected:true}
								// ]
							},
							{
								// name:'访问来源',
								name: vm_app.chart2_option_title_text,
								type:'pie',
								radius : [100, 140],
								center : ['50%', '66%'],
								selectedMode: 'multiple',
								itemStyle: {
									normal: {
										label: {
											// position : 'inner',
											formatter: function (params) {
												// console.log(params);
												return params.name + ' : ' + params.value + ' (' + (params.percent - 0).toFixed(0) + '%)'
											}
										},
										labelLine: {
											show : true
										}
									},
									emphasis: {
										label: {
											show: true,
											formatter: "{b}\n{d}%"
										}
									}
								},
								
								data: vm_app.chart2_option_series_data,
								// data:[
									// {value:335, name:'直达'},
									// {value:310, name:'邮件营销'},
									// {value:234, name:'联盟广告'},
									// {value:135, name:'视频广告'},
									// {value:1048, name:'百度'},
									// {value:251, name:'谷歌'},
									// {value:147, name:'必应'},
									// {value:102, name:'其他'}
								// ]
							}
						]
					};
					
			
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},

		chart3_function: function () {
			// 路径配置
			require.config({
				paths: {
					// echarts: 'http://echarts.baidu.com/build/dist'
					echarts: "{{ asset('statics/echarts') }}"
				}
			});
			
			// 使用
			require(
				[
					'echarts',
					'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
					'echarts/chart/line'
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById('chart3'), 'macarons'); 
					
					var option = {
						title: {
							text: '按月份对比不良率和PPM',
							x:'center'
						},
						tooltip: {
							trigger: 'axis',
							axisPointer: {            // 坐标轴指示器，坐标轴触发有效
								type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
							}
						},
						legend: {
							// data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎','百度','谷歌','必应','其他']
							data: vm_app.chart3_option_legend_data,
							x: 'left',
							padding: [40,5,5,5]
						},
						grid: {
							y: 120,
						},
						toolbox: {
							show: true,
							// orient: 'vertical',
							orient: 'horizontal',
							x: 'right',
							y: 'top',
							padding: [65, 5, 5, 5],
							feature: {
								mark: {show: true},
								dataView: {show: true, readOnly: false},
								// magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
								restore: {show: true},
								saveAsImage: {show: true}
							}
						},
						calculable: true,
						xAxis: [
							{
								type: 'category',
								// data: ['周一','周二','周三','周四','周五','周六','周日']
								data: vm_app.chart3_option_xAxis_data
							}
						],
						yAxis: [
							{
								type: 'value',
								name: '件数',
								axisLabel: {
									formatter: '{value} 件'
								}
							},
							{
								type: 'value',
								name: 'PPM',
								axisLabel: {
									formatter: '{value} ppm'
								}
							}
						],
						series: [
							{
								name:'连焊',
								type:'bar',
								// barWidth: 30,
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								// data:[620, 732, 701, 734, 1090, 1130, 1120, 620, 732, 701, 734, 620, 732]
								data: vm_app.chart3_option_series_data[0]
							},
							{
								name:'引脚焊锡量少',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								// data:[120, 132, 101, 134, 290, 230, 220, 210, 120, 132, 801, 134, 222]
								data: vm_app.chart3_option_series_data[1]
							},
							{
								name:'CHIP部品焊锡少',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[2]
							},
							{
								name:'焊锡球',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[3]
							},
							{
								name:'1005部品浮起.竖立',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[4]
							},
							{
								name:'CHIP部品横立',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[5]
							},
							{
								name:'部品浮起.竖立',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[6]
							},
							{
								name:'欠品',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[7]
							},
							{
								name:'焊锡未熔解',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[8]
							},
							{
								name:'位置偏移',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[9]
							},
							{
								name:'部品打反',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[10]
							},
							{
								name:'部品错误',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[11]
							},
							{
								name:'多余部品',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[12]
							},
							{
								name:'异物',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[13]
							},
							{
								name:'极性错误',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[14]
							},
							{
								name:'炉后部品破损',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[15]
							},
							{
								name:'引脚弯曲',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[16]
							},
							{
								name:'基板/部品变形后引脚浮起',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[17]
							},
							{
								name:'引脚不上锡',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[18]
							},
							{
								name:'基板不上锡',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[19]
							},
							{
								name:'CHIP部品不上锡',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[20]
							},
							{
								name:'基板不良',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[21]
							},
							{
								name:'部品不良',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[22]
							},
							{
								name:'其他',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[23]
							},
							{
								name:'汇总',
								type:'line',
								stack: '不良汇总',
								itemStyle: {
									normal: {
										label: {
											show: true,
											position: 'top',
											// formatter: function (params) {
												// var d = 0;
												// for (var i = 0, l = option.xAxis[0].data.length; i < l; i++) {
													// if (option.xAxis[0].data[i] == params.name) {
														// return option.series[0].data[i] + params.value;
														// d += option.series[0].data[i];
													// }
												// }
												// return d;
											// },
											textStyle: {
												fontSize: '18',
												fontFamily: '微软雅黑',
												// fontWeight: 'bold'
											}
										},
										lineStyle: {
											type: 'dashed',
											width: 1
										}
									}
								},
								// data:[5060, 6672, 6671, 6674, 10190, 10130, 10110]
								data: vm_app.chart3_option_series_data_huizong
							},
							{
								name: 'PPM',
								type: 'line',
								yAxisIndex: 1,
								itemStyle: {
									normal: {
										label: {
											show: true,
											// 'position' => 'outer'
											textStyle: {
												fontSize: '20',
												fontFamily: '微软雅黑',
												fontWeight: 'bold'
											}
										}
									}
								},
								// data: [10.5, 7.2, 7.1, 7.4, 5.9, 13.0, 11.0, 3.8, 7.7, 8.1, 19.0, 11.9, 4.9]
								data: vm_app.chart3_option_series_data_ppm
							}
						]
					};
						
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},
		
		
		
		// ajax返回后显示图表
		onchart1: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			var hejidianshu = [];
			var bushihejianshuheji = [];
			var shuliang = [];
			var ppm = [];
			
			var i = 0;
			for (i=0;i<10;i++) {
				hejidianshu[i] = 0;
				bushihejianshuheji[i] = 0;
				shuliang[i] = 0;
				ppm[i] = 0;
			}
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;
			
			// 图表按当前表格中最大记录数重新查询
			
			var qcdate_filter = [];

			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.tabledata1 = [];
				_this.warning(false, '警告', '请先选择日期范围！');
				return false;
			} else {
				qcdate_filter =  _this.qcdate_filter;
			}
			
			qcdate_filter = [qcdate_filter[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter[1].Format("yyyy-MM-dd 23:59:59")];

			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var chartdata1 = response.data.data;
					// console.log(chartdata1);
					chartdata1.map(function (v,j) {
						switch(v.xianti.trim())
						{
							case 'SMT-1':
								i = 0;break;
							case 'SMT-2':
								i = 1;break;
							case 'SMT-3':
								i = 2;break;
							case 'SMT-4':
								i = 3;break;
							case 'SMT-5':
								i = 4;break;
							case 'SMT-6':
								i = 5;break;
							case 'SMT-7':
								i = 6;break;
							case 'SMT-8':
								i = 7;break;
							case 'SMT-9':
								i = 8;break;
							case 'SMT-10':
								i = 9;break;
							default:
							  
						}
					
						hejidianshu[i] += v.hejidianshu;
						bushihejianshuheji[i] += v.bushihejianshuheji;
						shuliang[i] += v.shuliang;

						// if (hejidianshu[i] == 0) {
							// ppm[i] = 0;
						// } else {
							// ppm[i] = bushihejianshuheji[i] / hejidianshu[i] * 1000000;
						// }
						// ppm[i] += v.ppm;

					});
					// console.log(shuliang);

					// ppm计算
					hejidianshu.map(function (v,i) {

					
						// ppm[i] += v.ppm;
						// hejidianshu[i] += v.hejidianshu;
						// bushihejianshuheji[i] += v.bushihejianshuheji;

						if (hejidianshu[i] == 0) {
							ppm[i] = 0;
						} else {
							ppm[i] = (shuliang[i] / hejidianshu[i] * 1000000).toFixed(2);
						}

					});
					
					// console.log(bushihejianshuheji);
					// console.log(hejidianshu);
					// console.log(ppm);
					// return false;
					
					// bushihejianshuheji
					var a1 = [{
						// name: '不适合件数合计',
						name: '不良件数',
						type: 'bar',
						barWidth: 30,
						itemStyle: {
							normal: {
								label: {
									show: true,
									position: 'top'
								}
							}
						},
						// data: bushihejianshuheji
						data: shuliang
					},
					{
						name: '合计点数',
						type: 'bar',
						barWidth: 30,
						itemStyle: {
							normal: {
								label: {
									show: true,
									position: 'top'
								}
							}
						},
						data: hejidianshu
					},
					{
						name: 'PPM',
						type: 'line',
						yAxisIndex: 1,
						itemStyle: {
							normal: {
								label: {
									show: true,
									// 'position' => 'outer'
									textStyle: {
										fontSize: '20',
										fontFamily: '微软雅黑',
										fontWeight: 'bold'
									}
								}
							}
						},
						data: ppm
					}];

					_this.chart1_option_series = a1;
					_this.chart1_function();				
				
				}
				
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},
		
		
		onchart2: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			var shuliang = [];
			for (var i=0;i<24;i++) {
				shuliang[i] = 0;
			}

			var shuliang_huizong = [];
			for (var i=0;i<6;i++) {
				shuliang_huizong[i] = 0;
			}
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			// 图表按当前表格中最大记录数重新查询
			
			var qcdate_filter = [];

			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.tabledata1 = [];
				_this.warning(false, '警告', '请先选择日期范围！');
				return false;
			} else {
				qcdate_filter =  _this.qcdate_filter;
			}
			
			qcdate_filter = [qcdate_filter[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter[1].Format("yyyy-MM-dd 23:59:59")];
			
			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var chartdata2 = response.data.data;			
			
					chartdata2.map(function (v,j) {
						switch(v.buliangneirong)
						{
							case '连焊':
								i = 0;j = 0;break;
							case '引脚焊锡量少':
								i = 1;j = 0;break;
							case 'CHIP部品焊锡少':
								i = 2;j = 0;break;
							case '焊锡球':
								i = 3;j = 0;break;
							case '1005部品浮起.竖立':
								i = 4;j = 1;break;
							case 'CHIP部品横立':
								i = 5;j = 1;break;
							case '部品浮起.竖立':
								i = 6;j = 1;break;
							case '欠品':
								i = 7;j = 1;break;
							case '焊锡未熔解':
								i = 8;j = 1;break;
							case '位置偏移':
								i = 9;j = 1;break;
							case '部品打反':
								i = 10;j = 1;break;
							case '部品错误':
								i = 11;j = 1;break;
							case '多余部品':
								i = 12;j = 1;break;
							case '异物':
								i = 13;j = 2;break;
							case '极性错误':
								i = 14;j = 3;break;
							case '炉后部品破损':
								i = 15;j = 3;break;
							case '引脚弯曲':
								i = 16;j = 3;break;
							case '基板/部品变形后引脚浮起':
								i = 17;j = 3;break;
							case '引脚不上锡':
								i = 18;j = 4;break;
							case '基板不上锡':
								i = 19;j = 4;break;
							case 'CHIP部品不上锡':
								i = 20;j = 4;break;
							case '基板不良':
								i = 21;j = 4;break;
							case '部品不良':
								i = 22;j = 4;break;
							case '其他':
								i = 23;j = 5;break;
							default:
							  
						}
					
						// bushihejianshuheji[i] += v.bushihejianshuheji;
						shuliang[i] += v.shuliang;
						shuliang_huizong[j] += v.shuliang;
					});
					
					var data = 
					[
						{value: shuliang[0], name:'连焊'},
						{value: shuliang[1], name:'引脚焊锡量少'},
						{value: shuliang[2], name:'CHIP部品焊锡少'},
						{value: shuliang[3], name:'焊锡球'},
						{value: shuliang[4], name:'1005部品浮起.竖立'},
						{value: shuliang[5], name:'CHIP部品横立'},
						{value: shuliang[6], name:'部品浮起.竖立'},
						{value: shuliang[7], name:'欠品'},
						{value: shuliang[8], name:'焊锡未熔解'},
						{value: shuliang[9], name:'位置偏移'},
						{value: shuliang[10], name:'部品打反'},
						{value: shuliang[11], name:'部品错误'},
						{value: shuliang[12], name:'多余部品'},
						{value: shuliang[13], name:'异物'},
						{value: shuliang[14], name:'极性错误'},
						{value: shuliang[15], name:'炉后部品破损'},
						{value: shuliang[16], name:'引脚弯曲'},
						{value: shuliang[17], name:'基板/部品变形后引脚浮起'},
						{value: shuliang[18], name:'引脚不上锡'},
						{value: shuliang[19], name:'基板不上锡'},
						{value: shuliang[20], name:'CHIP部品不上锡'},
						{value: shuliang[21], name:'基板不良'},
						{value: shuliang[22], name:'部品不良'},
						{value: shuliang[23], name:'其他'},
					];

					var data_huizong = 
					[
						{value: shuliang_huizong[0], name:'印刷系'},
						{value: shuliang_huizong[1], name:'装着系'},
						{value: shuliang_huizong[2], name:'异物系'},
						{value: shuliang_huizong[3], name:'人系'},
						{value: shuliang_huizong[4], name:'部品系'},
						{value: shuliang_huizong[5], name:'其他系'},
					];
					
					// console.log(data);
					_this.chart2_option_series_data = data;
					_this.chart2_option_series_data_huizong = data_huizong;
					_this.chart2_function();
			
				}
				
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},		
		
		
		onchart3: function () {
			var _this = this;

			// 2018-12-31
			var current_year = new Date();
			var current_date = current_year.getFullYear() + '-12-31';
			
			// 2017-01-01
			var last_year = current_year.getFullYear() - 1;
			var last_date = last_year + '-01-01';
			
			// 查询去年到今年的日期范围
			var qcdate_filter = [last_date, current_date];
			
			// 修正表X轴文字，去年“FY2017平均”字样。
			_this.chart3_option_xAxis_data[0] = 'FY' + last_year + '平均';
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;
			
			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var chartdata3 = response.data.data;			
			
					_this.chart3_option_series_data = [
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0]
					];

					_this.chart3_option_series_data_huizong = [0,0,0,0,0,0,0,0,0,0,0,0,0];
					_this.chart3_option_series_data_hejidianshu = [0,0,0,0,0,0,0,0,0,0,0,0,0];
					
					_this.chart3_option_series_data_ppm = [0,0,0,0,0,0,0,0,0,0,0,0,0];
			
					// 去年和今年的日期范围
					var dd = new Date();
					var current_year = dd.getFullYear();
					var last_year = dd.getFullYear() - 1;

					var last_date_range = [new Date(last_year + '-01-01 00:00:00'), new Date(last_year + '-12-31 23:59:59')];
					var current_date_range = [new Date(current_year + '-01-01 00:00:00'), new Date(current_year + '-12-31 23:59:59')];

					// console.log(last_date_range);
					// console.log(current_date_range);
					// return false;
					
					chartdata3.map(function (v,k) {
						switch(v.buliangneirong)
						{
							case '连焊':
								i = 0;break;
							case '引脚焊锡量少':
								i = 1;break;
							case 'CHIP部品焊锡少':
								i = 2;break;
							case '焊锡球':
								i = 3;break;
							case '1005部品浮起.竖立':
								i = 4;break;
							case 'CHIP部品横立':
								i = 5;break;
							case '部品浮起.竖立':
								i = 6;break;
							case '欠品':
								i = 7;break;
							case '焊锡未熔解':
								i = 8;break;
							case '位置偏移':
								i = 9;break;
							case '部品打反':
								i = 10;break;
							case '部品错误':
								i = 11;break;
							case '多余部品':
								i = 12;break;
							case '异物':
								i = 13;break;
							case '极性错误':
								i = 14;break;
							case '炉后部品破损':
								i = 15;break;
							case '引脚弯曲':
								i = 16;break;
							case '基板/部品变形后引脚浮起':
								i = 17;break;
							case '引脚不上锡':
								i = 18;break;
							case '基板不上锡':
								i = 19;break;
							case 'CHIP部品不上锡':
								i = 20;break;
							case '基板不良':
								i = 21;break;
							case '部品不良':
								i = 22;break;
							case '其他':
								i = 23;break;
							default:
								i = 24;	
							  
						}
					
						// 按不良内容汇总数量，共24种
						if (i > 0 && i < 24) {
							// var riqi = new Date(v.shengchanriqi);
							var riqi = new Date(v.created_at);
							// var riqi = v.shengchanriqi.split('-');

							// 日期在去年的，统一保存到下标为0的数组中
							if (riqi >= last_date_range[0] && riqi <= last_date_range[1]) {
								j = 0;
							
							// 日期在今年的，按月份保存
							} else if (riqi >= current_date_range[0] && riqi <= current_date_range[1]) {
								// console.log(riqi.Format('MM'));
								switch(riqi.Format('MM')) //月份
								{
									case '01':
										j = 10;break;
									case '02':
										j = 11;break;
									case '03':
										j = 12;break;
									case '04':
										j = 1;break; // 注意0下标
									case '05':
										j = 2;break;
									case '06':
										j = 3;break;
									case '07':
										j = 4;break;
									case '08':
										j = 5;break;
									case '09':
										j = 6;break;
									case '10':
										j = 7;break;
									case '11':
										j = 8;break;
									case '12':
										j = 9;break;
									default:
									  
								}
							}
								
							// i为不良内容分类，j为月份
							_this.chart3_option_series_data[i][j] += v.shuliang;
							
							// 每月份的汇总
							_this.chart3_option_series_data_huizong[j] += v.shuliang;
							
							// 合计点数之和，用于计算总的PPM
							_this.chart3_option_series_data_hejidianshu[j] += v.hejidianshu;
							
						}
						
					});
					
					// ppm计算
					_this.chart3_option_series_data_huizong.map(function (v,i) {
						if (_this.chart3_option_series_data_hejidianshu[i] == 0) {
							_this.chart3_option_series_data_ppm[i] = 0;
						} else {
							_this.chart3_option_series_data_ppm[i] = (_this.chart3_option_series_data_huizong[i] / _this.chart3_option_series_data_hejidianshu[i] * 1000000).toFixed(2);
						}
					});
					
					_this.chart3_function();
			
				}
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},
		
		
		
		// upload
		handleUpload (file) {
			this.file = file;
			return false;
		},
		upload () {
			this.loadingStatus = true;
			
			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('smt.qcreport.qcreportimport') }}";
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
				console.log(response.data);
				alert(response.data);
			})
			.catch(function (error) {
				this.error(false, 'Error', error);
			})
			
			
			
			setTimeout(() => {
				this.file = null;
				this.loadingStatus = false;
				this.$Message.success('Success')
			}, 1500);
		},
		
		
		//
		onchangegongxu: function () {
			var _this = this;
			
			var saomiao = _this.saomiao;
			var gongxu = _this.gongxu;
			
			if (saomiao == '' || saomiao == undefined || gongxu == '' || gongxu == undefined ) {
				_this.dianmei = '';
				return false;
			}
			
			var url = "{{ route('smt.qcreport.getsaomiao') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					saomiao: saomiao,
					gongxu: gongxu
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.dianmei = response.data.dianmei;

				// 生产日报中的机种生产日期，暂保留，无用（返回但没用上）
				_this.shengchanriqi = response.data.shengchanriqi;
			})
			.catch(function (error) {
				this.error(false, 'Error', error);
			})
		},
		
		
		// 编辑前查看
		qcreport_edit: function (row) {
			var _this = this;
			
			_this.id_edit = row.id;
			_this.jizhongming_edit = row.jizhongming;
			_this.created_at_edit = row.created_at;
			_this.updated_at_edit = row.updated_at;
			_this.jianchajileixing_edit = row.jianchajileixing;
			_this.buliangneirong_edit = row.buliangneirong;
			_this.weihao_edit = row.weihao;
			_this.shuliang_edit[0] = row.shuliang;
			_this.shuliang_edit[1] = row.shuliang;
			_this.jianchazhe_edit = row.jianchazhe;
			_this.dianmei_edit = row.dianmei;
			_this.meishu_edit = row.meishu;
			_this.hejidianshu_edit = row.hejidianshu;
			_this.bushihejianshuheji_edit = row.bushihejianshuheji;
			_this.ppm_edit = row.ppm;

			_this.modal_qcreport_edit = true;
		},
		
		
		// 编辑后保存
		qcreport_edit_ok: function () {
			var _this = this;
			
			var id = _this.id_edit;
			var jizhongming = _this.jizhongming_edit;
			var created_at = _this.created_at_edit;
			var updated_at = _this.updated_at_edit;
			var jianchajileixing = _this.jianchajileixing_edit;
			var buliangneirong = _this.buliangneirong_edit;
			var weihao = _this.weihao_edit;
			var shuliang = _this.shuliang_edit;
			var jianchazhe = _this.jianchazhe_edit;
			var dianmei = _this.dianmei_edit;
			var meishu = _this.meishu_edit;
			var hejidianshu = _this.hejidianshu_edit;
			var bushihejianshuheji = _this.bushihejianshuheji_edit;
			var ppm = _this.ppm_edit;

			// 重新计算枚数、合计点数、不良件数合计和PPM
			hejidianshu = dianmei * meishu;
			bushihejianshuheji = bushihejianshuheji + shuliang[1] - shuliang[0];
			ppm = bushihejianshuheji / hejidianshu * 1000000;
			
			// console.log(buliangneirong);
			// return false;
			
			// 数量为0时，清空不良内容、位号和数量
			if (shuliang[1] == 0) {
				buliangneirong = '';
				weihao = '';
				shuliang[1] = '';
			} else if (buliangneirong == '' || buliangneirong == null || buliangneirong == undefined
				|| weihao == '' || weihao == null || weihao == undefined) {
				_this.warning(false, '警告', '[不良内容] 或 [位号] 不能为空！');
				return false;
			}
			
			var url = "{{ route('smt.qcreport.qcreportupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				jizhongming: jizhongming,
				created_at: created_at,
				updated_at: updated_at,
				jianchajileixing: jianchajileixing,
				buliangneirong: buliangneirong,
				weihao: weihao,
				shuliang: shuliang[1],
				jianchazhe: jianchazhe,
				meishu: meishu,
				hejidianshu: hejidianshu,
				bushihejianshuheji: bushihejianshuheji,
				ppm: ppm
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.qcreportgets(_this.pagecurrent, _this.pagelast);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.id_edit = '';
					_this.jizhongming_edit = '';
					_this.created_at_edit = '';
					_this.updated_at_edit = '';
					_this.jianchajileixing_edit = '';
					_this.buliangneirong_edit = '';
					_this.weihao_edit = '';
					_this.shuliang_edit = [0, 0];
					_this.jianchazhe_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请刷新查询条件后再试！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},

			
	},
	mounted: function () {
		var _this = this;
		_this.configgets();
		// _this.qcdate_filter = new Date().Format("yyyy-MM-dd");
		// _this.qcreportgets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection