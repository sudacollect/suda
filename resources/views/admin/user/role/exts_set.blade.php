@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <h1 class="page-title">
            角色应用授权
            <span class="help-block">
            设置应用的具体权限
        </span>
        </h1>
        
    </div>

    <div class="row suda-row">
        
        <div class="zsteps col-sm-8 offset-sm-2">
                
                <div class="col-sm-6 zsteps-step complete">
                  <div class="text-center zsteps-stepnum">第一步</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="zsteps-dot"></a>
                  <div class="zsteps-info text-center">选择应用</div>
                </div>
                
                <div class="col-sm-6 zsteps-step active"><!-- complete -->
                  <div class="text-center zsteps-stepnum">第二步</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="zsteps-dot"></a>
                  <div class="zsteps-info text-center">
                      设置应用权限
                  </div>
                </div>
                
        </div>
            
        
        

        <form class="form-ajax" method="POST" id="exts-form" action="{{ admin_url('user/roles/setexts/save') }}">

            @csrf
            <input type="hidden" name="role_id" value="{{ $role->id }}" >

            <div class="col-sm-12 suda_page_body">
                <div class="panel panel-default panel-exts-set" style="min-height:500px;">
                    <div class="panel-body " style="position:relative;">
                    
                        <div class="ext-lists" style="width:200px;padding:0px;position:absolute;left:0px;top:0px;min-height:500px;border-right:1px solid #eee;">

                            <ul class="ext-ul">
                            @foreach($role->permissions['exts'] as $slug=>$item)

                            <li class="ext-li" style="border-bottom:1px solid #f5f5f5;padding:10px 10px;" ext-slug="{{ $slug }}">
                            <img src="{{ extension_logo($slug) }}" class="icon icon-extension" style="max-width:48px;">
                            {{ $exts_all[$slug]['name'] }}
                            </li>
                            @endforeach
                            </ul>
                        </div>

                        <div class="ext-details" style="width:100%;padding-left:215px;">
                            
                        </div>

                    </div><!-- panel-body -->

                </div><!-- panel -->
            </div><!-- suda_page_body -->

            <div class="col-sm-12" style="text-align:center;">
                <button type="submit" class="btn btn-primary btn-md" id="submit">保存设置</button>
            </div>

        </form>

    </div>
</div>
@endsection


@push('scripts')

<script type="text/javascript">

$(document).ready(function(){

    var getExt = function(slug){

        $.ajax({
            url: suda.link(suda.data('adminPath')+'/manage/extension/getPolicy/'+slug),
            type: 'GET',
            dataType: 'html',
            data: {
                _token: suda.data('csrfToken')
            },
            error: function(xhr) {
                suda.modal({error:"获取应用失败"});
            },
            success: function(res) {
                console.log(res);
            },
        });

    };

    var ext_slug = $('.ext-lists').find('li.ext-li:first').attr('ext-slug');
    getExt(ext_slug);


});

</script>

@endpush
