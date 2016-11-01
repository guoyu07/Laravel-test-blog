<div class="result_wrap">
    <div class="result_content">
        <table class="list_tab">
            <tr>
                <th class="tc" width="5%">排序</th>
                <th class="tc" width="5%">ID</th>
                <th>分类名称</th>
                <th>标题</th>
                <th>查看次数</th>
                <th>操作</th>
            </tr>
            <?php foreach($data_category_pagination as $v): ?>
                <tr>
                    <td class="tc">
                        <input type="text" name="ord[]" value="<?php echo e($v->order_id); ?>">
                    </td>
                    <td class="tc"><?php echo e($v->id); ?></td>
                    <td>
                        <a href="#"><?php echo e($v->title); ?></a>
                    </td>
                    <td><?php echo e($v->title); ?></td>
                    <td><?php echo e($v->view_count); ?></td>
                    <td>
                        <a href="#">修改</a>
                        <a href="#">删除</a>
                    </td>
                </tr>

            <?php endforeach; ?>
        </table>

        <?php echo $__env->make('admin.elements.pagination',['data_page'=>$data_category_pagination], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
</div>
