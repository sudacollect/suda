<footer class="site-footer bg-dark text-light">
    <div class="container">
        <div class="site-footer-inner">
            <div class="brand footer-brand">
                <a href="https://suda.gtd.xyz">
                    <img src="{{ suda_asset('images/site/logo_white_only.png') }}" style="width:30px;">
                </a>
            </div>
            
            {{-- <ul class="footer-social-links list-reset">
                
            </ul> --}}
            <div class="footer-copyright" style="justify-content:flex-end;">
                &copy; {{ date('Y') }} @if(isset($sdcore->settings->company_name)) {{ $sdcore->settings->company_name }} @else Suda @endif, all rights reserved
                @if(isset($sdcore->settings->icp_number)) &nbsp; <a href="http://www.beian.miit.gov.cn" target="_blank" title="beian">{{ $sdcore->settings->icp_number }}</a> @endif

            </div>
        </div>
    </div>
</footer>