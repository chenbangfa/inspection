<?php require_once('../data/db.php');
$groupId = $db->getPar("groupId");
$s = $db->getPar("s");

$w="tId='".$_SESSION["tId"]."'";
if($groupId!="")
	$w .= " AND dropClass='$groupId'";
if($s!="")
	$w .= " AND (dropName like '%$s%' OR dropNo like '%$s%' OR dropInfo like '%$s%') ";
	
list($cur,$co,$pg,$pe,$ne,$cu,$results) = $db->getList("blueInspect",$w,"addTime desc",20);
	$g = $pg;
	$g = $g==0?0:$g;
	$url = "groupId=$groupId&s=$s";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>巡检点管理-安全生产隐患巡检管理系统</title>
<link rel="shortcut icon" href="../image/logo.png">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/bootstrap-table.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/chart-data.js"></script>
<script src="js/easypiechart.js"></script>
<script src="js/easypiechart-data.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-table.js"></script>
<script src="../js/pub.js?v=2"></script>
<style>
.pages {
	width: 100%;
	float: left;
	padding: 20px 0;
	text-align: center;
	font-size: 16px;
	color: #666;
}
.pages a {
	text-decoration: none
}
.pages span {
	padding: 5px 10px;
	border: 1px solid #e6e6e6;
	background: #fff;
	cursor: pointer;
	color: #666;
}
.pages span.on {
	border: 1px solid #f30;
	background: #f30;
	color: #fff;
}
.pages span:hover {
	border: 1px solid #f30;
	background: #f30;
	color: #fff;
}
.table>tbody>tr>td{ vertical-align:middle}
</style>
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
<script>setCookie("menu","bmgl");</script>
</head>

