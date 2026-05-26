/**
 * ============================================
 *  CONQUEST OF WINTER — GLOBAL MUSIC SYSTEM
 * ============================================
 *  Uses loadedmetadata to restore position.
 *  Uses timeupdate to continuously save position.
 */

(function () {
    'use strict';

    const LS_ENABLED = 'got_music_enabled';
    const LS_VOLUME  = 'got_music_volume';
    const LS_TIME    = 'got_music_time';
    const LS_STARTED = 'got_music_started';

    const DEFAULT_VOLUME  = 0.3;
    const DEFAULT_ENABLED = true;

    function isEnabled() {
        const v = localStorage.getItem(LS_ENABLED);
        return v === null ? DEFAULT_ENABLED : v === '1';
    }
    function getVolume() {
        const v = localStorage.getItem(LS_VOLUME);
        return v === null ? DEFAULT_VOLUME : parseFloat(v);
    }
    function getSavedTime() {
        const v = localStorage.getItem(LS_TIME);
        return v === null ? 0 : parseFloat(v);
    }

    let bgMusic = null;
    let clickSfx = [];
    let musicUnlocked = false;

    // ─── Build audio elements ────────────────────────────
    function ensureAudio() {
        bgMusic = document.getElementById('bgMusic');
        if (!bgMusic) {
            bgMusic = document.createElement('audio');
            bgMusic.id = 'bgMusic';
            bgMusic.loop = true;
            bgMusic.preload = 'auto';
            const src = document.createElement('source');
            src.src = '/music/theme.mpeg';
            src.type = 'audio/mpeg';
            bgMusic.appendChild(src);
            document.body.appendChild(bgMusic);
        }
        bgMusic.loop = true;
        bgMusic.preload = 'auto';
        bgMusic.volume = getVolume();

        // ── SAVE position on every timeupdate ──
        bgMusic.addEventListener('timeupdate', function () {
            if (!bgMusic.paused && !isNaN(bgMusic.currentTime)) {
                localStorage.setItem(LS_TIME, String(bgMusic.currentTime));
            }
        });

        // Click SFX pool
        if (clickSfx.length === 0) {
            for (let i = 0; i < 4; i++) {
                const a = document.createElement('audio');
                a.preload = 'auto';
                a.src = '/music/click.aac';
                document.body.appendChild(a);
                clickSfx.push(a);
            }
        }
    }

    // ─── Restore position & play ─────────────────────────
    // ONLY called after loadedmetadata fires
    function seekAndPlay() {
        if (!bgMusic || !isEnabled()) return;

        const saved = getSavedTime();
        bgMusic.volume = getVolume();

        // Restore position (safe — metadata is loaded)
        if (saved > 0 && bgMusic.duration && saved < bgMusic.duration) {
            bgMusic.currentTime = saved;
        } else {
            bgMusic.currentTime = 0;
        }

        const p = bgMusic.play();
        if (p && p.then) {
            p.then(function () {
                musicUnlocked = true;
                localStorage.setItem(LS_STARTED, '1');
            }).catch(function () {
                // Autoplay blocked — will retry on user click
            });
        }
    }

    // ─── Wait for loadedmetadata, then seek & play ───────
    function resumeMusic() {
        if (!bgMusic || !isEnabled()) return;

        if (bgMusic.readyState >= 1) {
            // Metadata already available
            seekAndPlay();
        } else {
            // Wait for it
            bgMusic.addEventListener('loadedmetadata', function handler() {
                bgMusic.removeEventListener('loadedmetadata', handler);
                seekAndPlay();
            });
            bgMusic.load();
        }
    }

    // ─── Click SFX ───────────────────────────────────────
    let clickIdx = 0;
    function playClick() {
        if (!isEnabled() || clickSfx.length === 0) return;
        const sfx = clickSfx[clickIdx % clickSfx.length];
        sfx.volume = Math.min(getVolume() + 0.15, 1);
        sfx.currentTime = 0;
        sfx.play().catch(function () {});
        clickIdx++;
    }

    // ─── Click listener (capture phase) ──────────────────
    function attachListeners() {
        document.addEventListener('click', function (e) {
            var target = e.target.closest(
                'a, button, [role="button"], .nav-item, .nav-sub-item, ' +
                'input[type="submit"], input[type="checkbox"], input[type="radio"], ' +
                'select, .got-btn, .got-btn-outline, .daily-reward-btn'
            );
            if (target) {
                playClick();
                // Save position right now before navigation
                if (bgMusic && !bgMusic.paused && !isNaN(bgMusic.currentTime)) {
                    localStorage.setItem(LS_TIME, String(bgMusic.currentTime));
                }
            }
        }, true);

        document.addEventListener('submit', function () {
            if (bgMusic && !bgMusic.paused && !isNaN(bgMusic.currentTime)) {
                localStorage.setItem(LS_TIME, String(bgMusic.currentTime));
            }
        }, true);
    }

    // ─── First interaction unlock ────────────────────────
    function attachFirstInteraction() {
        if (localStorage.getItem(LS_STARTED) === '1' && isEnabled()) {
            resumeMusic();
            // Fallback if autoplay blocked
            if (!musicUnlocked) {
                addUnlockListeners();
            }
        } else {
            addUnlockListeners();
        }
    }

    function addUnlockListeners() {
        function unlock() {
            if (musicUnlocked) return;
            resumeMusic();
            document.removeEventListener('click', unlock, true);
            document.removeEventListener('keydown', unlock, true);
            document.removeEventListener('touchstart', unlock, true);
        }
        document.addEventListener('click', unlock, true);
        document.addEventListener('keydown', unlock, true);
        document.addEventListener('touchstart', unlock, true);
    }

    // ─── Public API (settings page) ──────────────────────
    window.MusicSystem = {
        toggle: function (enabled) {
            localStorage.setItem(LS_ENABLED, enabled ? '1' : '0');
            if (enabled) {
                resumeMusic();
            } else if (bgMusic) {
                if (!bgMusic.paused && !isNaN(bgMusic.currentTime)) {
                    localStorage.setItem(LS_TIME, String(bgMusic.currentTime));
                }
                bgMusic.pause();
            }
        },
        setVolume: function (vol) {
            var v = Math.max(0, Math.min(1, vol));
            localStorage.setItem(LS_VOLUME, String(v));
            if (bgMusic) bgMusic.volume = v;
        },
        getState: function () {
            return { enabled: isEnabled(), volume: getVolume() };
        }
    };

    // ─── Bootstrap ───────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        ensureAudio();
        attachListeners();
        attachFirstInteraction();
    });

    // Save on page leave
    window.addEventListener('beforeunload', function () {
        if (bgMusic && !bgMusic.paused && !isNaN(bgMusic.currentTime)) {
            localStorage.setItem(LS_TIME, String(bgMusic.currentTime));
        }
    });
    window.addEventListener('pagehide', function () {
        if (bgMusic && !bgMusic.paused && !isNaN(bgMusic.currentTime)) {
            localStorage.setItem(LS_TIME, String(bgMusic.currentTime));
        }
    });

})();
