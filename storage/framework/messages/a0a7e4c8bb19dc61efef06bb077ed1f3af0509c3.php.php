<?php $__env->startSection('breadcrumbs'); ?>
&raquo; 修改密码
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3><?php echo e(__('Modify Password')); ?></h3>
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
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <?php echo Form::model($admin,['url'=>'article/update']); ?>

        <?php echo e(Form::text('id')); ?>

        <?php echo Form::text('name'); ?>

        <?php echo Form::text('email'); ?>

        <?php echo Form::text('password'); ?>

        <?php echo Form::close(); ?>

        <form id="passForm" method="post" action="" onsubmit="return false">
            <?php echo e(csrf_field()); ?>

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
    <?php echo $validator->render('admin.elements.validation','#passForm'); ?>

    <script>
        $('.btnSubmit2').click(function () {
            $(this).postForm('<?php echo e(route('admin.user.change_password')); ?>', function (rsp) {
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>