<body>
<?php include("menu.php");?>
<!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="active">签到记录</li>
    </ol>
  </div>
  <!--/.row-->
  
  <div class="row" style=" margin:5px -15px;">
    <div class="col-lg-12" style="float:left;">
    <select id="groupId" class="form-control" style="width:120px;float:left;">
		<option value="">全部分类</option>
        <?php $res=$db->getAll("blueGroup","tId='".$_SESSION["tId"]."'","gSort asc");
		foreach ($res as $rowg)
			{
				$rowg = $rowg["BlueGroup"];
		?>
        <option value="<?php echo $rowg["gName"];?>"><?php echo $rowg["gName"];?></option>
        <?php }?>
	</select>
	<input class="form-control" id="search" placeholder="巡检点编号、名称、巡检人查询" style="width:320px;float:left;">
	<button type="button" class="btn btn-primary" onClick="return searchs();" style=" margin-left:5px">搜索</button>
    <button type="button" class="btn btn-primary" onClick="return dc();" style=" margin-left:5px;background-color:#F00; border-color:#F00">导出</button>
    </div>
  </div>
  <script type="text/javascript">
  <?php 
	echo '$("#groupId").val("'.$groupId.'");';
	echo '$("#search").val("'.$s.'");';
  ?>
  </script>
  <script type="text/javascript">
  function searchs()
  {
	   window.location.href="bluelist.php?groupId="+$("#groupId").val()+"&s="+$("#search").val();
  }
  function dc()
  {
	   window.location.href="../sendexcel/bluelist.php?groupId="+$("#groupId").val()+"&s="+$("#search").val();
  }
  </script> 
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <table data-toggle="table">
            <thead>
              <tr>
                <th>图片</th>
                <th>编号</th>
                <th>名称</th>
                <th>分类</th>
                <th>周期</th>
                <th>次数</th>
                <th>间隔</th>
                <th>蓝牙</th>
                <th>巡检人</th>
                <th>巡检结果</th>
                <th>最后巡检时间</th>
                <th>操作</th>
              </tr>
            </thead>
			<?php
			$count = $cu;
			$page_count = $g; 
			$init=1; 
			$page_len=7; 
			$max_p=$page_count; 
			$pages=$page_count; 
			//判断当前页码 
			$page=$db->getPar("p"); 
			$page = empty($page)?1:$page;
			$nums=0;
			$key="";
			foreach ($results as $row)
			{
				$nums++;
				$row = $row["BlueInspect"];
		  ?>
            <tr>
              <td><?php 
				$yhPhoto = explode('|',$row["dropPhoto"]);
				for($i=0;$i<count($yhPhoto);$i++) 
					{
						if($yhPhoto[$i]!="")
						{
							$imgs=$yhPhoto[$i];
							$imgs=str_replace('"','', $imgs);
							echo '<img src="../'.$imgs.'" style="width:50px; height:50px;">';
							break;	
						}
					}?>
               </td>
              <td><?php echo $row["dropNo"];?></td>
              <td><?php echo $row["dropName"];?></td>
              <td><?php echo $row["dropClass"];?></td>
              <td><?php echo $row["patrolCycle"];?></td>
              <td><?php echo $row["patrolNum"];?></td>
              <td><?php echo $row["patrolDiff"];?></td>
              <td><?php echo $row["deviceId"]==""?"未绑定":$row["deviceId"];?></td>
              <td><?php echo $row["hyAppointName"];?></td>
              <td><?php echo $row["drores"]==0?"正常":"<span style='color:red'>异常</span>";?></td>
              <td><?php echo $row["inspectTime"];?></td>
              <td>              
              <a href="blueedit.php?id=<?php echo $row["id"];?>"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp; 
              <a href="javascript:void();" class="trash del" t="blueInspect" i="<?php echo $row["id"];?>" m="bluelist.php"><span class="glyphicon glyphicon-trash"></span></a>
              </td>
            </tr>
            <?php } $page_len = ($page_len%2)?$page_len:$pagelen+1;//页码个数 
					$pageoffset = ($page_len-1)/2;//页码个数左右偏移量  
					$key.= "<div style='width:100%;float:left;color:#999; line-height:34px;'>共".$co."条，共".$pg."页</div><br/>";
					if($page!=1){ 
					$key.=" <a href=\"".$_SERVER['PHP_SELF']."?".$url."&p=1\"><span>首页</span></a> "; //第一页 
					$key.=" <a href=\"".$_SERVER['PHP_SELF']."?".$url."&p=".($pe)."\"><span>上一页</span></a>"; //上一页 
					}else { 
					$key.=" <a><span>首页</span></a>";//第一页 
					$key.=" <a><span>上一页</span></a>"; //上一页 
					} 
					if($pages>$page_len){ 
					//如果当前页小于等于左偏移 
					if($page<=$pageoffset){ 
					$init=1; 
					$max_p = $page_len; 
					}else{//如果当前页大于左偏移 
					//如果当前页码右偏移超出最大分页数 
					if($page+$pageoffset>=$pages+1){ 
					$init = $pages-$page_len+1; 
					}else{ 
					//左右偏移都存在时的计算 
					$init = $page-$pageoffset; 
					$max_p = $page+$pageoffset; 
					} 
					} 
					} 
					for($i=$init;$i<=$max_p;$i++){ 
					if($i==$page){ 
					$key.=' <a><span class="on">'.$i.'</span></a>'; 
					} else { 
					$key.=" <a href=\"".$_SERVER['PHP_SELF']."?".$url."&p=".$i."\"><span>".$i."</span></a>"; 
					} 
					} 
					if($page!=$pages){ 
					$key.=" <a href=\"".$_SERVER['PHP_SELF']."?".$url."&p=".($ne)."\"><span>下一页</span></a>";//下一页 
					$key.=" <a href=\"".$_SERVER['PHP_SELF']."?".$url."&p={$pages}\"><span>末页</span></a>"; //最后一页 
					}else { 
					$key.=" <a><span>下一页</span> </a>";//下一页 
					$key.=" <a><span>末页</span></a>"; //最后一页 
					} 
				?>
          </table>
          <div class="pages"> <?php echo $key ?> </div>
        </div>
      </div>
    </div>
  </div>
</div>

	<script src="laydate/laydate.js"></script> 
    <script>
	lay('#version').html('-v'+ laydate.v);
	
	//执行一个laydate实例
	laydate.render({
	  elem: '#startime',
	  type : 'datetime'
	});
	
	//执行一个laydate实例
	laydate.render({
	  elem: '#endtime',
	  type : 'datetime'
	});
	</script>
    
<script>
var w=window.screen.width-220;
$(".main").css("width",w+"px");
</script>
<style>
.main{margin-left:200px; margin-top:33px}
.breadcrumb{background-color:#fff;}
.col-lg-12{ padding:0}
</style>
</body>
</html>
