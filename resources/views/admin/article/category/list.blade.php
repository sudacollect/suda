@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title"><i class="zly-th-o"></i>&nbsp;文章分类</h1>
            <a href="{{ admin_url('article/category/add') }}" class="pop-modal btn btn-primary btn-sm pull-left" data-modal-id="add-article-category"><i class="ion-add"></i>&nbsp;{{ __('suda_lang::press.btn.add') }}</a>
        </div>
        <div class="col-sm-12 suda_page_body">
            <div class="panel panel-default">
                
                <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                          <thead>
                            <tr>
                              <th width="20%">分类名称</th>
                              <th width="10%">路径</th>
                              <th width="15%">排序</th>
                              <th width="20%">更新时间</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($categories)
                                
                                @include('view_suda::admin.article.category.list_content',['cates'=>$categories,'child'=>0])
                                
                            @endif
                          </tbody>
                      </table>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
