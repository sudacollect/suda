<div class="col-lg-8 mx-auto pb-md-5 px-3">
    <footer class="text-muted border-top d-flex">
        
        <div class="footer-copyright lh-lg">
            &copy; {{ date('Y') }} @if(isset($sdcore->settings->site->company_name)) {{ $sdcore->settings->site->company_name }} @else Suda @endif, all rights reserved
            @if(isset($sdcore->settings->site->icp_number)) &nbsp; <a href="http://www.beian.miit.gov.cn" target="_blank" title="beian">{{ $sdcore->settings->site->icp_number }}</a> @endif
        </div>
    </footer>
</div>
