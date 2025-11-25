<div class="notification-box" style="position:fixed;top:1rem;right:1rem;z-index:1060;display:flex;flex-direction:column;gap:0.5rem;align-items:flex-end;pointer-events:none;"></div>

<style>
.notification-item{
  pointer-events:auto;
  display:flex;align-items:center;
  gap:.6rem;
  min-width:260px;
  max-width:360px;
  color:#fff;
  padding:.6rem .8rem;
  border-radius:.5rem;
  box-shadow:0 6px 20px rgba(0,0,0,.12);
  transform:translateX(120%);
  opacity:0;
  transition:transform .35s ease,opacity .35s ease;
  font-family:system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial;
}
.notification-item .message{flex:1;font-size:.95rem}
.notification-item .btn-close-mini{background:transparent;border:none;color:rgba(255,255,255,.85);cursor:pointer;font-size:1rem}
</style>

<script>
function sendNotification(type, text) {
  const icons = {
    info: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    error: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    warning: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`,
    success: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
  };

  const colors = {
    info: '#0ea5e9',
    error: '#ef4444',
    warning: '#f59e0b',
    success: '#16a34a'
  };

  const box = document.querySelector('.notification-box');
  if (!box) return;

  const item = document.createElement('div');
  item.className = 'notification-item';
  item.style.background = colors[type] || colors.info;

  item.innerHTML = `
    <div class="icon">${icons[type] || icons.info}</div>
    <div class="message">${text}</div>
    <button class="btn-close-mini" aria-label="close">&times;</button>
  `;

  box.appendChild(item);

  // enter animation
  requestAnimationFrame(() => {
    item.style.transform = 'translateX(0)';
    item.style.opacity = '1';
  });

  // close handler
  item.querySelector('.btn-close-mini').addEventListener('click', () => {
    item.style.transform = 'translateX(120%)';
    item.style.opacity = '0';
    setTimeout(()=> item.remove(), 350);
  });

  // auto hide after 4s
  setTimeout(() => {
    item.style.transform = 'translateX(120%)';
    item.style.opacity = '0';
    setTimeout(()=> item.remove(), 350);
  }, 4000);
}

// build flash array in PHP then encode safely for JS
@php
  $flashes = array_filter([
    'success' => session('success'),
    'error'   => session('error'),
    'warning' => session('warning'),
    'info'    => session('info'),
  ]);
@endphp

document.addEventListener('DOMContentLoaded', function () {
  const flashes = @json($flashes);
  for (const [type, msg] of Object.entries(flashes)) {
    if (msg) sendNotification(type, msg);
  }
});
</script>