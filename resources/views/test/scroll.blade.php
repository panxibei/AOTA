<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test iview scroll</title>
	<link rel="stylesheet" href="{{ asset('statics/iview/styles/iview.css') }}">

<style type="text/css">


.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}
.layout-header-bar{
	background: #fff;
	box-shadow: 0 1px 1px rgba(0,0,0,.1);
}
.layout-logo{
    width: 100px;
    height: 30px;
    <!--background: #5b6270;-->
    border-radius: 3px;
    float: left;
    position: relative;
    top: 15px;
    left: 20px;
}
.layout-breadcrumb{
	<!-- padding: 10px 15px 0; -->
    width: 100px;
    height: 30px;
    <!--background: #5b6270;-->
    border-radius: 3px;
    float: left;
    position: relative;
    top: 5px;
    left: 20px;
}
.layout-nav{
    width: 420px;
    margin: 0 auto;
    margin-right: 120px;
}
.layout-footer-center{
    text-align: center;
}
.ivu-table-cell{
	font-size: 12px;
}

</style>
	
</head>
<body>
<div id="app">

    <div class="layout">
        <Layout>
			<Layout>
            <!--头部导航-->
			<div style="z-index: 999;">
            <Header :style="{position: 'fixed', width: '100%', marginLeft: '200px'}">
                <Layout>
				<i-menu mode="horizontal" theme="light" active-name="1">
                    <!--<div class="layout-logo">qqqqqqqqqqqq</div>-->
					
					<!--面包屑-->
					<div class="layout-breadcrumb">
						<Breadcrumb>
							<Breadcrumb-item href="#">首页</Breadcrumb-item>
							<Breadcrumb-item href="#">应用中心</Breadcrumb-item>
							<Breadcrumb-item>某应用</Breadcrumb-item>
						</Breadcrumb>
					</div>
					
					
                    <div class="layout-nav">
                        <Menu-item name="1">
                            <Icon type="ios-navigate" size="24"></Icon>
                            <!--Item 1-->
                        </Menu-item>
                        <Menu-item name="2">
                            <Icon type="ios-keypad" size="24"></Icon>
                            <!--Item 2-->
                        </Menu-item>
                        <Menu-item name="3">
                            <Icon type="ios-analytics" size="24"></Icon>
                            <!--Item 3-->
                        </Menu-item>
                        <Menu-item name="4">
                            <Icon type="ios-paper" size="24"></Icon>
                            <!--Item 4-->
                        </Menu-item>
                        <Menu-item name="5">
                            <Icon type="person" size="24"></Icon>
                            <!--Item 5-->
                        </Menu-item>
                    </div>
                </i-menu>
				</Layout>
				<!--头部标签组-->
				<Layout :style="{padding: '0 2px', marginLeft: '20px'}">
					<div>
						<Tag type="dot">标签一</Tag>
						<Tag type="dot">标签二</Tag>
						<Tag type="dot" closable>标签三</Tag>
						<Tag type="dot" closable>标签三</Tag>
						<Tag v-if="show" @on-close="handleClose" type="dot" closable color="blue">可关闭标签</Tag>
					</div>
				</Layout>
            </Header>
			</div>
			</Layout>

            <Layout>
                <!--左侧导航菜单-->
				<Sider hide-trigger :style="{background: '#fff', position: 'fixed', height: '100vh', left: 0, overflow: 'auto'}">
					<div style="height: 60px;">
						<div class="layout-logo">xzWorkflow 2018</div>
					</div>
					<i-menu active-name="2-3" theme="light" width="auto" :open-names="['2']">
                        <Submenu name="1">
                            <template slot="title">
                                <Icon type="ios-navigate"></Icon>
                                网站首页
                            </template>
                            <Menu-item name="1-1">Option 1</Menu-item>
                            <Menu-item name="1-2">Option 2</Menu-item>
                            <Menu-item name="1-3">Option 3</Menu-item>
                        </Submenu>
                        <Submenu name="2">
                            <template slot="title">
                                <Icon type="ios-loop-strong"></Icon>
								Circulation
                            </template>
                            <Menu-item name="2-1"><Icon type="document-text"></Icon>Circulation</Menu-item>
                            <Menu-item name="2-2"><Icon type="edit"></Icon>ToDo</Menu-item>
                            <Menu-item name="2-3"><Icon type="archive"></Icon>Archives</Menu-item>
                        </Submenu>
                        <Submenu name="3">
                            <template slot="title">
                                <Icon type="ios-analytics"></Icon>
                                Item 3
                            </template>
                            <Menu-item name="3-1">Option 1</Menu-item>
                            <Menu-item name="3-2">Option 2</Menu-item>
                        </Submenu>
                    </i-menu>
                </Sider>
			</Layout>
			
				<div>
				<br>
				<br>
				<br>
				<br>
				</div>
			<Layout :style="{padding: '0 24px 24px', marginLeft: '200px'}">
				
				
				
				<Content :style="{padding: '24px 24px', minHeight: '280px', background: '#fff'}">
					11111111111111111111111111111111111111111
					<br>222222222222222222222222222222
					<br>33333333333333333333333333
					<br>44444444444444444444
					<br>55555555555555
					<br>6666666666666666666666

					<br><br>
					
					<br>
					
					<br><br>7.表格
					<br>
					<i-table height="200" size="small" border :columns="columns5" :data="data5"></i-table>
					<br>
					
					
					<br>
					<br>
					<br>

					<Scroll :on-reach-bottom="handleReachBottom" distance-to-edge=5 height="200">
