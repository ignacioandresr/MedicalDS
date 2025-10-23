document.addEventListener('DOMContentLoaded', function () {
    const switchEl = document.getElementById('martian-interface-switch');
    if (! switchEl) return;

    switchEl.addEventListener('change', function (e) {
        if (e.target.checked) {
            window.location.href = window.__visitorWelcomeUrl || '/visitor/welcome/ru';
            return;
        }

        fetch(window.__visitorLeaveUrl || '/visitor/leave', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        }).then(function (resp) {
            window.location.href = '/';
        }).catch(function () {
            window.location.href = '/';
        });
    });
});
