<?php require_once('../data/db.php');
$startime = $db->getPar("startime");
$endtime = $db->getPar("endtime");
$odState = $db->getPar("odState");
$s = $db->getPar("s");

$w="1=1";
//$w="tId='".$_SESSION["tId"]."'";
if($startime!="")
	$w .= " AND addTime>='$startime'";
if($endtime!="")
	$w .= " AND addTime<='$endtime'";
if($odState!="")
	$w .= " AND odState='$odState'";
if($s!="")
	$w .= " AND (hyName = '$s' OR dropName = '$s' OR odInfo = '$s' OR odtogether = '$s') ";
	
list($cur,$co,$pg,$pe,$ne,$cu,$results) = $db->getList("blueOrder",$w,"addTime desc",20);
	$g = $pg;
	$g = $g==0?0:$g;
	$url = "startime=$startime&endtime=$endtime&odState=$odState&s=$s";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>巡检记录-安全生产隐患巡检管理系统</title>
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
      <li class="active">巡检记录</li>
    </ol>
  </div>
  <!--/.row-->
  
  <div class="row" style=" margin:5px -15px;">
    <div class="col-lg-12" style="float:left;">
    <input class="form-control" type="text" id="startime" placeholder="请输入开始时间" style="width:160px;float:left;">
    <input class="form-control" type="text" id="endtime" placeholder="请输入结束时间" style="width:160px;float:left;">
    <select id="odState" class="form-control" style="width:120px;float:left;">
		<option value="">全部状态</option>
        <option value="0">正常</option>
        <option value="1">异常</option>        
	</select>
	<input class="form-control" id="search" placeholder="输入巡检人/巡检点名称/巡检备注查询" style="width:220px;float:left;">
	<button type="button" class="btn btn-primary" onClick="return searchs();" style=" margin-left:5px">搜索</button>
    <button type="button" class="btn btn-primary" onClick="return dc();" style=" margin-left:5px;background-color:#F00; border-color:#F00">导出</button>
    </div>
  </div>
  <script type="text/javascript">
  <?php 
	echo '$("#startime").val("'.$startime.'");';
	echo '$("#endtime").val("'.$endtime.'");';
	echo '$("#odState").val("'.$odState.'");';
	echo '$("#search").val("'.$s.'");';
  ?>
  </script>
  <script type="text/javascript">
  function searchs()
  {
	   window.location.href="blueOrder.php?startime="+$("#startime").val()+"&endtime="+$("#endtime").val()+"&odState="+$("#odState").val()+"&s="+$("#search").val();
  }
  function dc()
  {
	   window.location.href="../sendexcel/blueOrder.php?startime="+$("#startime").val()+"&endtime="+$("#endtime").val()+"&odState="+$("#odState").val()+"&s="+$("#search").val();
  }
  </script> 
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <table data-toggle="table">
            <thead>
              <tr>
                <th>巡检图</th>
                <th>巡检人</th>
                <th>随检人</th>
                <th>巡检点</th>
                <th>状态</th>
                <th>巡检时间</th>
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
				$row = $row["BlueOrder"];
		  ?>
            <tr>
              <td><?php 
				$yhPhoto = explode('|',$row["odPhoto"]);
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
              <td><?php echo $row["hyName"];?></td>
              <td><?php echo $row["odtogether"];?></td>
              <td><?php echo $row["dropName"];?></td>
              <td><?php echo $row["odState"]==0?"正常":"<span style='color:red;'>异常</span>";?></td>
              <td><?php echo $row["addTime"];?></td>
              <td>              
              <a href="blueOrderedit.php?id=<?php echo $row["id"];?>"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp; 
              <a href="javascript:void();" class="trash del" t="blueOrder" i="<?php echo $row["id"];?>" m="blueOrder.php"><span class="glyphicon glyphicon-trash"></span></a>
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
