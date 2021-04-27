<ul class="nav nav-tabs card-tabs">
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='all')  bg-white active @endif" href="{{ admin_url('media') }}">所有</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='hidden')  bg-white active @endif" href="{{ admin_url('media/hidden') }}">已隐藏</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='image')  bg-white active @endif" href="{{ admin_url('media/images') }}"><i class="ion-image-outline"></i>&nbsp;图片</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='file') bg-white active @endif" href="{{ admin_url('media/files') }}"><i class="ion-document-attach-outline"></i>&nbsp;文件</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($media_tab=='tag') bg-white active @endif" href="{{ admin_url('mediatags') }}"><i class="ion-pricetags-outline"></i>&nbsp;标签</a>
    </li>
    @if($soperate->superadmin==1)
    <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{ admin_url('mediamanager') }}"><i class="ion-folder-outline"></i>&nbsp;打开管理器</a>
    </li>
    @endif
</ul>