@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        <h1 class="display-3 page-title page-tabs-title">{{ __('suda_lang::press.menu_items.tool_compass') }}</h1>
        
        @include('view_suda::admin.compass.tabs',['active'=>'index'])
        
        <div class="col-12 suda_page_body compass-commands">
            
                <div class="card">
                
                    <div class="card-body">
                        
                        @if(isset($artisan_output) && !empty($artisan_output))
                            <pre>
                               <i class="close-output ion-close-circle"></i>
                               <div class="output-title">命令执行结果:</div>
                               <div class="output-content">
                                   {{ trim(trim($artisan_output,'"')) }}
                               </div>
                            </pre>
                        @endif
                        
                        <div class="col-sm-12">
                        <ul class="list-group list-group-horizontal mb-3">
                        @foreach($commands as $command)
                        
                        	<li class="list-group-item w-25 px-0 py-0">
                                <div class="command px-2 py-2" data-command="{{ $command->name }}">
                            		<code>php artisan {{ $command->name }}</code>
                            		<small>{{ $command->description }}</small>
                                    <i class="ion-play"></i>
                            		<form action="{{ route('sudaroute.admin.tool_compass_commands_post') }}" class="command-form" method="POST">
                                        @csrf
                                        <input type="text" name="args" autofocus class="form-control" placeholder="填写参数">
                                        <input type="submit" class="btn btn-primary pull-right delete-confirm" value="运行">
                                        <input type="hidden" name="command" id="hidden_cmd" value="{{ $command->name }}">
                                    </form>
                                </div>
                        	</li>
                            @if($loop->iteration%4==0)
                                </ul>
                                </div>
                                <div class="col-sm-12">
                                <ul class="list-group list-group-horizontal mb-3">
                            
                            @endif
                        
                        	
                        @endforeach
                        </ul>
                        </div>

                    </div>
                </div>
            
        </div>
        

        
        
    </div>
</div>
@endsection

@push('scripts')

<script type="text/javascript"> 
$(document).ready(function(){
    $('.compass-commands').find('.command').on('click',function(){
        var command_form = $(this).find('.command-form');
        $('.compass-commands').find('.command-form').not(command_form).slideUp();
        
        
        $(this).find('.command-form').slideDown();
    });
    
    $('.close-output').click(function(){
        $(this).parent('pre').slideUp();
    });
});
</script>

@endpush