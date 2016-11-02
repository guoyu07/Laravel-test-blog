@extends('layouts.admin')

@section('breadcrumbs')
&raquo; 修改密码
@endsection

@section('content')
    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <p>界面语言: {{ComoTool::LocaleInterface()}}</p>
            <p>内容语言: {{ComoTool::LocaleContent()}}</p>
            <h3>{{__('Modify Password')}} <br> {{url('/')}} <br> {{route('admin.logout')}}</h3>
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
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        {!! Form::model($admin,['url'=>'article/update']) !!}
        {{ Form::text('id') }}
        {!! Form::text('name') !!}
        {!! Form::text('email') !!}
        {!! Form::text('password') !!}
        {!! Form::close() !!}
        <form id="passForm" method="post" action="" onsubmit="return false">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="120"><i class="require">*</i>原密码：</th>
                    <td>
                        <input type="password" name="password_o"> </i>请输入原始密码</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>新密码：</th>
                    <td>
                        <input type="password" name="password"> </i>新密码6-20位</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>确认密码：</th>
                    <td>
                        <input type="password" name="password_confirmation"> </i>再次输入密码</span>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="button" class="btnSubmit2" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    {!! $validator->render('admin.elements.validation','#passForm') !!}
    <script>
        $('.btnSubmit2').click(function () {
            $(this).postForm('{{route('admin.user.change_password')}}', function (rsp) {
                $("div.result_title .mark").remove();
                if (rsp.status == 'error') {
                    var div = $("<div>");
                    div.addClass('mark');
                    $.each(rsp.msg, function (k, v) {
                        $.each(v, function (kk, vv) {
                            div.append('<p>' + vv + '</p>');
                        });
                    });
                    $("div.result_title").append(div);
                }
            });
        });
    </script>
@endsection