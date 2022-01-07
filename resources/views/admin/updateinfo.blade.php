@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid py-3">
    <div class="row suda-row">
        
        
        <div class="col-sm-4 offset-sm-4 suda_page_body">
            
                <div class="card mt-5">
                
                    <div class="card-body">
                        
                        <div class="card-title">
                            Suda v{{ $application['version'] }}
                        </div>

                            <p>
                                <strong>core update</strong>
                            </p>
                            <p>
                                <code>composer update gtd/suda</code>
                            </p>
                            
                            <p>
                                <strong>reset assets</strong>
                            </p>
                            <p>
                                <code>php artisan suda:reset assets</code>
                            </p>
    
                            <p>
                                <strong>reset themes</strong>
                            </p>
                            <p>
                                <code>php artisan suda:reset themes</code>
                            </p>
    
                            <p>
                                <strong>update extension</strong>
                            </p>
                            <p>
                                <code>php artisan suda:ext ext-slug-name</code>
                            </p>
    
                            <p>
                                <strong>update database</strong>
                            </p>
                            <p>
                                <code>php artisan migrate</code>
                            </p>
                        

                    </div>
                    
                </div>
            
        </div>
        





        
        
    </div>
</div>



@endsection

