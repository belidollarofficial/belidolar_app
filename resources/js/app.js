/**
 * Beli Dolar - plain JavaScript utilities.
 * Tidak menggunakan Alpine.js.
 */

window.BeliDolar = window.BeliDolar || {};

/**
 * Tampilkan notifikasi toast.
 *
 * @param {string} type success | warning | error | info
 * @param {string} message
 */
window.showToast = function (type, message) {
    var container = document.getElementById('toast-container');
    if (!container) return;

    var icons = {
        success: 'fa-check-circle',
        warning: 'fa-exclamation-circle',
        error: 'fa-times-circle',
        info: 'fa-info-circle',
    };
    var borders = {
        success: 'border-success',
        warning: 'border-warning',
        error: 'border-error',
        info: 'border-info',
    };
    var colors = {
        success: '#22c55e',
        warning: '#f59e0b',
        error: '#ef4444',
        info: '#4f46e5',
    };

    type = type === 'danger' ? 'error' : (type || 'info');

    var toast = document.createElement('div');
    toast.className = 'toast-item ' + (borders[type] || borders.info);
    toast.setAttribute('role', 'status');

    var wrap = document.createElement('div');
    wrap.className = 'flex items-start gap-3';

    var icon = document.createElement('i');
    icon.className = 'fas ' + (icons[type] || icons.info) + ' text-lg mt-0.5';
    icon.style.color = colors[type] || colors.info;

    var body = document.createElement('div');
    body.className = 'flex-1';

    var text = document.createElement('p');
    text.className = 'text-sm text-white';
    text.textContent = message;
    body.appendChild(text);

    var close = document.createElement('button');
    close.type = 'button';
    close.className = 'text-gray-400 hover:text-white transition';
    close.setAttribute('aria-label', 'Tutup notifikasi');
    close.innerHTML = '<i class="fas fa-times"></i>';
    close.addEventListener('click', function () {
        dismiss(toast);
    });

    wrap.appendChild(icon);
    wrap.appendChild(body);
    wrap.appendChild(close);
    toast.appendChild(wrap);
    container.appendChild(toast);

    var timer = setTimeout(function () {
        dismiss(toast);
    }, 5000);

    toast.addEventListener('click', function (e) {
        if (e.target === toast) {
            clearTimeout(timer);
            dismiss(toast);
        }
    });

    function dismiss(el) {
        if (!el.isConnected) return;
        el.classList.add('toast-out');
        setTimeout(function () {
            if (el.parentElement) el.parentElement.removeChild(el);
        }, 300);
    }
};

window.hideToast = function (el) {
    if (el && el.parentElement) el.parentElement.removeChild(el);
};

(function () {
    'use strict';

    function bindLivewireToasts() {
        if (!window.Livewire || typeof window.Livewire.on !== 'function') return;

        window.Livewire.on('toast', function (payload) {
            if (Array.isArray(payload)) payload = payload[0] || {};
            if (!payload) return;
            window.showToast(payload.type || 'info', payload.message || '');
        });
    }

    if (window.Livewire) {
        bindLivewireToasts();
    } else {
        document.addEventListener('livewire:init', bindLivewireToasts, { once: true });
    }

    document.addEventListener('show-toast', function (event) {
        var detail = event.detail || {};
        window.showToast(detail.type || 'info', detail.message || '');
    });
})();
