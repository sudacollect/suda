@extends('view_path::component.modal')



@section('content')

<div class="modal-body" style="padding:0">
          

    <div class="container" style="padding:0">


            <a href="{{ admin_url('style/dashboard') }}" class="col-sm-12" style="overflow:hidden;text-align:center;height:120px;line-height:120px;background:linear-gradient(215deg,rgb(87, 0, 255),rgba(253, 120, 255, 0.8) 90.71%);">
                <i class="zly-workbench-o" style="font-size:2.2rem;color:#fff;opacity:1"></i>
                <span style="text-align: center;font-size: 2.2rem;color:#fff;background:transparent;padding: 2px 10px;">
                    设置面板风格
                </span>
            </a>
            
            <a href="{{ admin_url('theme') }}" class="col-sm-12" style="overflow:hidden; text-align:center;height: 120px;line-height:120px;background:linear-gradient(215deg,rgb(67, 243, 135),rgba(73, 26, 187, 0.8) 80.71%);">
                <i class="zly-medal" style="font-size:2.2rem;color:#fff;opacity:1"></i>
                <span style="text-align: center;font-size: 2.2rem;color:#fff;background:transparent;padding: 2px 10px;">
                    设置主题外观
                </span>
            </a>

    </div>
</div>

<!-- <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
</div> -->

@endsection

