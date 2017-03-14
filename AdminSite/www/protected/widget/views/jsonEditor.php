<style>
.title {
	font-size: 14px;
	background: url(/static/img/img2.gif) no-repeat;
	padding-left: 30px;
}

#ldh_ui_window {
	width: 840px;
	height: 520px;
	position: absolute;
	background: #fff url(/static/img/img3.gif) repeat-x 0 32px;
	border: 2px solid #4A84C4;
	border-top: none;
	left: 50%;
	margin-left: -420px;
	top: 50%;
	margin-top: -260px;
}

#ldh_ui_caption {
	height: 32px;
	line-height: 32px;
	text-indent: 1em;
	background: url(/static/img/img4.gif) repeat-x;
	text-align: center;
}

#ldh_ui_body_left {
	width: 300px;
	height: 450px;
	overflow: auto;
	position: absolute;
	left: 10px;
	top: 55px;
	border: 1px solid #4A84C4;
}

#editWindow {
	width: 500px;
	position: absolute;
	left: 325px;
	top: 55px;
	border: 1px solid #4A84C4;
}

#tree_cap {
	height: 18px;
	line-height: 18px;
	text-align: center;
	background: url(/static/img/img5.gif) repeat-x;
}
/* toolbar */
#subToolbar {
	height: 29px;
	background: url(/static/img/img6.gif) repeat-x 50% 50%;
	line-height: 29px;
	position: relative;
	width: 100%;
	text-indent: 10px;
}

#subToolbar a {
	color: #000;
	background: url(/static/img/img7.gif) no-repeat 0% 50%;
	padding-left: 20px;
	text-decoration: none;
}

#subToolbar span {
	position: absolute;
	right: 10px;
	bottom: 4px;
}

#subToolbar a:hover {
	color: #fff;
	background-image: url(/static/img/format.gif);
	text-decoration: underline;
}

#subToolbar a#save_as {
	background-image: url(/static/img/img9.gif);
}

#subToolbar a#format_indent {
	background-image: url(/static/img/img8.gif);
}

#subToolbar a#update {
	background-image: url(/static/img/img10.gif);
}

#subToolbar a#format_compress {
	background-image: url(/static/img/img11.gif);
}

#subToolbar a#clear_txt {
	background-image: url(/static/img/img12.gif);
}
/* end */
#json_eidit {
	width: 497px;
	border: none;
	margin: 0px;
	color: #000;
	height: 340px;
	font-size: 14px;
}

.json_editInfo {
	line-height: 160%;
	border: 1px solid #4A84C4;
	width: 483px; +
	width: 500px;
	height: 65px;
	color: #003300;
	position: absolute;
	left: 325px;
	bottom: 12px;
}

#json_editInfo {
	height: 48px;
	padding-left: 20px;
	padding-top: 10px;
	background: url(/static/img/img13.gif) no-repeat 0px 12px;
}

#json_editInfo b {
	color: red
}

#ldh_ui_window .err {
	color: red;
	background-image: url(/static/img/img14.gif);
}

#ldh_ui_window .busy {
	color: #333;
	background-image: url(/static/img/img15.gif);
}

#ldh_ui_window .info {
	color: #006600;
	background-image: url(/static/img/img16.gif);
}

#json_editInfo input {
	width: 50px;
	border: 1px solid #4A84C4;
	height: 14px;
}

#json_editInfo  button {
	background: url(/static/img/img17.gif) no-repeat;
	width: 42px;
	height: 20px;
	line-height: 20px;
	text-align: center;
	border: none;
	color: #114060;
	letter-spacing: 0px;
	margin-left: 0px;
}
/* for tree */
img,input,select {
	vertical-align: middle
}

#ldh_ui_window #tree {
	white-space: nowrap;
	font-size: 12px;
	line-height: 24px;
}

#tree a {
	text-decoration: none;
	color: #333;
}

#tree a:hover,#tree a.hot {
	color: #000;
	background: #0B92FF;
}

dd,dl,dt {
	padding: 0;
	margin: 0;
	border: none;
	font-size: 12px;
}

dt img {
	vertical-align: middle;
}

