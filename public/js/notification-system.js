/**
 * ═══════════════════════════════════════════
 *  Notification System — Conquest of Winter
 * ═══════════════════════════════════════════
 *  Handles in-app notification polling and
 *  browser push notification delivery.
 */
const NotificationSystem = (function () {
    let _pollInterval = null;
    let _lastNotifId = 0;
    let _permissionGranted = false;
    let _csrfToken = '';

    /**
     * Initialize the notification system.
     */
    function init() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        _csrfToken = meta ? meta.getAttribute('content') : '';

        // Request browser notification permission
        requestBrowserPermission();

        // Start polling every 60 seconds
        poll(); // immediate first poll
        _pollInterval = setInterval(poll, 60000);
    }

    /**
     * Request browser Notification permission.
     */
    function requestBrowserPermission() {
        if (!('Notification' in window)) return;

        if (Notification.permission === 'granted') {
            _permissionGranted = true;
        } else if (Notification.permission !== 'denied') {
            // Ask after a short delay so it doesn't feel intrusive
            setTimeout(() => {
                Notification.requestPermission().then(perm => {
                    _permissionGranted = (perm === 'granted');
                });
            }, 5000);
        }
    }

    /**
     * Poll the server for new notifications.
     */
    function poll() {
        fetch('/notifications', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateBadge(data.unread_count);
                updateDropdown(data.recent, data.unread_count);

                // Fire browser push for any new notifications
                if (data.unread && data.unread.length > 0) {
                    const newest = data.unread[0];
                    if (newest.id > _lastNotifId && _lastNotifId > 0) {
                        sendBrowserNotification(newest.title, newest.message);
                        playNotificationSound();
                    }
                    _lastNotifId = newest.id;
                }
                // Set initial _lastNotifId on first poll
                if (_lastNotifId === 0 && data.unread && data.unread.length > 0) {
                    _lastNotifId = data.unread[0].id;
                }
            }
        })
        .catch(() => {}); // Silently fail
    }

    /**
     * Update the bell badge count.
     */
    function updateBadge(count) {
        const badge = document.getElementById('notif-badge');
        const bellBtn = document.getElementById('notif-bell-btn');
        if (!badge) return;

        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
            if (bellBtn) bellBtn.classList.add('has-unread');
        } else {
            badge.classList.add('hidden');
            if (bellBtn) bellBtn.classList.remove('has-unread');
        }
    }

    /**
     * Build the notification dropdown HTML.
     */
    function updateDropdown(notifications, unreadCount) {
        const list = document.getElementById('notif-list');
        if (!list) return;

        const emptyState = document.getElementById('notif-empty');
        const markAllBtn = document.getElementById('notif-mark-all');

        if (!notifications || notifications.length === 0) {
            list.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            if (markAllBtn) markAllBtn.classList.add('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (markAllBtn) {
            if (unreadCount > 0) markAllBtn.classList.remove('hidden');
            else markAllBtn.classList.add('hidden');
        }

        list.innerHTML = notifications.map(n => {
            const isRead = n.is_read;
            return `
                <div class="notif-item ${isRead ? 'opacity-50' : ''}" data-id="${n.id}" onclick="NotificationSystem.markRead(${n.id}, this)">
                    <div class="flex items-start gap-3 p-3 rounded-lg transition-all duration-200 cursor-pointer
                        ${isRead ? 'bg-transparent hover:bg-white/5' : 'bg-[var(--accent-glow)] hover:bg-white/10'}">
                        <div class="w-9 h-9 rounded-full bg-black/60 border border-[var(--panel-border)] flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid ${n.icon} text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs font-bold text-white truncate font-cinzel">${n.title}</h4>
                                ${!isRead ? '<span class="w-2 h-2 rounded-full bg-[var(--accent-color)] shrink-0 animate-pulse shadow-[0_0_8px_var(--accent-glow)]"></span>' : ''}
                            </div>
                            <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed mt-0.5 line-clamp-2">${n.message}</p>
                            <span class="text-[9px] text-[var(--text-secondary)] uppercase tracking-wider font-bold mt-1 block">
                                <i class="fa-solid fa-clock mr-1 text-[var(--text-accent)]"></i>${n.time_ago}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    /**
     * Mark a notification as read.
     */
    function markRead(id, element) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': _csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(() => {
            if (element) {
                element.classList.add('opacity-50');
                const dot = element.querySelector('.animate-pulse');
                if (dot) dot.remove();
            }
            // Re-poll to update badge
            setTimeout(poll, 300);
        })
        .catch(() => {});
    }

    /**
     * Mark all notifications as read.
     */
    function markAllRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': _csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(() => {
            poll(); // refresh everything
        })
        .catch(() => {});
    }

    /**
     * Clear (delete) all notifications.
     */
    function clearAll() {
        if (!confirm('Are you sure you want to clear all notifications?')) return;
        
        fetch('/notifications/clear-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': _csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(() => {
            poll(); // refresh everything
        })
        .catch(() => {});
    }

    /**
     * Send a native browser notification.
     */
    function sendBrowserNotification(title, body) {
        if (!_permissionGranted) return;
        if (document.hasFocus()) return; // Don't show if tab is focused

        try {
            const notif = new Notification(title.replace(/[\u{1F000}-\u{1FFFF}]/gu, '').trim(), {
                body: body.replace(/[\u{1F000}-\u{1FFFF}]/gu, '').trim(),
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'conquest-notification',
                renotify: true,
                silent: false
            });

            notif.onclick = function () {
                window.focus();
                notif.close();
            };

            // Auto close after 8 seconds
            setTimeout(() => notif.close(), 8000);
        } catch (e) {
            // SW-based notifications not supported in this context
        }
    }

    /**
     * Play a subtle notification chime.
     */
    function playNotificationSound() {
        // Respect the user's music/mute preference
        const musicEnabled = localStorage.getItem('got_music_enabled');
        if (musicEnabled === '0') return;

        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            
            // Medieval chime: two quick bell-like tones
            const playTone = (freq, startTime, duration) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime + startTime);
                gain.gain.setValueAtTime(0.12, audioCtx.currentTime + startTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + startTime + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(audioCtx.currentTime + startTime);
                osc.stop(audioCtx.currentTime + startTime + duration);
            };

            playTone(880, 0, 0.3);    // A5
            playTone(1174, 0.15, 0.4); // D6
        } catch (e) {
            // Audio not available
        }
    }

    // Public API
    return {
        init,
        poll,
        markRead,
        markAllRead,
        clearAll
    };
})();

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    NotificationSystem.init();
});
