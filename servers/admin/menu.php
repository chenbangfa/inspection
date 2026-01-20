<?php
if(!isset($_SESSION["hyId"]))
{
	echo "<scr"."ipt>location.href='login.php'</scr"."ipt>";
	exit();
}
?>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#treeview {
	width: 200px;
}
#treeview ul {
	list-style-type: none;
	margin: 0px;
	padding: 0px 0px 0px 12px;
}
#treeview ul li {
	margin: 0px;
	padding: 0px 0px 0px 0px;
	line-height: 20px;
	width: auto;
	clear: both;
}
#treeview div {
	padding: 0px;
	margin: 0px;
}
#treeview h1 {
	margin: 0px;
	padding: 0px;
	font-size: 14px;
	font-weight: normal;
	line-height: 30px;
	background: url(image/dirico.gif) no-repeat left;
	padding-left: 16px;
	float: left;
	color: #fff;
}
#treeview .opendir {
	background: url(image/openico.gif) no-repeat left;
	margin-top: 4px;
}
#treeview .closedir {
	background: url(image/closeico.gif) no-repeat left;
	margin-top: 4px;
}
#treeview .opendir, .closedir {
	width: 12px;
	height: 20px;
	float: left;
	cursor: pointer;
}
#treeview .nodir {
	width: 12px;
	height: 20px;
	float: left;
}
#treeview .none {
	display: none;
}
-->
</style>
<meta charset="utf-8">
<script src="../js/pub.js?d=2"></script>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background:#fff; z-index:2">
  <div class="container-fluid">
    <div class="navbar-header"> <a class="navbar-brand" href="index.php" style="margin-left:-20px">
      <div style="width:100%; background-color:#fff;"><img src="image/bglogo.png" style="height:50px"/></div>
      </a>
      <ul class="user-menu">
        <li class="dropdown pull-right" style="text-align:center;"><a href="login.php" style="color:#5f6468"> <em class="glyphicon glyphicon-off glyphicon-l" style="font-size:2em;"></em>
          <div>退出登陆</div>
          </a> </li>
        <li class="dropdown pull-right" style="text-align:center; margin-right:20px"><a href="updatepwd.php" style="color:#5f6468"> <em class="glyphicon glyphicon-cog glyphicon-l" style="font-size:2em;"></em>
          <div>修改密码</div>
          </a> </li>
        <li class="dropdown pull-right" style="text-align:center; margin-right:20px"><a href="javascript:void(0);" onclick="return recookie();" style="color:#5f6468"> <em class="glyphicon glyphicon-time glyphicon-l" style="font-size:2em;"></em>
          <div>清理菜单缓存</div>
          </a> </li>
          <li class="dropdown pull-right" style="text-align:center; margin-right:20px"><a href="" style="color:#5f6468"> <em class="glyphicon glyphicon-th-list glyphicon-l" style="font-size:2em;"></em>
          <div>切换团队</div>
          </a> </li>
        <li class="dropdown pull-right" style="text-align:center; margin-right:20px"><em class="glyphicon glyphicon-user glyphicon-l" style="font-size:2em;"></em>
          <div><?php echo $db->getTname($_SESSION["tId"]);?></div>
        </li>
        </li>
      </ul>
    </div>
  </div>
  <!-- /.container-fluid --> 
</nav>
<div style="width:190px; height:100%; position:fixed; background-color:#252423; top:80px; padding-bottom:50px" id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
<div id="treeview">
<ul>
 <li id="depth_1_item_1">
    <div class="closedir" id="depth_1_item_1_ico" onclick="controlNode(this,'depth_1_item_1')"></div>
    <h1>用户管理</h1>
    <div id="depth_1_item_1_layer" style="display:none;">
      <ul>
        <li id="depth_2_item_1_1">
          <div class="nodir"></div>
          <h1><a href="index.php">数据概况</a></h1>
        </li>
        <?php if($_SESSION["hyTel"]=="18608518772"){?>
        <li id="depth_2_item_1_2">
          <div class="nodir"></div>
          <h1><a href="hyteam.php">团队管理</a></h1>
        </li>
        <?php }?>
        <li id="depth_2_item_1_3">
          <div class="nodir"></div>
          <h1><a href="hylist.php">用户管理</a></h1>
        </li>
      </ul>
    </div>
  </li>

  <li id="depth_1_item_2">
    <div class="closedir" id="depth_1_item_2_ico" onclick="controlNode(this,'depth_1_item_2')"></div>
    <h1>签到打卡</h1>
    <div id="depth_1_item_2_layer" style="display:none;">
      <ul>
        <li id="depth_2_item_2_1">
          <div class="nodir"></div>
          <h1><a href="inspect.php">签到记录</a></h1>
        </li>
        <li id="depth_2_item_2_2">
          <div class="nodir"></div>
          <h1><a href="inspectgroup.php">签到分组</a></h1>
        </li>
      </ul>
    </div>
  </li>
 
  <li id="depth_1_item_3">
    <div class="closedir" id="depth_1_item_3_ico" onclick="controlNode(this,'depth_1_item_3')"></div>
    <h1>巡检点管理</h1>
    <div id="depth_1_item_3_layer" style="display:none;">
      <ul>
        <li id="depth_2_item_3_1">
          <div class="nodir"></div>
          <h1><a href="bluegroup.php">巡检点分类</a></h1>
        </li>
        <li id="depth_2_item_3_2">
          <div class="nodir"></div>
          <h1><a href="bluelist.php">所有巡检点</a></h1>
        </li>
      </ul>
    </div>
  </li>
  <li id="depth_1_item_4">
    <div class="closedir" id="depth_1_item_4_ico" onclick="controlNode(this,'depth_1_item_4')"></div>
    <h1>巡检统计</h1>
    <div id="depth_1_item_4_layer" style="display:none;">
      <ul>
        <li id="depth_2_item_4_1">
          <div class="nodir"></div>
          <h1><a href="blueorder.php">巡检记录</a></h1>
        </li>
        <li id="depth_2_item_4_2">
          <div class="nodir"></div>
          <h1><a href="bluetimeout.php">超时未检</a></h1>
        </li>
      </ul>
    </div>
  </li>
  
