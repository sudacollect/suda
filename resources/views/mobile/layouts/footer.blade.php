<div class="col-lg-8 mx-auto pb-md-5 px-3">
    <footer class="text-muted border-top">
        <a href="https://suda.gtd.xyz">
            <img src="{{ suda_asset('images/site/logo_blue_only.png') }}" style="width:30px;">
        </a>
        <div class="footer-copyright" style="justify-content:flex-end;">
            &copy; {{ date('Y') }} @if(isset($sdcore->settings->site->company_name)) {{ $sdcore->settings->site->company_name }} @else Suda @endif, all rights reserved
            @if(isset($sdcore->settings->site->icp_number)) &nbsp; <a href="http://www.beian.miit.gov.cn" target="_blank" title="beian">{{ $sdcore->settings->icp_number }}</a> @endif

        </div>
    </footer>
</div>