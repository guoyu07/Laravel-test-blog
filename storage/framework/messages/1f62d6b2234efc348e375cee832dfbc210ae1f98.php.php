<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo e(url('public/admin/style/css/ch-ui.admin.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/admin/style/font/css/font-awesome.min.css')); ?>">
    <script type="text/javascript" src="<?php echo e(url('public/admin/style/js/jquery.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(url('public/admin/style/js/ch-ui.admin.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(url('public/admin/jsvalidation/js/jsvalidation.js')); ?>"></script>
</head>
<body style="background:#F3F3F4;">
<div class="login_box">
    <h1>Blog</h1>
    <h2><?php echo e(_('Hello! Welcome to the blog management platform')); ?></h2>
    <div class="form">
        <?php if(session('msg')): ?>
            <p style="color:red"><?php echo e(session('msg')); ?></p>
        <?php endif; ?>
        <div class="result_wrap">
            <div class="result_title">
                <?php if(count($errors)>0): ?>
                    <div class="mark">
                        <?php if(is_object($errors)): ?>
                            <?php foreach($errors->all() as $error): ?>
                                <p><?php echo e($error); ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?php echo e($errors); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <form action="" method="post">
            <?php echo e(csrf_field()); ?>

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
                    <img src="<?php echo e(captcha_src()); ?>" style="cursor:pointer"
                         onclick="this.src='<?php echo e(captcha_src()); ?>'+Math.random()">
                </li>
                <li>
                    <input type="submit" value="立即登陆"/>
                </li>
            </ul>
        </form>
        <p><a href="<?php echo e(url('')); ?>">返回首页</a> &copy; <?php echo e(date('Y')); ?> Powered by <a href="http://www.chenhua.club"
                                                                              target="_blank">http://www.chenhua.club</a>
        </p>
    </div>
</div>
</body>
</html>