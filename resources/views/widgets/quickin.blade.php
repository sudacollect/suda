
<div class="col-sm-12 mb-3 suda_page_body dashboard-item grid-item">
    
       <div class="card">
        
            <div class="card-header bg-white">
                <a href="{{ admin_url('manage/extension') }}"><i class="ion-file-tray-full"></i>&nbsp;应用</a>
                <i class="dash-switch zly-angle-down pull-right"></i>
            </div>
            @if(isset($exts))
                <div class="card-body app_quickin">
                    <div class="row px-3">
                    @foreach ($exts as $item)
                    <ul class="col-2 list-group list-group-horizontal list-group-flush mb-3">
                      <li class="list-group-item">
                          <div class="quickin-content">
                            <div class="quickin">
                                @if(isset($item['default_page_url']))
                                <a class="app-info" href="{{ admin_url($item['default_page_url']) }}" title="{{ $item['name'] }}">
                                    <img src="{{ extension_logo($item['slug']) }}" class="icon icon-extension">
                                    <span>{{ $item['name'] }}</span>
                                </a>
                                @endif
                            </div>
                          </div>
                      </li>

                     </ul>
                     @endforeach
                    </div>
                </div>
            @endif
        </div>

</div>






       