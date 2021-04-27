
<div class="col-sm-12 mb-3 suda_page_body dashboard-item grid-item">
    
        <div class="card">
         
            <div class="card-header bg-white">
                <a href="{{ admin_url('/') }}">欢迎</a>
                <i class="dash-switch zly-angle-down pull-right"></i>
            </div>
             
            <div class="card-body welcome">
                <div class="row">
                    <div class="userinfo col-2 col-sm-3">
                    {{ suda_avatar($soperate->avatar,'small') }}
                    </div>
                    <div class="col-8 col-sm-9">
                        <p style="font-size:1rem;">
                            {{ $soperate->username }}<br>
                            {{ $soperate->email }}
                        </p>
                        <hr />
                        <p style="font-size:1rem;">
                            {{ $sdcore->settings->company_name }}
                        </p>
                    </div>
                </div>
                
            </div>
             
         </div>
 
 </div> 


 <div class="col-sm-12 mb-3 suda_page_body dashboard-item grid-item">
    
    <div class="card">
     
        <div class="card-header bg-white">
            <a href="{{ admin_url('/') }}">快捷管理</a>
            <i class="dash-switch zly-angle-down pull-right"></i>
        </div>
         
        <div class="card-body welcome">
            <div class="row">
                
                <div class="col-6 col-sm-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('setting/site') }}">
                            <span class="badge text-secondary">
                                <i class="ion-cog"></i>
                            </span>
                            系统设置
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('page/list') }}">
                            <span class="badge text-secondary">
                                <i class="ion-document"></i>
                            </span>
                            页面管理
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('article/list') }}">
                            <span class="badge text-secondary">
                                <i class="ion-folder"></i>
                            </span>
                            文章管理
                        </a>
                    </li>
                </ul>
                </div>
                <div class="col-6 col-sm-6 ">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('theme') }}">
                            <span class="badge text-secondary">
                                <i class="ion-color-palette"></i>
                            </span>
                            模板风格
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('user/roles') }}">
                            <span class="badge text-secondary">
                                <i class="ion-people"></i>
                            </span>
                            角色权限
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('manage/extension') }}">
                            <span class="badge text-secondary">
                                <i class="ion-cube"></i>
                            </span>
                            应用列表
                        </a>
                    </li>
                </ul>
                </div>
            </div>
            
        </div>
         
     </div>

</div>