<div class="col-lg-8 mx-auto pb-md-5 px-3">
    <footer class="text-muted border-top d-flex">
        
        <div class="footer-copyright lh-lg">
            &copy; {{ date('Y') }} @if(isset($sdcore->settings->company_name)) {{ $sdcore->settings->company_name }} @else Suda @endif, all rights reserved
            @if(isset($sdcore->settings->icp_number)) &nbsp; <a href="http://www.beian.miit.gov.cn" target="_blank" title="beian">{{ $sdcore->settings->icp_number }}</a> @endif
        </div>
    </footer>
</div>
