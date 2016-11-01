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
            @foreach($data_category_pagination as $v)
                <tr>
                    <td class="tc">
                        <input type="text" name="ord[]" value="{{$v->order_id}}">
                    </td>
                    <td class="tc">{{$v->id}}</td>
                    <td>
                        <a href="#">{{$v->title}}</a>
                    </td>
                    <td>{{$v->title}}</td>
                    <td>{{$v->view_count}}</td>
                    <td>
                        <a href="#">修改</a>
                        <a href="#">删除</a>
                    </td>
                </tr>

            @endforeach
        </table>

        @include('admin.elements.pagination',['data_page'=>$data_category_pagination])
    </div>
</div>
