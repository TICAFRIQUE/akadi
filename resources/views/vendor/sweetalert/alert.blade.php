@php
    $hasDelete = Session::has('alert.delete');
    $hasAlert  = Session::has('alert.config');
    $alertJson = $hasAlert ? Session::pull('alert.config') : null;
    $deleteJson= $hasDelete ? Session::pull('alert.delete') : null;
    $alertData = $alertJson ? json_decode($alertJson, true) : null;
@endphp

@if ($hasDelete || $hasAlert)

{{-- SweetAlert2 JS (pour confirm-delete uniquement) --}}
@if ($hasDelete)
<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endif

{{-- ── Toast Akadi ── --}}
@if ($alertData)
@php
    $icon    = $alertData['icon']  ?? 'info';
    $title   = $alertData['title'] ?? '';
    $timer   = $alertData['timer'] ?? 4000;
@endphp
<style>
.ak-toast-wrap {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
}
.ak-toast {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-width: 300px;
    max-width: 380px;
    padding: 16px 18px 16px 16px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.08);
    border-left: 4px solid #f85d05;
    pointer-events: all;
    position: relative;
    overflow: hidden;
    animation: ak-toast-in .35s cubic-bezier(0.34,1.56,0.64,1) both;
}
.ak-toast.ak-toast-error   { border-left-color: #eb0029; }
.ak-toast.ak-toast-warning { border-left-color: #f59e0b; }
.ak-toast.ak-toast-info    { border-left-color: #3b82f6; }
.ak-toast.ak-toast-out {
    animation: ak-toast-out .3s ease forwards;
}
@keyframes ak-toast-in {
    from { opacity:0; transform: translateX(60px) scale(.95); }
    to   { opacity:1; transform: translateX(0)    scale(1);   }
}
@keyframes ak-toast-out {
    from { opacity:1; transform: translateX(0)    scale(1);   max-height:120px; margin-bottom:0; }
    to   { opacity:0; transform: translateX(60px) scale(.95); max-height:0;    margin-bottom:-10px; }
}
.ak-toast-icon {
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.ak-toast-success .ak-toast-icon { background: rgba(248,93,5,.12); color: #f85d05; }
.ak-toast-error   .ak-toast-icon { background: rgba(235,0,41,.1);  color: #eb0029; }
.ak-toast-warning .ak-toast-icon { background: rgba(245,158,11,.1);color: #f59e0b; }
.ak-toast-info    .ak-toast-icon { background: rgba(59,130,246,.1); color: #3b82f6; }
.ak-toast-body {
    flex: 1;
    min-width: 0;
}
.ak-toast-label {
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 3px;
}
.ak-toast-success .ak-toast-label { color: #f85d05; }
.ak-toast-error   .ak-toast-label { color: #eb0029; }
.ak-toast-warning .ak-toast-label { color: #f59e0b; }
.ak-toast-info    .ak-toast-label { color: #3b82f6; }
.ak-toast-msg {
    font-size: .88rem;
    font-weight: 600;
    color: #1a1a1a;
    line-height: 1.4;
}
.ak-toast-close {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    border: none;
    background: none;
    cursor: pointer;
    color: #bbb;
    font-size: .8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all .15s;
    padding: 0;
    margin-top: -2px;
}
.ak-toast-close:hover { background: #f5f5f5; color: #555; }
/* Barre de progression */
.ak-toast-progress {
    position: absolute;
    bottom: 0; left: 0;
    height: 3px;
    border-radius: 0 0 14px 14px;
    animation: ak-progress linear forwards;
}
.ak-toast-success .ak-toast-progress { background: linear-gradient(90deg, #f85d05, #eb0029); }
.ak-toast-error   .ak-toast-progress { background: #eb0029; }
.ak-toast-warning .ak-toast-progress { background: #f59e0b; }
.ak-toast-info    .ak-toast-progress { background: #3b82f6; }
@keyframes ak-progress {
    from { width: 100%; }
    to   { width: 0%; }
}
/* Mobile */
@media (max-width: 576px) {
    .ak-toast-wrap {
        top: auto;
        bottom: calc(68px + env(safe-area-inset-bottom, 0px) + 10px);
        right: 12px;
        left: 12px;
    }
    .ak-toast { min-width: 0; max-width: 100%; }
}
</style>

<div class="ak-toast-wrap" id="ak-toast-wrap"></div>

<script>
(function () {
    var icon  = @json($icon);
    var title = @json($title);
    var timer = {{ (int)$timer }};

    var iconMap = {
        success: { cls: 'ak-toast-success', label: 'Succès',      fa: 'fas fa-check-circle' },
        error:   { cls: 'ak-toast-error',   label: 'Erreur',      fa: 'fas fa-times-circle'  },
        warning: { cls: 'ak-toast-warning', label: 'Attention',   fa: 'fas fa-exclamation-circle' },
        info:    { cls: 'ak-toast-info',    label: 'Information', fa: 'fas fa-info-circle'   },
    };
    var cfg = iconMap[icon] || iconMap['info'];

    var wrap = document.getElementById('ak-toast-wrap');
    if (!wrap) return;

    var toast = document.createElement('div');
    toast.className = 'ak-toast ' + cfg.cls;
    toast.innerHTML =
        '<div class="ak-toast-icon"><i class="' + cfg.fa + '"></i></div>' +
        '<div class="ak-toast-body">' +
            '<div class="ak-toast-label">' + cfg.label + '</div>' +
            '<div class="ak-toast-msg">' + title + '</div>' +
        '</div>' +
        '<button class="ak-toast-close" aria-label="Fermer"><i class="fas fa-times"></i></button>' +
        '<div class="ak-toast-progress" style="animation-duration:' + timer + 'ms"></div>';

    wrap.appendChild(toast);

    function dismiss() {
        toast.classList.add('ak-toast-out');
        toast.addEventListener('animationend', function () { toast.remove(); }, { once: true });
    }

    toast.querySelector('.ak-toast-close').addEventListener('click', dismiss);
    setTimeout(dismiss, timer);
})();
</script>
@endif

{{-- Confirm delete (garde SweetAlert2) --}}
@if ($hasDelete)
<script>
document.addEventListener('click', function (event) {
    if (event.target.matches('[data-confirm-delete]')) {
        event.preventDefault();
        Swal.fire({!! $deleteJson !!}).then(function (result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.action = event.target.href;
                form.method = 'POST';
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>
@endif

@endif
