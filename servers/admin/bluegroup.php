<?php require_once("../data/db.php");
$s = $db->getPar("s");
$w="tId='".$_SESSION["tId"]."'";
if($s!="")
	$w .= " AND (gName like '%$s%') ";
list($cur,$co,$pg,$pe,$ne,$cu,$results) = $db->getList("blueGroup",$w,"gSort asc",20);
	$g = $pg;
	$g = $g==0?0:$g;
	$url = "s=$s";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>巡检点分类- 安全生产隐患巡检管理系统</title>
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
<script src="../js/pub.js"></script>
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
</head>

<body>
<?php include("menu.php");?>
<!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="active">巡检点分类</li>
    </ol>
  </div>
  <!--/.row-->
  
  <div class="row" style=" margin:5px -15px;">
    <div class="col-lg-12" style="float:left;">
	<input class="form-control" id="search" placeholder="分类名称查询" style="width:220px;float:left;">
	<button type="button" class="btn btn-primary" onClick="return searchs();" style=" margin-left:5px">搜索</button>
    </div>
  </div>
  <script type="text/javascript">
  <?php echo '$("#search").val("'.$s.'");';?>
  </script>
  <script type="text/javascript">
  function searchs()
  {
	   window.location.href="bluegroup.php?s="+$("#search").val();
  }
  </script> 
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <table data-toggle="table">
            <thead>
              <tr>
                <th>编号</th>
                <th>名称</th>
                <th>排序</th>
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
				$row = $row["BlueGroup"];
		  ?>
            <tr>
              <td><?php echo $nums;?></td>
              <td><?php echo $row["gName"];?></td>
              <td><?php echo $row["gSort"];?></td>
              <td><a href="bluegroupedit.php?id=<?php echo $row["id"];?>"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp; <a href="javascript:void();" class="trash del" t="blueGroup" i="<?php echo $row["id"];?>" m="bluegroup.php"><span class="glyphicon glyphicon-trash"></span></a></td>
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