dt {
	height: 20px;
	white-space: nowrap;
}
legend {
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: -moz-use-text-color -moz-use-text-color #e5e5e5;
    border-image: none;
    border-style: none none solid;
    border-width: 0 0 0px;
    color: #333333;
    display: block;
    font-size: 14px;
    line-height: 1em;
    margin-bottom: 0;
    padding: 0;
    width: auto;
}
#ldh_ui_caption > input{
	float: right;
}
</style>
<div id="json_Edit_Div" style="display:none;">
	<div id="ldh_ui_window" style="z-index:100;">
		<div id="ldh_ui_caption">
			<strong class="title">JSON配置内容编辑器</strong>
			<input type="button" id="btnSaveCfg" value="保存配置" onclick="saveJsonEditor();">
			<input type="button" id="btnCancelCfg" value="放弃修改" onclick="cancelJsonEditor();">
		</div>
		<div id="ldh_ui_body_left">
			<div id="tree_cap">树视图</div>
			<div id="tree"></div>
		</div>
		<div id="editWindow">
			<div id="subToolbar">
				<a href="javascript:void(0)" title="" id="format_indent">缩进</a> | <a
					href="javascript:void(0)" title="" id="format_compress">紧凑</a> | <a
					href="javascript:void(0)" title="" id="update">刷新视图</a> | <a
					href="javascript:void(0)" title="" id="clear_txt">清空</a> | <a
					href="javascript:void(0)" title="" id="save_as">另存为</a> <span> <label
					for="autoUpdate"><input type="checkbox" id="autoUpdate" checked />
						同步更新树视图</label>
				</span>
			</div>
			<div id="edit">
				<textarea id="json_eidit"></textarea>
			</div>
		</div>
		<fieldset class="json_editInfo">
			<legend>消息</legend>
			<div id="json_editInfo" class="busy">请点击刷新视图验证Json格式</div>
		</fieldset>
	</div>	
</div>

<div id="mask" class="mask" style="display:none;position:fixed;left:0;top:0;width:100%;height:100%;z-index:10;background-color:rgba(0,0,0,0.5);"></div>

<script type="text/javascript">
function showJsonEditor(pvData)
{
   $("#json_eidit").val(pvData);
   $("#json_Edit_Div").show();
   $("#mask").show();
}
function cancelJsonEditor()
{
   $("#json_Edit_Div").hide();
   $("#mask").hide();
}
function saveJsonEditor()
{
	var saveTxt=JE.format($("#json_eidit").val(),true);
	$("#config_value").val(saveTxt);
	$("#json_Edit_Div").hide();
	$("#mask").hide();	
}
</script>

