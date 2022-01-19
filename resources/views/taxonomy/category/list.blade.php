@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-grid"></i>&nbsp;{{ $taxonomy_title }}
            </h1>
            <a href="{{ admin_url($buttons['create']) }}" class="pop-modal btn btn-primary btn-sm pull-left" data-modal-id="add-taxonomy-category" title="{{ __('suda_lang::press.add') }}" data-toggle="tooltip" data-placement="top">
                <i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add') }}
            </a>
        </div>
        <div class="col-sm-12 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                          <thead class="bg-light">
                            <tr>
                              <th width="20%">名称</th>
                              <th width="10%">路径</th>
                              <th width="15%">排序</th>
                              <th width="20%">更新时间</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($categories)
                                
                                @include('view_suda::taxonomy.category.list_content',['cates'=>$categories,'child'=>0])
                                
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

@push('scripts')

<script type="text/javascript">

    $(document).ready(function(){
        $('body').on('click','.category-toggle',function(e){
            e.preventDefault();
            var el = this;
            var p_tr = $(el).parents('tr');
            var toggle_value = 0;
            if($(el).hasClass('toggle-open'))
            {
                $(el).removeClass('toggle-open').addClass('toggle-close');
                $(p_tr).nextUntil('tr[data_child="0"]').toggle();
                $(el).children('i').attr('class','ion-chevron-down');
                toggle_value = 1;
            }else if($(el).hasClass('toggle-close'))
            {
                $(el).removeClass('toggle-close').addClass('toggle-open');
                $(p_tr).nextUntil('tr[data_child="0"]').toggle();
                $(el).children('i').attr('class','ion-chevron-up');
                toggle_value = 0;
            }
            $.ajax({
                type: 'POST',
                url: suda.link(suda.data('adminPath')+'/article/category/toggle/'+$(el).attr('data-id')),
                cache: false,
                dataType: 'json',
                data: {toggle: toggle_value, _token:'{{ csrf_token() }}'},
                success(data) {
                    /**NOTHING**/
                },
                error() {
                    if (xhr.status == 422) {
                      const errors = xhr.responseJSON;
                      suda.infobox(errors);
                    }
                },
                fail() {
                    suda.infobox('请求失败');
                },
            });
        })
    });
    
</script>
@endpush
