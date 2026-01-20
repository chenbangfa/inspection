<?php require_once("../data/db.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>签到记录 - 安全生产隐患巡检管理系统</title>
<link rel="shortcut icon" href="../image/logo.png">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
    <script src="upfile/jquery.form.js" type="text/javascript"></script>
<script src="../js/pub.js"></script>


<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<style>.form-control{ width:400px; display:inline}</style>
<body>
<?php include("menu.php");?>
<!--/.sidebar-->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
				<li class="active">签到记录</li>
                <li class="active">编辑记录</li>
			</ol>
		</div><!--/.row-->
		
		<!--/.row-->	
		<div class="row" style=" margin:5px -15px;">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
                    <?php 
					$id=$db->getpar("id");
					$rebate=$db->getOne("inspect","id='".$id."'");
					$row=$rebate["Inspect"];
					?>
						<div class="col-md-6" style="width:60%; float:left">
							<form class="form1">
                            <input type="hidden" name="tag" value="inspectedit" />
                            <input type="hidden" value="<?php echo $row["id"];?>" name="id">
                                <div class="form-group">
									<label>公司名称：</label>
                                    <input class="form-control req" value="<?php echo $row["gsName"];?>" name="gsName">
								</div>
                                <div class="form-group">
									<label>隐患位置：</label>
                                    <input class="form-control req" value="<?php echo $row["yhAdd"];?>" name="yhAdd">
								</div>
                                <div class="form-group">
									<label>隐患部位：</label>
                                    <input class="form-control req" value="<?php echo $row["yhPosition"];?>" name="yhPosition">
								</div>
                                <div class="form-group">
									<label>隐患内容：</label>
                                    <textarea class="form-control req" name="yhContent" rows="4"><?php echo $row["yhContent"];?></textarea>
								</div>
                                <div class="form-group">
									<label>隐患专业：</label>                                    
                                    <select id="yhSpeciality" class="form-control" name="yhSpeciality">
                                    <?php $res=$db->getAll("yhzyGroup","tId='".$_SESSION["tId"]."'","gSort asc");
									foreach ($res as $rowg)
										{
											$rowg = $rowg["YhzyGroup"];
									?>
									<option value="<?php echo $rowg["gName"];?>"><?php echo $rowg["gName"];?></option>
									<?php }?>
                                    </select>
								</div>
                                <div class="form-group">
									<label>随检人员：</label>
                                    <input class="form-control req" value="<?php echo $row["together"];?>" name="together">
								</div>
                                
                                <div class="form-group">
									<label>整治要求：</label>
                                    <textarea class="form-control req" name="zgAsk" rows="4"><?php echo $row["zgAsk"];?></textarea>
								</div>
                                <div class="form-group">
									<label>整治时限：</label>
                                    <input class="form-control req" value="<?php echo $row["zgTime"];?>" name="zgTime">
								</div>
                                <div class="form-group">
									<label>签到定位：</label>
                                    <?php echo $row["posAdd"];?>
								</div>
                                
                                <div class="form-group">
									<label>签到时间：</label>
                                    <?php echo $row["addTime"];?>
								</div>
                                 <script type="text/javascript">
								$("#yhSpeciality").val("<?php echo $row["yhSpeciality"];?>");
                                </script>
							<button type="button" class="btn btn-primary save">保存</button>
						</form>
                        
                        </div>
                        <div style="float:right; width:40%;">
                        <p style="font-size:16px;padding-left:5px;padding:10px 0 5px 0; margin-top:5px; background-color:#fff" class="tit">隐患照片</p>    
                        <?php 
                                    $a=0;
                                    $c="";
                                    $gxpic = explode('|',$row["yhPhoto"]);
                                    for($i=0;$i<count($gxpic);$i++) 
                                    {
                                        if($gxpic[$i]!="")
                                        {
                                            if($a>2)
                                                $c="margin-top:4px;";
                                            echo '<a href="../'.$gxpic[$i].'" target="_blank"><img src="../'.$gxpic[$i].'" style="width:30%;margin-right:4px;'.$c.'" class="imgs-box"></a>';
                                            $a++;
                                        }
                                    }
                                ?>
          
                        </div>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->		
	</div><!--/.main-->
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
