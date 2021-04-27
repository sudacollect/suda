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
                                <strong>核心更新</strong>
                            </p>
                            <p>
                                <code>composer update gtd/suda</code>
                            </p>
                            
                            <p>
                                <strong>更新图片/样式表资源</strong>
                            </p>
                            <p>
                                <code>php artisan suda:reset assets</code>
                            </p>
    
                            <p>
                                <strong>更新 themes 文件</strong>
                            </p>
                            <p>
                                <code>php artisan suda:reset themes</code>
                            </p>
    
                            <p>
                                <strong>更新应用</strong>
                            </p>
                            <p>
                                <code>php artisan suda:ext flush 应用Slug</code>
                            </p>
    
                            <p>
                                <strong>更新数据库/表</strong>
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

