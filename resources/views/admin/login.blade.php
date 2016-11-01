<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{url('public/admin/style/css/ch-ui.admin.css')}}">
    <link rel="stylesheet" href="{{url('public/admin/style/font/css/font-awesome.min.css')}}">
    <script type="text/javascript" src="{{url('public/admin/style/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('public/admin/style/js/ch-ui.admin.js')}}"></script>
    <script type="text/javascript" src="{{url('public/admin/jsvalidation/js/jsvalidation.js')}}"></script>
</head>
<body style="background:#F3F3F4;">
<div class="login_box">
    <h1>Blog</h1>
    <h2>{{_('Hello! Welcome to the blog management platform')}}</h2>
    <div class="form">
        @if(session('msg'))
            <p style="color:red">{{session('msg')}}</p>
        @endif
        <div class="result_wrap">
            <div class="result_title">
                @if(count($errors)>0)
                    <div class="mark">
                        @if(is_object($errors))
                            @foreach($errors->all() as $error)
                                <p>{{$error}}</p>
                            @endforeach
                        @else
                            <p>{{$errors}}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <form action="" method="post">
            {{csrf_field()}}
            <ul>
                <li>
                    <input type="text" name="username" class="text"/>
                    <span><i class="fa fa-user"></i></span>
                </li>
                <li>
                    <input type="password" name="password" class="text"/>
                    <span><i class="fa fa-lock"></i></span>
                </li>
                <li>
                    <input type="text" class="code" name="code"/>
                    <span><i class="fa fa-check-square-o"></i></span>
                    <img src="{{captcha_src()}}" style="cursor:pointer"
                         onclick="this.src='{{captcha_src()}}'+Math.random()">
                </li>
                <li>
                    <input type="submit" value="立即登陆"/>
                </li>
            </ul>
        </form>
        <p><a href="{{url('')}}">返回首页</a> &copy; {{date('Y')}} Powered by <a href="http://www.chenhua.club"
                                                                              target="_blank">http://www.chenhua.club</a>
        </p>
    </div>
</div>
</body>
</html>