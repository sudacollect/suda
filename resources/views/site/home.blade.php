@extends('view_app::layouts.default')

@section('content')

<div class="col-lg-8 mx-auto px-3 py-md-5">
    
  
    <main>
      <h1>{{ __('suda_lang::demo.kv_title') }}</h1>
      <p class="fs-5 col-md-8">
        {{ __('suda_lang::demo.kv_description') }}
      </p>
  
      <div class="mb-5">
        <a href="https://suda.gtd.xyz" class="btn btn-outline-primary px-4">{{ __('suda_lang::demo.kv_btn') }}</a>
      </div>
  
      <hr class="col-3 col-md-2 mb-5">
  
      <div class="row g-5">
        <div class="col-md-6">
          <h2>{{ __('suda_lang::demo.start_project') }}</h2>
          <p>
            {{ __('suda_lang::demo.start_project_description') }}
          </p>
          <ul class="icon-list ps-0">
            <li class="d-flex align-items-start mb-1">{{ __('suda_lang::demo.start_project_item1') }}</li>
            <li class="text-muted d-flex align-items-start mb-1">{{ __('suda_lang::demo.start_project_item2') }}</li>
            <li class="text-muted d-flex align-items-start mb-1">{{ __('suda_lang::demo.start_project_item3') }}</li>
          </ul>
        </div>
  
        <div class="col-md-6">
          <h2>{{ __('suda_lang::demo.guides') }}</h2>
          
          <ul class="icon-list ps-0">
            <li class="d-flex align-items-start mb-1"><a href="http://docs.gtd.xyz/install">{{ __('suda_lang::demo.guide_install') }}</a></li>
            <li class="d-flex align-items-start mb-1"><a href="http://docs.gtd.xyz/basic/config/">{{ __('suda_lang::demo.guide_config') }}</a></li>
            <li class="d-flex align-items-start mb-1"><a href="http://docs.gtd.xyz/basic/extensions/">{{ __('suda_lang::demo.guide_extension') }}</a></li>
            <li class="d-flex align-items-start mb-1"><a href="http://docs.gtd.xyz/basic/theme/">{{ __('suda_lang::demo.guide_theme') }}</a></li>
          </ul>
        </div>
      </div>
    </main>
    
  </div>

@endsection