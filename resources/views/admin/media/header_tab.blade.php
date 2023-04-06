<ul class="nav nav-tabs card-tabs">
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='all')  bg-white active @endif" href="{{ admin_url('media') }}">{{ __('suda_lang::press.medias.all') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='hidden')  bg-white active @endif" href="{{ admin_url('media/hidden') }}">{{ __('suda_lang::press.medias.hidden') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='image')  bg-white active @endif" href="{{ admin_url('media/images') }}"><i class="ion-image-outline"></i>&nbsp;{{ __('suda_lang::press.medias.image') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='file') bg-white active @endif" href="{{ admin_url('media/files') }}"><i class="ion-document-attach-outline"></i>&nbsp;{{ __('suda_lang::press.medias.file') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='tag') bg-white active @endif" href="{{ admin_url('mediatags') }}"><i class="ion-pricetags-outline"></i>&nbsp;{{ __('suda_lang::press.tags.tag') }}</a>
    </li>
    {{-- @if($soperate->superadmin==1)
    <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{ admin_url('mediamanager') }}"><i class="ion-folder-outline"></i>&nbsp;打开管理器</a>
    </li>
    @endif --}}
</ul>