<div class="page-heading">
    <h1 class="page-title">
        <i class="ion-image"></i>
        {{ __('suda_lang::press.menu_items.media') }}
    </h1>
    <button id="show-media-upload" class="btn btn-primary btn-sm">
        <i class="ion-arrow-up"></i>&nbsp;{{ __('suda_lang::press.upload') }}
    </button>
    {{-- <a href="{{ admin_url('media/gallery') }}" class="btn btn-dark btn-sm ms-auto me-2">
        <i class="ion-grid"></i>
    </a> --}}
    {{-- <a href="{{ admin_url('media/list') }}" class="btn btn-dark btn-sm ms-auto me-2">
        <i class="ion-list"></i>
    </a> --}}
    <a href="{{ admin_url('media/setting') }}" class="pop-modal btn btn-warning ms-auto btn-sm">
        {{ __('suda_lang::press.medias.setting') }}
    </a>
    
</div>