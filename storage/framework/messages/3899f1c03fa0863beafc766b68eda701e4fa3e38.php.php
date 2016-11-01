<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/admin/style/css/ch-ui.admin.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/admin/style/font/css/font-awesome.min.css')); ?>">
    <script type="text/javascript" src="<?php echo e(url('public/admin/style/js/jquery.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(url('public/admin/style/js/ch-ui.admin.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(url('public/admin/jsvalidation/js/jsvalidation.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script>
        $.fn.post = function (url, data, callback, type) {
            var $this = $(this);
            if ($this.hasClass('__onRequesting')) {
                return;
            }
            $this.addClass('__onRequesting');
            if (!type) {
                type = 'html';
            }
            if (jQuery.isFunction(data)) {
                type = type || callback;
                callback = data;
                data = undefined;
            }
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: type,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (rsp) {
                    $this.removeClass('__onRequesting');
                    callback(rsp);
                },
                error: function (rsp) {
                    $this.removeClass('__onRequesting');
                    callback(rsp.responseText);
                }
            });
        };
        $.fn.postForm = function (url, callback, type) {
            if ($(this).closest('form').valid()) {
                $(this).post(url, $(this).closest('form').serialize(), callback, 'json');
            }
        };
    </script>
</head>
<body>
<!--头部 开始-->
<div class="top_box">
    <div class="top_left">
        <div class="logo">后台管理模板</div>
        <ul>
            <li><a href="<?php echo e(route('admin.home')); ?>" class="active">首页</a></li>
            <li><a href="#">管理页</a></li>
        </ul>
    </div>
    <div class="top_right">
        <ul>
            <li><?php echo e($sitename2); ?> 管理员：<?php echo e(session('admin.name')); ?></li>
            <li><a href="<?php echo e(route('admin.user.change_password')); ?>">修改密码</a></li>
            <li><a href="<?php echo e(route('admin.logout')); ?>">退出</a></li>
        </ul>
    </div>
</div>
<!--头部 结束-->

<!--左侧导航 开始-->
<div class="menu_box">
    <ul>
        <li>
            <h3><i class="fa fa-fw fa-clipboard"></i>内容管理</h3>
            <ul class="sub_menu">
                <li><a href="<?php echo e(route('admin.category.create')); ?>"><i class="fa fa-fw fa-plus-square"></i>添加分类</a></li>
                <li><a href="<?php echo e(route('admin.category.showlist')); ?>"><i class="fa fa-fw fa-list-ul"></i>分类列表</a></li>
                <li><a href="<?php echo e(route('admin.article.create')); ?>"><i class="fa fa-fw fa-plus-square"></i>添加文章</a></li>
                <li><a href="<?php echo e(route('admin.article.index')); ?>"><i class="fa fa-fw fa-list-ul"></i>文章列表</a></li>
            </ul>
        </li>
        <li>
            <h3><i class="fa fa-fw fa-cog"></i>系统设置</h3>
            <ul class="sub_menu">
                <li><a href="#" target="main"><i class="fa fa-fw fa-cubes"></i>网站配置</a></li>
                <li><a href="#" target="main"><i class="fa fa-fw fa-database"></i>备份还原</a></li>
            </ul>
        </li>
    </ul >
</div>
<!--左侧导航 结束-->

<!--主体部分 开始-->
<div class="main_box">
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(route('admin.home')); ?>">首页</a><?php echo $__env->yieldContent('breadcrumbs'); ?>
    </div>
    <!--面包屑导航 结束-->
    <?php echo $__env->yieldContent('content'); ?>
</div>
<!--主体部分 结束-->

<!--底部 开始-->
<div class="bottom_box">
    CopyRight &copy; <?php echo e(date('Y')); ?>. Powered By <a href="http://www.chenhua.club">http://www.chenhua.club</a>.
</div>
<!--底部 结束-->
</body>
</html>