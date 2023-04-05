@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-document-text"></i>
                已删除页面
            </h1>
        </div>
        
        @if($data_list && $data_list->count()>0)
        
        <div class="col-12 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    
                    <!-- list start -->
                    
                    <div class="table-responsive data-title">
                          <table class="table table-hover" style="margin-bottom:0px">
                              <thead class="bg-light">
                                <tr>
                                  <th width="5%">#<i class="stitle"></i></th>
                                  <th width="60px">主图</th>
                                  
                                  <th width="20%">标题</th>
                                  <th width="8%">发布</th>
                                  <th width="15%">发布者</th>
                                  <th width="15%">发布时间</th>
                                  <th>操作</th>
                                </tr>
                              </thead>
                          </table>
                      </div>
                  
                        <div class="table-responsive data-list">
                          <table class="table">
                          
                              <tbody>
                                @if($data_list)
                                @foreach ($data_list as $item)
                                <tr>
                                  <td width="5%">{{ $item->id }}</td>
                                  <td width="60px">
                                      
                                      <img src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                                      
                                  </td>
                                  
                                  <td width="20%">
                                      {{ $item->title }}
                                  </td>
                                  <td width="8%">
                                      @if($item->disable==1)
                                      否
                                      @else
                                      是
                                      @endif
                                  </td>
                                  <td width="15%">
                                      {{ $item->operate->username }}
                                  </td>
                                  
                                  <td width="15%">
                                      {{ $item->updated_at }}
                                  </td>
                                  
                                  <td>
                                      

                                      @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
                                      
                                      <button class="btn-restore btn btn-xs btn-light" data-id="{{ $item->id }}" href="{{ admin_url('page/restore/'.$item->id) }}">恢复</button>
                                      <button class="btn-forcedelete btn btn-xs btn-light" data-id="{{ $item->id }}" href="{{ admin_url('page/forcedelete/'.$item->id) }}">删除</button>
                                      
                                      @endif
                                  
                                  
                                  </td>
                                </tr>
                                @endforeach
                                @endif
                              </tbody>
                          </table>
                      
                          @if($data_list)
                          {{ $data_list->links() }}
                          @endif
                      
                          @if(isset($filter_str))
                      
                          <input type="hidden" id="filter_str" value="{{ $filter_str }}">
                      
                          @endif
                      
                        </div>
                    
                    
                    <!-- list end -->
                    
                </div>
                
                
                <div class="card-footer">
                    当前共有 {{ $data_count }} 条数据
                </div>
                
            </div>
            
        </div>
        
        @else
        
        <x-suda::empty-block />
        
        @endif
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    
    $('.btn-restore').suda_ajax({
        type:'POST',
        title:'确认恢复页面?',
        confirm:true,
    });

    $('.btn-forcedelete').suda_ajax({
        type:'POST',
        title:'确认删除页面?',
        confirm:true,
    });
    
});
</script>
@endpush