<!-- 						<Card dis-hover v-for="(item, index) in list1" :key="index" style="margin: 32px 0">
							Content @{{ item }}
						</Card>
 -->
                        <Collapse v-for="(item, index) in list_log">
                            <Panel name="@{{ index }}">
                                @{{ item.label }}
                                <p slot="content">
                                @{{ item.value }}
                                </p>
                            </Panel>
                        </Collapse>
					</Scroll>


					<br>bottom bottom bottom
				</Content>
			</Layout>
            <!-- </Layout> -->
			<Footer class="layout-footer-center">2011-2016 &copy; TalkingData</Footer>
        </Layout>
    </div>



	
</div>
</body>
<!--<script type="text/javascript" src="./vue.min.js"></script>
<script type="text/javascript" src="./dist/iview.js"></script>-->
<script src="{{ asset('js/vue.min.js') }}"></script>
<!-- 引入组件库 -->
<script src="{{ asset('statics/iview/iview.min.js') }}"></script>

<script type="text/javascript">
var vm_app = new Vue({
	el: '#app',
	data: {
		formItem: {
			input: '',
		},
	
		test: 'fadfadf',
		value: [20, 50],
		show: true,
		value3: 'xxx',
		animal: '印度黑羚',
		fruit: [],
		switch1: false,
		columns5: [
			{
				title: 'Date',
				key: 'date',
				sortable: true
			},
			{
				title: 'Name',
				key: 'name',
				render: (h, params) => {
					return h('div', [
						h('Icon', {
							props: {
								type: 'person'
							}
						}),
						h('strong', params.row.name)
					]);
				}
			},
			{
				title: 'Age',
				key: 'age',
				sortable: true
			},
			{
				title: 'Address',
				key: 'address',
			},
			{
				title: 'Action',
				key: 'action',
				align: 'center',
				render: (h, params) => {
					return h('div', [
						h('Button', {
							props: {
								type: 'primary',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.showperson(params.index)
								}
							}
						}, 'View'),
						h('Button', {
							props: {
								type: 'error',
								size: 'small'
							},
							on: {
								click: () => {
									vm_app.removeperson(params.index)
								}
							}
						}, 'Delete')
					]);
				}
			}
		],
		data5: [
			{
				name: 'John Brown',
				age: 18,
				address: 'New York No. 1 Lake Park',
				date: '2016-10-03'
			},
			{
				name: 'Jim Green',
				age: 24,
				address: 'London No. 1 Lake Park',
				date: '2016-10-01'
			},
			{
				name: 'Joe Black',
				age: 30,
				address: 'Sydney No. 1 Lake Park',
				date: '2016-10-02'
			},
			{
				name: 'Joe Black1',
				age: 30,
				address: 'Sydney No. 1 Lake Park',
				date: '2016-10-02'
			},
			{
				name: 'Joe Black2',
				age: 30,
				address: 'Sydney No. 1 Lake Park',
				date: '2016-10-02'
			},
			{
				name: 'Joe Black3',
				age: 30,
				address: 'Sydney No. 1 Lake Park',
				date: '2016-10-02'
			},
			{
				name: 'Jon Snow',
				age: 26,
				address: 'Ottawa No. 2 Lake Park',
				date: '2016-10-04'
			}
		],
		cityList: [
			{
				value: 'New York',
				label: 'New York'
			},
			{
				value: 'London',
				label: 'London'
			},
			{
				value: 'Sydney',
				label: 'Sydney'
			},
			{
				value: 'Ottawa',
				label: 'Ottawa'
			},
			{
				value: 'Paris',
				label: 'Paris'
			},
			{
				value: 'Canberra',
				label: 'Canberra'
			}
		],
		model2: '',
		value_slider: [20, 50],
		datepicker1: '',
		datepicker2: '',
		timepicker1: '',
		valuecascader: [],
		datacascader: [{
			value: 'beijing',
			label: '北京',
			children: [
				{
					value: 'gugong',
					label: '故宫'
				},
				{
					value: 'tiantan',
					label: '天坛'
				},
				{
					value: 'wangfujing',
					label: '王府井'
				}
			]
		}, {
			value: 'jiangsu',
			label: '江苏',
			children: [
				{
					value: 'nanjing',
					label: '南京',
					children: [
						{
							value: 'fuzimiao',
							label: '夫子庙',
						}
					]
				},
				{
					value: 'suzhou',
					label: '苏州',
					children: [
						{
							value: 'zhuozhengyuan',
							label: '拙政园',
						},
						{
							value: 'shizilin',
							label: '狮子林',
						}
					]
				}
			],
		}],
		datatransfer: [],
		targetKeystransfer: [],
		//	this.getTargetKeys()
		//targetKeystransfer: this.getTargetKeys()
		valueinputnumber: 88,
		valuerate: 1,
		color1: '#19be6b',
		color2: '',
		formInline: {
			user: '',
			password: ''
		},
		ruleInline: {
			user: [
				{ required: true, message: 'Please fill in the user name', trigger: 'blur' }
			],
			password: [
				{ required: true, message: 'Please fill in the password.', trigger: 'blur' },
				{ type: 'string', min: 6, message: 'The password length cannot be less than 6 bits', trigger: 'blur' }
			]
		},
		valuecollapse: 'c2',
		treedata: [
			{
				title: 'parent 1',
				expand: true,
				children: [
					{
						title: 'parent 1-1',
						expand: true,
						children: [
							{
								title: 'leaf 1-1-1'
							},
							{
								title: 'leaf 1-1-2'
							}
						]
					},
					{
						title: 'parent 1-2',
						expand: true,
						children: [
							{
								title: 'leaf 1-2-1'
							},
							{
								title: 'leaf 1-2-1'
							}
						]
					}
				]
			}
		],
		pagecurrent: 2,
		pagetotal: 199,
		pagepagesize: 20,
		stepcurrent: 0,
		spinShow: true,

		list1: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        list_log: [
            {
                label: '拙政园1',
                value: '<p>zhuozhengyuan</p><p>fsadfasfs</p>',
            },
            {
                label: '拙政园2',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园3',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园4',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园5',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园6',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园7',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园8',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园9',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园10',
                value: 'zhuozhengyuan',
            },
        ],


	},
	methods: {
		handleClose: function () {
			this.show = false;
		},
		mylabel: function (h) {
			return h('div', [
				h('span', '标签一'),
				h('Badge', {
					props: {
						count: 3
					}
				})
			])
		},
		switchchange: function (status) {
			this.$Message.info('开关状态：' + status);
		},
		showperson: function (index) {
			alert(index);
			this.$Modal.info({
				title: 'User Info',
				content: `Date：${this.data5[index].date}<br>Name：${this.data5[index].name}<br>Age：${this.data5[index].age}<br>Address：${this.data5[index].address}`
			})
		},
		removeperson: function (index) {
			alert(index);
		},
		datepickerchange: function () {
			//alert(this.datepicker1.Format("yyyy-MM-dd hh:mm:ss.S"));
			alert(this.datepicker1.Format("yyyy-MM-dd hh:mm:ss"));
			
		},
		getMockData: function () {
			let mockData = [];
			for (let i = 1; i <= 20; i++) {
				mockData.push({
					key: i.toString(),
					label: 'Content ' + i,
					description: 'The desc of content  ' + i,
					disabled: Math.random() * 3 < 1
				});
			}
			return mockData;
		},
		getTargetKeys: function () {
			return this.getMockData()
					.filter(() => Math.random() * 2 > 1)
					//.map(item => item.key);
					.map(function (item) {return item.key});
		},
		render1: function (item) {
			return item.label;
		},
		handleChange1: function (newTargetKeys, direction, moveKeys) {
			console.log(newTargetKeys);
			console.log(direction);
			console.log(moveKeys);
			this.targetKeystransfer = newTargetKeys;
		},
		handleSubmit(name) {
			this.$refs[name].validate((valid) => {
				if (valid) {
					this.$Message.success('Success!');
				} else {
					this.$Message.error('Fail!');
				}
			})
		},
		handleReset (name) {
			this.$refs[name].resetFields();
		},
		loading () {
			const msg = this.$Message.loading({
				content: 'Loading... 3秒后会自动关闭。当然你可以点击按钮关闭我。',
				duration: 0
			});
			setTimeout(msg, 3000);
		},
		poptipok () {
			this.$Message.info('You click ok');
		},
		poptipcancel () {
			this.$Message.info('You click cancel');
		},
		stepnext () {
			if (this.stepcurrent == 3) {
				this.stepcurrent = 0;
			} else {
				this.stepcurrent += 1;
			}
		},
		loadingbarstart () {
			this.$Loading.start();
		},
		loadingbarfinish () {
			this.$Loading.finish();
		},
		loadingbarerror () {
			this.$Loading.error();
		},


		handleReachBottom () {
			return new Promise(resolve => {
				setTimeout(() => {
					const last = this.list1[this.list1.length - 1];
					for (let i = 1; i < 21; i++) {
						this.list1.push(last + i);
					}
					resolve();
				}, 2000);
			});
		},



	},
	mounted: function () {
		this.datatransfer = this.getMockData();
		this.targetKeystransfer = this.getTargetKeys();
	}
})
</script>
<script>
// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
// let time1 = new Date().Format("yyyy-MM-dd");
// let time2 = new Date().Format("yyyy-MM-dd HH:mm:ss");
 
Date.prototype.Format = function (fmt) { //author: meizz
    let o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (let k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};
</script>
</html>