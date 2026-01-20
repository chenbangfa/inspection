<?php  require_once('../data/db.php');?>
	  <!DOCTYPE html>
	  <html>
      <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>修改密码 -安全生产隐患巡检管理系统</title>
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
      <script src="../js/pub.js"></script>
      
      <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
      
      </head>
      <style>
    .form-control{ display:inline; width:70%}
    .tit:before { content:"| "; color:#F00; font-size:20px; font-weight:bold }
	
.radio {
  margin: 1rem;
}
.radio input[type=radio] {
  position: absolute;
  opacity: 0;
}
.radio input[type=radio] + .radio-label:before {
  content: "";
  background: #f4f4f4;
  border-radius: 100%;
  border: 1px solid #b4b4b4;
  display: inline-block;
  width: 2em;
  height: 2em;
  position: relative;
  top: -0.2em;
  margin-right: 1em;
  vertical-align: top;
  cursor: pointer;
  text-align: center;
  transition: all 250ms ease;
}
.radio input[type=radio]:checked + .radio-label:before {
  background-color: #3197EE;
  box-shadow: inset 0 0 0 4px #f4f4f4;
}
.radio input[type=radio]:focus + .radio-label:before {
  outline: none;
  border-color: #3197EE;
}
.radio input[type=radio]:disabled + .radio-label:before {
  box-shadow: inset 0 0 0 4px #f4f4f4;
  border-color: #b4b4b4;
  background: #b4b4b4;
}
.radio input[type=radio] + .radio-label:empty:before {
  margin-right: 0;
}
    </style>
<script>setCookie("menu","xgmm");</script>
      <body>
      <?php include("menu.php");?>
      <!--/.sidebar-->
      <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
          <ol class="breadcrumb">
            <li><a href="proadd.php"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">修改密码</li>
            <li class="active">修改密码</li>
          </ol>
        </div>
        <!--/.row-->
        <div class="row" style="margin-top:5px">
          <div class="col-lg-12">
            <div class="panel panel-default">
              <div class="panel-body">
               <form class="form1">
               <input type="hidden" name="tag" value="resetpwd"/>
               <input type="hidden" name="id" id="id" value="<?php echo $_SESSION["hyid"];?>"/>
                <div class="col-md-6" style="width:50%; float:left">
                    <div class="form-group">
                      <label style="letter-spacing:5px">用户名：</label>
                      <?php echo $_SESSION["qdadmname"];?>
                    </div> 
                    <div class="form-group">
                      <label style="letter-spacing:5px">原密码：</label>
                      <input class="form-control req" name="oldpwd" type="password" placeholder="请输入原密码">
                    </div>
                    <div class="form-group">
                      <label style="letter-spacing:5px">新密码：</label>
                      <input class="form-control req" name="newpwd" type="password" placeholder="请输入新密码">
                    </div> 
                    <div class="form-group">
                      <label>重复密码：</label>
                      <input class="form-control req" name="repeatpwd" type="password" placeholder="请输再次入新密码">
                    </div> 
                    <button type="button" class="btn btn-primary save">修改</button>
                </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /.col--> 
        </div>
        <!-- /.row --> 
      </div>
      <!--/.main-->
   
  
  <script type="text/javascript">
	
	
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
	  