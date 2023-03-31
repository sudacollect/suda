@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-grid"></i>&nbsp;{{ $taxonomy_title }}
            </h1>
            <a href="{{ $buttons['create'] }}" class="pop-modal btn btn-primary btn-sm pull-left" data-modal-id="add-taxonomy-category" title="{{ __('suda_lang::press.add') }}" data-toggle="tooltip" data-placement="top">
                <i class="ion-add-circle"></i>&nbsp;{{ __('suda_lang::press.add') }}
            </a>
        </div>
        <div class="col-sm-12 suda_page_body">
            <div class="card">
                <div class="card-body">
                    @if($categories && $categories->count()>0)
                    <div class="table-responsive">
                      <table class="table">
                          <thead class="bg-light">
                            <tr>
                              <th width="20%">名称</th>
                              <th width="10%">路径</th>
                              <th width="15%">排序</th>
                              <th width="20%">更新</th>
                              <th>操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                                
                                @include('view_suda::taxonomy.category.list_content',['cates'=>$categories,'child'=>0])
                                
                            
                          </tbody>
                      </table>
                      
                    </div>
                    @else
                    <x-suda::empty-block empty="Oops.." :card=false />
                    @endif
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
        })
    });
    
</script>
@endpush
