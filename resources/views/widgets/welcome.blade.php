
<div class="col-sm-12 mb-3 suda_page_body dashboard-item grid-item">
    
        <div class="card">
         
            <div class="card-header bg-white">
                <a href="{{ admin_url('/') }}">{{ ucfirst(__('suda_lang::press.welcome')) }}</a>
                <i class="dash-switch ion-chevron-down pull-right"></i>
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
                            {{ $sdcore->settings->site->company_name }}
                        </p>
                    </div>
                </div>
                
            </div>
             
         </div>
 
 </div> 


 <div class="col-sm-12 mb-3 suda_page_body dashboard-item grid-item">
    
    <div class="card">
     
        <div class="card-header bg-white">
            <a href="{{ admin_url('/') }}">{{ __('suda_lang::press.dash.quick_start') }}</a>
            <i class="dash-switch ion-chevron-down pull-right"></i>
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
                            {{ __('suda_lang::press.dash.system_setting') }}
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('page/list') }}">
                            <span class="badge text-secondary">
                                <i class="ion-document"></i>
                            </span>
                            {{ __('suda_lang::press.dash.pages') }}
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('article/list') }}">
                            <span class="badge text-secondary">
                                <i class="ion-folder"></i>
                            </span>
                            {{ __('suda_lang::press.dash.articles') }}
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
                            {{ __('suda_lang::press.dash.themes') }}
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('user/roles') }}">
                            <span class="badge text-secondary">
                                <i class="ion-people"></i>
                            </span>
                            {{ __('suda_lang::press.dash.roles') }}
                        </a>
                    </li>

                    <li class="list-group-item" style="font-size:1rem;padding: .75rem .5rem;">
                        <a href="{{ admin_url('manage/extension') }}">
                            <span class="badge text-secondary">
                                <i class="ion-cube"></i>
                            </span>
                            {{ __('suda_lang::press.dash.extensions') }}
                        </a>
                    </li>
                </ul>
                </div>
            </div>
            
        </div>
         
     </div>

</div>