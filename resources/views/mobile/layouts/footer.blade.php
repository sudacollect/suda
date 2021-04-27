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
                {{-- <ul class="footer-social-links list-reset">
                    <li>
                        <a href="#">
                            <span class="screen-reader-text">Facebook</span>
                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.023 16L6 9H3V6h3V4c0-2.7 1.672-4 4.08-4 1.153 0 2.144.086 2.433.124v2.821h-1.67c-1.31 0-1.563.623-1.563 1.536V6H13l-1 3H9.28v7H6.023z" fill="#000000"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="screen-reader-text">Twitter</span>
                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 3c-.6.3-1.2.4-1.9.5.7-.4 1.2-1 1.4-1.8-.6.4-1.3.6-2.1.8-.6-.6-1.5-1-2.4-1-1.7 0-3.2 1.5-3.2 3.3 0 .3 0 .5.1.7-2.7-.1-5.2-1.4-6.8-3.4-.3.5-.4 1-.4 1.7 0 1.1.6 2.1 1.5 2.7-.5 0-1-.2-1.5-.4C.7 7.7 1.8 9 3.3 9.3c-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.3 1.6 2.3 3.1 2.3-1.1.9-2.5 1.4-4.1 1.4H0c1.5.9 3.2 1.5 5 1.5 6 0 9.3-5 9.3-9.3v-.4C15 4.3 15.6 3.7 16 3z" fill="#000000"/>
                            </svg>
                        </a>
                    </li>
                </ul> --}}
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