</ul>

</div>
</div>
<script>
$(document).ready(function()
{ 
	

	var m=getCookie("menu");
	$(".clickm_"+m).addClass("xzb");
	
	
	$(document).on('click','.getm',function (e)		
	{
		var m=$(this).attr("m");
		setCookie("menu",m);
	});
});
//这是有设定过期时间的使用示例：
//s20是代表20秒
//h是指小时，如12小时则是：h12
//d是天数，30天则：d30
</script> 
<script type="text/javascript" language="javascript">
defaultNodeState();

function recookie()
{
	 var nodeState = ",|,|,";
	 setCookie("nodeState",nodeState);
	 location.reload();
}
 
function defaultNodeState()
{
	var nodeState = getCookie("nodeState");
	if(nodeState == null)
	{
	    nodeState = ",|,|,";
		setCookie("nodeState",nodeState);
	}
	var layer = nodeState.split('|');
	for(var i=0;i<layer.length;i++)
	{
		if(layer[i] != ",")
		{
			var lItem = layer[i].split(',');
			var nodeIco = document.getElementById(lItem[0]+"_ico");
			var nodeLayer = document.getElementById(lItem[0]+"_layer");
			OpenNode(nodeIco);
			ShowDiv(nodeLayer);
		}
	}
}
function controlNode(obj,layerId)
{
	var className = obj.className.toLowerCase();
	var layer = document.getElementById(layerId + "_layer");
	cookiesNode(layerId,className);
	if(className == "opendir")
	{
		HideDiv(layer);
		obj.className = "closedir";
		
	}
	else if (className == "closedir")
	{
		ShowDiv(layer);
		obj.className = "opendir";
	}
}

function cookiesNode(layerId,className)
{
	var nodeState = getCookie("nodeState");
	if(nodeState == null)
	{
	    nodeState = ",|,|,";
		setCookie("nodeState",nodeState);
	}
	var layer = nodeState.split('|');
	var lDepth = getDepth(layerId);
	
	if(className == "opendir")
	{
		
		layer[lDepth-1] = ","
	}
	else
	{
		if(layer[lDepth-1] != ",")
		{
			var lItem = layer[lDepth-1].split(',');
			var lIco = document.getElementById(lItem[0]+"_ico");
			var lLayer = document.getElementById(lItem[0]+"_layer");
			HideDiv(lLayer);
			CloseNode(lIco);
		}
		layer[lDepth-1] = layerId+","+lDepth;
	}
	var cValue = layer[0];
	for(var i=1;i<layer.length;i++)
	{
		cValue += "|" + layer[i];
	}
	setCookie("nodeState",cValue);
}

function getDepth(layerId)
{
	var lItem = layerId.split('_');
	return lItem[1];
}

function OpenNode(obj)
{
	obj.className = "opendir";
}
function CloseNode(obj)
{
	obj.className = "closedir";
}
function HideDiv(obj)
{
    obj.style.display='none';

}
function ShowDiv(obj)
{
    obj.style.display='block';
}

//设置cookies
function setCookie(name,value)
{
var Days = 30;
var exp = new Date(); 
exp.setTime(exp.getTime() + Days*24*60*60*1000);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
//读取cookies
function getCookie(name)
{
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg)) return unescape(arr[2]);
else return null;
}
</script> 
