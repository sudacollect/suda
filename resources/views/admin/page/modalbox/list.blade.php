@extends('view_path::component.modal',['modal_size'=>'md'])



@section('content')

        <div class="modal-header">
            
            <h4 class="modal-title">
                <i class="ion-checkmark-done-outline"></i>&nbsp;选择页面
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        @if($data_list->count()>0)
        
               
                <div class="modal-body" style="padding:15px 0;">
                    
                    <div class="col-sm-12">
                  
                         <div class="table-responsive data-list">
                            
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                      <th width="5%">#<i class="stitle"></i></th>
                                  
                                      <th width="30%">标题</th>
                                      <th width="10%">发布</th>
                                      <!-- <th width="15%">发布时间</th> -->
                                      <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($data_list)
                                    @foreach ($data_list as $item)
                                    <tr>
                                      <td width="5%">{{ $item->id }}</td>
                                  
                                      <td width="30%">
                                          {{ $item->title }}
                                      </td>
                                      <td width="10%">
                                          @if($item->disable==1)
                                          否
                                          @else
                                          是
                                          @endif
                                      </td>
                                  
                                      <td>
                                        <button select-id="{{ $item->id }}" select-title="{{ $item->title }}" select-result="{{ $result_name }}" type="button" class="modal-select-page btn btn-light btn-sm" >选择</button>
                                      </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                      
                              @if($data_list)
                              {{ $data_list->links() }}
                              @endif
                      
                            </div>
                    </div>
                    
                    
                    
                    
                </div>
                
                
                <div class="modal-footer">
                    当前共有 {{ $data_count }} 条数据
                </div>
        
        @else
        
        
        
        @endif
        
        <script>
            
            $(document).ready(function(){
                
                $('.modal-select-page').on('click',function(e){
                    
                    e.preventDefault();
                    
                    var page_id = $(this).attr('select-id');
                    var page_title = $(this).attr('select-title');
                    var result_name = $(this).attr('select-result');
                    
                    var result_html = '<p><input type="hidden" name="'+result_name+'_page_id" value="'+page_id+'">'+page_title+'</p>';
                    $('body').find('.modal-result-'+result_name).show().html(result_html);
                    
                    $(this).parents('.modal-layout').modal('hide');
                });
                
            });
            
        </script>

@endsection
