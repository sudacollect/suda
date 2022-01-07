<footer class="site-footer text-light">
        <div class="container">
            <div class="site-footer-inner">
                <div class="brand footer-brand">
                    <a href="https://suda.gtd.xyz">
                        
                        <img src="{{ suda_asset('images/site/logo_white_only.png') }}" style="width:30px;">
                        
                    </a>
                </div>
                <ul class="footer-links list-reset">
                    <li>
                        <a href="https://suda.gtd.xyz">文档</a>
                    </li>
                    <li>
                        <a href="https://suda.gtd.xyz">关于</a>
                    </li>
                </ul>

                <div class="footer-copyright">
                    &copy; {{ date('Y') }} @if(isset($sdcore->settings->company_name)) {{ $sdcore->settings->company_name }} @else Suda @endif, all rights reserved
                </div>
    
                @if(isset($sdcore->settings->icp_number))
                <div class="footer-copyright">
                    <a href="http://www.beian.miit.gov.cn" target="_blank" title="备案">{{ $sdcore->settings->icp_number }}</a>
                </div>
                @endif
                
            </div>
        </div>
    </footer>