<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <title>登录</title>
    <script type="text/javascript" src="/tpl/Public/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/tpl/Public/js/jquery-form.js"></script>
    <script type="text/javascript" src="/tpl/Public/js/layer_mobile2/layer.js"></script>
    <script type="text/javascript" src="/tpl/Public/js/func.js"></script>
    <style>
        html,body,div,p,form,label,ul,li,dl,dt,dd,ol,img,button,b,em,strong,small,h1,h2,h3,h4,h5,h6{margin:0;padding:0;border:0;list-style:none;font-style:normal;}
        body{font-family:SimHei,'Helvetica Neue',Arial,'Droid Sans',sans-serif;font-size:14px;color:#333;background:#f2f2f2;}
        a, a.link{color:#666;text-decoration:none;font-weight:500;}
        a, a.link:hover{color:#666;}
        h1,h2,h3,h4,h5,h6{font-weight: normal;}
        body{
            background:url(/tpl/Public/images/login-bg.png) no-repeat;
            background-size: 100% auto;
            /*background: #58afbd;*/
        }
        .login{}
        .welcome{width:100%;margin:10% 0 10% 0;text-align: center;}
        .welcome img{}
        .login-inp{margin:0 30px 15px 30px;border:1px solid #fff;border-radius:25px;}
        .login-inp label{width:4em;text-align:center;display:inline-block;color:#fff;}
        .login-inp input{line-height:40px;color:#fff;background-color:transparent;border:none;outline: none;}
        .login-inp .submit{display:block;background:none;width:100%;text-align:center;line-height:40px;color:#fff;font-size:16px;letter-spacing:5px;}
        .login-txt{text-align:center;color:#fff;}
        .login-txt a{color:#fff;padding:0 5px;}
    </style>
</head>

<body style="background: #fff;" >
<div class="login">
    <form class="submit-ajax" action="<?php echo U('login');?>">
        <div class="welcome"><img src="/tpl/Public/images/zan.png" style="width: 138px;height: 138px;display: block;margin: 0 auto;"></div>
        <div class="login-form">
        	
        	
            <div class="login-inp" style="background: #f4f4f4;border: none;"><label style="color: #999;">手机</label><input  type="text" name="username" id="username"  placeholder="请输入手机号码" autocomplete="off" style="color: #000;"></div>
            
            
            <div class="login-inp" style="background: #f4f4f4;border: none;color">
            	<label style="color: #999;">密码</label>
            	<input type="password" name="password" id="password"  placeholder="请输入密码" class="password" autocomplete="off" style="color: #000;"></div>
            <div class="login-inp" style="border: none;background: #b02fd3;"><button type="submit" class="submit">立即登录</button></div>
        </div>
        
        <a href="<?php echo U('reg');?>">
        	<p style="width: 150px;height: 60px;line-height: 60px;margin: 66px auto 0; color: #b02fd3; "> <span style="width: 20px;height: 20px;border: 1px #b02fd3 solid;display: inline-block;margin: 20px 6px 20px 40px;text-align: center;float: left;line-height: 20px;border-radius: 20px;text-align: center;color: #b02fd3;">→</span> 去注册</p>
        </a>
        
    
    </form>
</div>

</body>
</html>