@if(!isset($without_card))
<div class="col-sm-12 ">
    <div class="card">
        
        <div class="card-body">
@endif            
            <div class="row z-empty @if(isset($type)) z-empty-{{ $type }} @endif">
                <div class="empty-icon"></div>
                <p>
                    @if(isset($empty))
                    {{ $empty }}
                    @else
                    Oops.. 还没有内容
                    @endif
                </p>
    
            </div>
@if(!isset($without_card))            
        </div>
    </div>
</div>
@endif 