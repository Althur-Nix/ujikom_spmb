<div id="flash-container" style="position:fixed;top:1rem;right:1rem;z-index:1060;">
    @foreach (['success','error','warning','info'] as $key)
        @if(session($key))
            <div class="flash-alert alert alert-{{ $key === 'error' ? 'danger' : $key }} alert-dismissible fade show"
                 role="alert"
                 data-flash-ttl="4000"
                 style="min-width:260px;box-shadow:0 6px 18px rgba(0,0,0,.12);">
                {!! session($key) !!}
                <button type="button" class="btn-close" aria-label="Close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('#flash-container .flash-alert').forEach(function(el){
    const ttl = parseInt(el.dataset.flashTtl || 4000, 10);
    // allow bootstrap fade out then remove
    setTimeout(()=> el.classList.remove('show'), ttl);
    setTimeout(()=> el.remove(), ttl + 300);
  });
});
</script>