<script>
JE={

	data:{},/* 关联数据 */
	code:false,/* 格式化后的代码 */
	oldCode:[],/* 历史代码 */
	editUI:null,/* 关联编辑框 */
	msgUI:null,/* 信息显示窗口 */
	treeUI:null,/* 树窗口 */
	name:'JE',/* 实例名 */
	root:'<b>JSON配置</b>',/* 根节点标题 */
	checkbox:0,/* 是否添加复框 */
	hot:null,/* 选中节点 */
	indent:'    ',/*缩进符'\t'或者4个' '*/
	firstUp:true,/*第一次自动刷新*/
	onclick:Array,/*树点击通知*/
	countInfo:'',/*统计信息*/
	formating:false,/* 格式化中(禁止重构树) */

	ico:{/* 树图标 */
		//文件夹结构线
		r0:'/static/img/img18.gif',
		r0c:'/static/img/img19.gif',
		r1:'/static/img/img20.gif',
		r1c:'/static/img/img21.gif',
		r2:'/static/img/img22.gif',
		r2c:'/static/img/img23.gif',
		//前缀图片
		nl:'/static/img/img24.gif',
		vl:'/static/img/img25.gif',
		//文件结构线
		f1:'/static/img/img26.gif',
		f2:'/static/img/img27.gif',
		root:'/static/img/img28.gif',
		//文件夹
		arr:'/static/img/img29.gif',
		arrc:'/static/img/img30.gif',
		obj:'/static/img/img31.gif',
		objc:'/static/img/img32.gif',
		//文件
		arr2:'/static/img/img33.gif',
		obj2:'/static/img/img34.gif'
	},

	toTree:function(){/* JSON转换为树HTML,同时格式化代码 */
		var draw=[],This=this,ico=this.ico;
		JE.firstUp=false;/*完成首次自动构造*/
			//prefix/*前缀HTML*/,lastParent/*父是否是尾节点(确定图标是空白|结构线)*/,name/*节点名*/,value/*节点值*/,formObj/* 父是否是对象(确定子图标) */){/* 构造子节点 */
		var notify=function(prefix,lastParent,name,value,formObj){
			var rl=prefix==''?ico.r0:lastParent?ico.r1:ico.r2;/* 配置根节点图标 */
			if(value&&value.constructor==Array){/* 处理数组节点 */
				draw.push('<dl><dt>',This.draw(prefix,rl,ico.arr,name,''),'</dt><dd>');/* 绘制文件夹 */
				for (var i=0;i<value.length;i++)
					notify(prefix+This.img(lastParent?ico.nl:ico.vl),i==value.length-1,i,value[i]);
				draw.push('</dd></dl>');
			}else	if(value&&typeof value=='object'){/* 处理对象节点 */
					draw.push('<dl><dt>',This.draw(prefix,rl,ico.obj,name,''),'</dt><dd>');/* 绘制文件夹 */
					var len=0,i=0;
					for(var key in value)len++;/* 获取对象成员总数 */
					for(var key in value)notify(prefix+This.img(lastParent?ico.nl:ico.vl),++i==len,key,value[key],true);
					draw.push('</dd></dl>');
				}else{/* 处理叶节点(绘制文件) */
					draw.push('<dl><dt>',This.draw(prefix,lastParent?ico.f1:ico.f2,formObj?ico.obj2:ico.arr2,name,value),'</dt></dl>');
				};
		};/* 不是[]或者{}不绘制 */
		if(typeof this.data=='object'){notify('',true,this.root,this.data);};
		if(this.treeUI)this.treeUI.innerHTML=draw.join('');/* 显示在树窗口 */
		this.msg('成功构造树视图!');
	},

	draw:function(prevfix,line,ico,name,value){/* 子项HTML构造器 */
		var cmd=false,J=this.ico,imgName=false;
		switch (line)	{//传递切换图标
			case J.r0:imgName='r0';break;
			case J.r1:imgName='r1';break;
			case J.r2:imgName='r2';
		}
		if(imgName)cmd=' onclick="'+this.name+'.show(this,\''+imgName+'\')" ';/* 加入折叠命令 */
		var type=typeof name=='string'?'(对象成员)':'(数组索引)';
		var theHtml= prevfix+this.img(line,cmd)
			+(this.checkbox?'<input type="checkbox" onclick="'+this.name+'.select(this)" />':'')
			+this.img(ico)+' <a href="javascript:void(0)" onclick="'+this.name+'.click(this);" '
			+'key="'+name+'" val="'+value+'" >'
			+name+type+(value==''?'':' = ')+value+'</a>'
		return theHtml;
	},
	img:function(src,attr){/* img HTML构造 */
		return '<img src="'+src+'" '+(attr||'')+'  />';
	},
	click:function(item){/* 子项点击统一回调 */
		if(this.hot)this.hot.className='';
		this.hot=item;
		this.hot.className='hot';/* 切换选中项 */
		this.onclick(item);
	},

	format:function(txt,compress/*是否为压缩模式*/){/* 格式化JSON源码(对象转换为JSON文本) */
		if(/^\s*$/.test(txt))return this.msg('数据为空,无法格式化! ');
		try{var data=eval('('+txt+')');}
		catch(e){
			JE.editUI.style.color='red';
			return this.msg('数据源语法错误,格式化失败! 错误信息: '+e.description,'err');
		};
		JE.editUI.style.color='#000';
		var draw=[],last=false,This=this,line=compress?'':'\n',nodeCount=0,maxDepth=0;
		var notify=function(name,value,isLast,indent/*缩进*/,formObj){
			nodeCount++;/*节点计数*/
			for (var i=0,tab='';i<indent;i++ )tab+=This.indent;/* 缩进HTML */
			tab=compress?'':tab;/*压缩模式忽略缩进*/
			maxDepth=++indent;/*缩进递增并记录*/
			if(value&&value.constructor==Array){/*处理数组*/
				draw.push(tab+(formObj?('"'+name+'":'):'')+'['+line);/*缩进'[' 然后换行*/
				for (var i=0;i<value.length;i++)
					notify(i,value[i],i==value.length-1,indent,false);
				draw.push(tab+']'+(isLast?line:(','+line)));/*缩进']'换行,若非尾元素则添加逗号*/
			}else	if(value&&typeof value=='object'){/*处理对象*/
					draw.push(tab+(formObj?('"'+name+'":'):'')+'{'+line);/*缩进'{' 然后换行*/
					var len=0,i=0;
					for(var key in value)len++;
					for(var key in value)notify(key,value[key],++i==len,indent,true);
					draw.push(tab+'}'+(isLast?line:(','+line)));/*缩进'}'换行,若非尾元素则添加逗号*/
				}else{
						if(typeof value=='string')value='"'+value+'"';
						draw.push(tab+(formObj?('"'+name+'":'):'')+value+(isLast?'':',')+line);
				};
		};
		var isLast=true,indent=0;
		notify('',data,isLast,indent,false);
		this.countInfo='共处理节点<b>'+nodeCount+'</b>个,最大树深为<b>'+maxDepth+'</b>';
		return draw.join('');
	},
	msg:function(text,type){/* 编辑状态或者错误通知 */
		if(!this.msgUI)return alert(text);
		//with(new Date)var now=([getHours(),getMinutes(),getSeconds()].join(':')).replace(/\b\d\b/g,'0$&');
		//this.msgUI.innerHTML='<div>['+now+'] &nbsp;&nbsp;'+text.replace(/\n/g,'<br/>')+'</div>';
		this.msgUI.innerHTML='<div>'+text.replace(/\n/g,'<br/>')+'</div>';
		this.msgUI.className=type;
	},
	show:function (ico,id){/* 显隐树节点 */
		var subView=ico.parentNode.parentNode.childNodes[1].style,J=this.ico;
		if(subView.display=='none'){
			subView.display='';
			 ico.src=J[id];
		}else{
			subView.display='none';
			 ico.src=J[id+'c'];
		};
	},
	select:function (sender){
		var sub=sender.parentNode.parentNode.getElementsByTagName("INPUT");
		for (var i=0;i<sub.length;i++ ) {sub[i].checked=sender.checked;}
	}
};

