@if (session('status'))

<div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index:9999;">
    <div class="toast-container toast-container position-absolute top-0 end-0 p-3" id="toastPlacement">
        <div class="suda-toast toast" role="alert" aria-live="assertive" aria-atomic="true" @if(isset(session('status')['autohide'])) data-bs-autohide="session('status')['autohide']" @else data-bs-autohide="true" @endif @if(isset(session('status')['delay'])) data-bs-delay="{{ session('status')['delay'] }}" @else data-bs-delay="2500" @endif >
            <div class="d-flex">
                <div class="toast-body">
                    {!! session('status')['msg'] !!}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

@endif