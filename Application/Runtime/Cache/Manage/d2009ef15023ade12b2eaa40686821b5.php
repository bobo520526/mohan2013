<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>登录中心 </title>

    <!-- Bootstrap -->
    <link href="/Public/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/Public/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/Public/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/Public/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/Public/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="post" action="" id="loginform">
              <h1>后台管理</h1>
              <div>
                <input type="text" class="form-control" name="username" placeholder="用户名" required="" />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="密码" required="" />
              </div>
              <div>
                <input type="text" class="form-control" name="vertify" placeholder="输入验证码" required="" style="width:40%;float: left;" />
                <span style="float: right;width: 60%;"><img alt="点击刷新验证码" id="imgVerify" style="cursor:pointer;" src="<?php echo U('Public/vertify');?>" onclick="fleshVerify();"/></span>
              </div>
              <div style="clear: both;margin-top: 20px;">
                <button id="cbt" class="btn btn-primary form-control" type="button" onclick="checkLogin()">登  录</button>
              </div>

              <div class="clearfix"></div>

            </form>
          </section>
        </div>
        <script src="/Public/js/jquery-1.8.2.min.js"></script>
        <script src="/Public/js/layer/layer.js"></script>
        <script>
          function fleshVerify(){
            //重载验证码
            $('#imgVerify').attr('src','/index.php?m=Manage&c=Public&a=vertify&r='+Math.floor(Math.random()*100));
          }

          function checkLogin(){
            var username = $('input[name="username"]').val();
            var password = $('input[name="password"]').val();
            var vertify = $('input[name="vertify"]').val();
            if( username == '' || password ==''){
              layer.alert('用户名或密码不能为空', {icon: 2}); //alert('用户名或密码不能为空');
              return;
            }
            if(vertify ==''){
              layer.alert('验证码不能为空', {icon: 2});
              return;
            }
            if(vertify.length !=4){
              layer.alert('验证码错误', {icon: 2});
              //fleshVerify();
              return;
            }
            $("#cbt").html("正在登录......");
            $.ajax({
              url:'/index.php?m=Manage&c=Public&a=login&t='+Math.random(),
              type:'post',
              dataType:'json',
              data:{username:username,password:password,vertify:vertify},
              success:function(res){
                $("#cbt").html("登  录");
                if(res.status==1){
                  top.location.href = res.url;
                }else{
                  layer.alert(res.msg, {icon: 2});
                }
              },
              error : function(XMLHttpRequest, textStatus, errorThrown) {
                $("#cbt").html("登  录");
                layer.alert('网络失败，请刷新页面后重试', {icon: 2});
              }
            })
          }

          document.onkeydown=function(event){
            e = event ? event :(window.event ? window.event : null);
            if(e.keyCode==13){
              checkLogin();
            }
          }

        </script>
      </div>
    </div>
  </body>
</html>