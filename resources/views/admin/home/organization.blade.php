  <div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>
                {{ trans('suda_lang::press.organization') }}
            </th>
            <th>
                {{ trans('suda_lang::press.analytics.count') }}
            </th>
          </tr>
        </thead>
        <tbody>
          @if($orgs)
          @foreach ($orgs as $k=>$org)
          <tr>
            <td>{{ $k }}</td>
            <td>{{ $org['name'] }}</td>
            
            <td>{{ $org['count'] }}</td>
            
          </tr>
          @endforeach
          @endif
        </tbody>
    </table>
    
  </div>