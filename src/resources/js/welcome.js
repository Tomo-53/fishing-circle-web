/**
 * トップページ（welcome）専用のインタラクション。
 * app.js または welcome エントリから読み込む。Alpine の起動は呼び出し側に任せる。
 */

const THEMES = ['dawn', 'day', 'night'];

/**
 * 時間帯テーマ手動切替（aria-pressed 連動）
 * @param {'dawn'|'day'|'night'} t
 */
export function setTheme(t) {
    document.documentElement.dataset.theme = t;
    document.querySelectorAll('[data-theme-btn]').forEach((btn) => {
        const active = btn.dataset.themeBtn === t;
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        btn.style.opacity = active ? '1' : '0.55';
    });
    const celestial = document.getElementById('hero-celestial');
    if (celestial) {
        celestial.setAttribute('data-theme-active', t);
        celestial.setAttribute(
            'aria-label',
            `時間帯プレビュー（開発用）: 現在 ${t}。クリックで次のテーマへ`,
        );
    }
}

window.setTheme = setTheme;

/** CTA リップル */
export function addRipple(e) {
    const btn = e.currentTarget;
    const rect = btn.getBoundingClientRect();
    const ripple = document.createElement('span');
    ripple.className = 'ripple-ring';
    ripple.style.cssText = `left:${e.clientX - rect.left}px;top:${e.clientY - rect.top}px`;
    btn.appendChild(ripple);
    setTimeout(() => {
        if (ripple.parentNode) ripple.parentNode.removeChild(ripple);
    }, 900);
}

window.addRipple = addRipple;

function cycleTheme() {
    const current = document.documentElement.dataset.theme || 'day';
    const idx = THEMES.indexOf(current);
    const next = THEMES[(idx + 1) % THEMES.length];
    setTheme(next);
}

// ─── IntersectionObserver scroll reveal ───
function initReveal() {
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 },
    );
    document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
}

// ─── Header scroll ───
function initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;
    window.addEventListener(
        'scroll',
        () => {
            header.classList.toggle('scrolled', window.scrollY > 60);
        },
        { passive: true },
    );
}

// ─── Parallax hero bg ───
function initHeroParallax() {
    const bg = document.getElementById('hero-bg');
    if (!bg || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    window.addEventListener(
        'scroll',
        () => {
            if (window.scrollY < window.innerHeight) {
                bg.style.transform = `translateY(${window.scrollY * 0.22}px)`;
            }
        },
        { passive: true },
    );
}

// ─── Fish scroll parallax + bobbing（ヒーロー可視中のみ rAF） ───
function initFishParallax() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const nodes = document.querySelectorAll('.hero-fish');
    if (!nodes.length) return;

    const fishElements = Array.from(nodes).map((el) => ({
        el,
        speedX: parseFloat(el.dataset.speedX || '0.02'),
        speedY: parseFloat(el.dataset.speedY || '-0.1'),
        bobAmp: parseFloat(el.dataset.bobAmp || '8'),
        bobPeriod: parseFloat(el.dataset.bobPeriod || '4000'),
        phase: Math.random() * Math.PI * 2,
    }));

    let scrollY = window.scrollY;
    let rafId = 0;

    function inHeroRange() {
        return scrollY < window.innerHeight * 1.2;
    }

    function tick(now) {
        if (!inHeroRange()) {
            rafId = 0;
            return;
        }
        fishElements.forEach(({ el, speedX, speedY, bobAmp, bobPeriod, phase }) => {
            const bob = Math.sin((now / bobPeriod) * Math.PI * 2 + phase) * bobAmp;
            el.style.transform = `translate(${scrollY * speedX}px, ${scrollY * speedY + bob}px)`;
        });
        rafId = requestAnimationFrame(tick);
    }

    function ensureRaf() {
        if (!rafId && inHeroRange() && !document.hidden) {
            rafId = requestAnimationFrame(tick);
        }
    }

    window.addEventListener(
        'scroll',
        () => {
            scrollY = window.scrollY;
            ensureRaf();
        },
        { passive: true },
    );

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            if (rafId) cancelAnimationFrame(rafId);
            rafId = 0;
        } else {
            ensureRaf();
        }
    });

    ensureRaf();
    window.addEventListener('pagehide', () => cancelAnimationFrame(rafId), { once: true });
}

// ─── Dev celestial theme cycle ───
function initCelestialToggle() {
    const celestial = document.getElementById('hero-celestial');
    if (!celestial) return;
    celestial.addEventListener('click', cycleTheme);
}

export function bootWelcome() {
    if (window.__welcomeInited) return;
    const main = document.getElementById('main-content');
    if (!main) return;

    main.style.opacity = '1';

    const current = document.documentElement.dataset.theme || 'day';
    setTheme(current);
    initReveal();
    initHeaderScroll();
    initHeroParallax();
    initFishParallax();
    initCelestialToggle();
    window.__welcomeInited = true;
}

function scheduleBoot() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootWelcome);
    } else {
        bootWelcome();
    }
}

scheduleBoot();
