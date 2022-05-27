

<div class="col-sm-12 mb-3  suda_page_body dashboard-item grid-item">
    
      <div class="card">

        <div class="card-header bg-white">
            <a href="{{ $product['permalink'] }}">更新日志</a>
            <i class="dash-switch ion-chevron-down pull-right"></i>
        </div>
        <div class="card-body newsfeed">
            <ul class="list-group list-group-flush">
            @foreach ($product['items'] as $item)
            
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ $item->get_permalink() }}" target=_blank>{{ $item->get_title() }}</a>
                <small>{{ $item->get_date('Y-m-d') }}</small>
              </li>
            @endforeach
              </ul>
        </div>

      </div>

</div>









       