JE.add=function(){
	this.msg('功能添加中...*_^');
}

JE.editItem=function(){
}

JE.begin=function(){/* 设置UI控件关联响应 */
	var $=function (id){return document.getElementById(id)};
	/* 关联UI */
	JE.editUI=$("json_eidit");
	JE.msgUI=$("json_editInfo");
	JE.treeUI=$("tree");
	var updateUI=$("update");
	var auto=$("autoUpdate");
	var fontSize=$("fontSize");
	/* 单击树子项 */
	JE.onclick=function(item){
		var key='键名: <input value="'+item.getAttribute('key')+'" />',
			val='  键值: '+'<input value="'+item.getAttribute('val')+'" style="width:285px;" id="txtValueInput"/>',
			add='<button onclick="'+this.name+'.add(this)">新增</button>',
			edit='<button onclick="'+this.name+'.editItem(this)">修改</button>';
		JE.msg(key+val,'info');
	}
	/* 监听代码变化事件 */
	JE.editUI.oninput=JE.editUI.onpropertychange=function (){
		if(JE.formating)return;/* 格式化不刷新树 */
		if(/^\s*$/.test(this.value))return  JE.msg('请输入JSON格式的代码!');;
		clearTimeout(JE.update);
		try{JE.data=eval('('+this.value+')');	
		}catch(e){
			JE.editUI.style.color='red';
			return JE.msg("源代码有错误: "+e.description+' , 如果正在编辑中, 请忽略此消息!','err');
		};
		JE.editUI.style.color='#000';
		if(auto.checked||JE.firstUp){/*若同步*/
			JE.msg('语法正确,正在重新构造树,请稍候...','busy');
			JE.update=setTimeout(function(){
				JE.toTree();
			},450);
		}else{
			JE.msg('语法正确,请点击刷新,或者打开视图同步开关,或者继续编辑!')
		}
		return true;
	};
	if(window.ActiveXObject)
		document.execCommand("BackgroundImageCache", false, true);
	/* 拦截Tab,自动格式化 */
	JE.editUI.onkeydown=function (){
		if(event.keyCode==9){$('format_indent').onclick();event.returnValue=false;};
		JE.code=this.value;
	}
	/* 格式化 */
	var format=function(compress){
		var code=JE.format(JE.editUI.value,compress);
		JE.formating=true;
		if(code)JE.editUI.value=code;
		JE.editUI.focus();
		setTimeout(function(){JE.formating=false;},1000);
		return code;
	}
	/* 工具栏按钮 */
	$('format_indent').onclick=function (){if(format())JE.msg('完成缩进风格转换,'+JE.countInfo)}
	$('format_compress').onclick=function (){if(format(true)!=undefined)JE.msg('完成紧凑风格转换,'+JE.countInfo);}
	updateUI.onclick=function (){
		JE.firstUp=true;
		JE.editUI.onpropertychange()?JE.msg('成功刷新视图!'):JE.msg('数据有误,刷新失败!','err')
		JE.firstUp=false;
	};
	$('clear_txt').onclick=function (){JE.editUI.value=JE.treeUI.innerHTML='';JE.editUI.focus();}
	auto.onclick=function (){JE.msg('自动同步视图功能'+(this.checked?'开启':'关闭!'));};
	/* 另存为 */
	if(/*@cc_on !@*/true){$('save_as').style.display='none'};
	$('save_as').onclick=function (){
		var d=document,w=d.createElement('IFRAME');
		w.style.display="none";
		d.body.appendChild(w);
		setTimeout(function(){
			var g=w.contentWindow.document;
			g.charset = 'utf-8';
			g.body.innerHTML=JE.editUI.value;
			g.execCommand("saveas",'', "json.txt") ;
		},1);
	}
};

window.onload=function (){
	JE.begin();
}
</script>