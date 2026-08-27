const FILTERS = [
    'contrast(220%) brightness(65%) saturate(300%) hue-rotate(210deg)',
    'grayscale(100%) contrast(280%) brightness(80%)',
    'sepia(100%) saturate(300%) contrast(200%) hue-rotate(-25deg)',
    'hue-rotate(95deg) saturate(400%) contrast(200%) brightness(110%)',
    'sepia(100%) saturate(700%) hue-rotate(-55deg) contrast(220%) brightness(85%)',
    'hue-rotate(180deg) saturate(450%) contrast(190%) brightness(110%)',
    'invert(90%) hue-rotate(170deg) contrast(220%) saturate(300%)',
    'saturate(600%) contrast(200%) brightness(120%)',
    'grayscale(70%) contrast(300%) brightness(115%) invert(15%)',
    'hue-rotate(260deg) saturate(450%) contrast(210%) brightness(115%)'
];

const TEXT_PRESETS = [
    { type: 'neon', col: '#fff', glow: '#00ffff', blur: 18 },
    { type: 'typewriter', col: '#00ff66', font: 'monospace', size: 38 },
    { type: 'glitch', cols: ['#ff0055', '#00ffff', '#fff'] },
    { type: 'slam', col: '#ffd700', scale: 2.2, blur: 16 },
    { type: 'tracking', col: '#fff', spacing: 16 },
    { type: 'wipe', col: '#fff', streak: true },
    { type: 'gold', grad: ['#ffe066', '#d4af37', '#8a651a'], font: 'Georgia, serif', size: 42 },
    { type: 'rise', col: '#fff', offset: 30 },
    { type: 'matrix', col: '#00ff41', scanline: true, font: 'monospace', size: 38 },
    { type: 'impact', col: '#ff1a40', shake: 5, size: 48 }
];

function setFont(ctx, text, w, base = 44, family = 'Impact, "Arial Black", sans-serif') {
    let s = base;
    ctx.font = `900 ${s}px ${family}`;
    while (ctx.measureText(text).width > w * 0.85 && s > 14) ctx.font = `900 ${--s}px ${family}`;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    return s;
}

function renderTextEffect(ctx, t, p, w, h, e) {
    const s = setFont(ctx, t, w, e.size || 44, e.font);
    ctx.fillStyle = e.col || '#fff';
    ctx.shadowColor = e.glow || '#000';
    ctx.shadowBlur = e.blur || 12;

    switch (e.type) {
        case 'neon':
            ctx.shadowBlur = (e.blur || 15) + Math.sin(p * Math.PI) * 15;
            ctx.fillText(t, w / 2, h / 2);
            break;
        case 'typewriter':
            const len = Math.floor(t.length * Math.min(1, p / 0.7));
            ctx.fillText(t.slice(0, len) + (p < 0.85 && Math.floor(p * 15) % 2 === 0 ? '_' : ''), w / 2, h / 2);
            break;
        case 'glitch':
            const j = (1 - Math.min(1, p / 0.6)) * 20;
            for (let i = 0; i < 5; i++) {
                ctx.save();
                ctx.beginPath();
                ctx.rect(0, h / 2 - s + (i * s * 2 / 5), w, s * 2 / 5);
                ctx.clip();
                const dx = (Math.random() - 0.5) * j;
                ctx.fillStyle = e.cols[i % 2];
                ctx.fillText(t, w / 2 + dx, h / 2);
                ctx.fillStyle = e.cols[2];
                ctx.fillText(t, w / 2 - dx, h / 2);
                ctx.restore();
            }
            break;
        case 'slam':
            const scale = (e.scale || 2) - (1 - Math.pow(1 - Math.min(1, p / 0.5), 3)) * (e.scale - 1);
            ctx.save();
            ctx.translate(w / 2, h / 2);
            ctx.scale(scale, scale);
            setFont(ctx, t, w, e.size || 44, e.font);
            ctx.fillStyle = e.col;
            ctx.fillText(t, 0, 0);
            ctx.restore();
            break;
        case 'tracking':
            ctx.letterSpacing = `${(1 - Math.min(1, p / 0.7)) * (e.spacing || 16)}px`;
            ctx.fillText(t, w / 2, h / 2);
            ctx.letterSpacing = '0px';
            break;
        case 'wipe':
            const prog = Math.min(1, p / 0.7);
            ctx.save();
            ctx.beginPath();
            ctx.rect(0, 0, w * prog, h);
            ctx.clip();
            ctx.fillText(t, w / 2, h / 2);
            ctx.restore();
            if (prog < 1 && e.streak) {
                ctx.fillStyle = 'rgba(255,255,255,0.9)';
                ctx.fillRect(w * prog - 2, 0, 4, h);
            }
            break;
        case 'gold':
            const g = ctx.createLinearGradient(0, h / 2 - s, 0, h / 2 + s);
            e.grad.forEach((c, idx) => g.addColorStop(idx / (e.grad.length - 1), c));
            ctx.fillStyle = g;
            ctx.fillText(t, w / 2, h / 2);
            break;
        case 'rise':
            ctx.fillText(t, w / 2, h / 2 + (1 - Math.min(1, p / 0.7)) * (e.offset || 30));
            break;
        case 'matrix':
            ctx.fillText(t, w / 2, h / 2);
            if (e.scanline) {
                ctx.fillStyle = 'rgba(0,255,65,0.25)';
                ctx.fillRect(0, (p * h * 2) % h, w, 4);
            }
            break;
        case 'impact':
            const k = p < 0.35 ? (Math.random() - 0.5) * (e.shake || 5) : 0;
            ctx.fillText(t, w / 2 + k, h / 2 + k);
            break;
    }
}

let activeId = null, rafId = null, activeVid = null;

function render(vid, cvs, ctx, cfg, isFirst) {
    if (!vid || vid.paused || vid.ended) return;
    ctx.save();
    ctx.clearRect(0, 0, cvs.width, cvs.height);
    ctx.filter = cfg.filter;
    ctx.translate(cvs.width / 2, cvs.height / 2);
    ctx.scale(cfg.zoom, cfg.zoom);
    ctx.translate(-cvs.width / 2, -cvs.height / 2);
    ctx.drawImage(vid, 0, 0, cvs.width, cvs.height);
    ctx.restore();

    if (isFirst && vid.currentTime <= 2.5) {
        const p = vid.currentTime / 2.5, a = Math.min(1, p / 0.15) * (p > 0.75 ? Math.max(0, (1 - p) / 0.25) : 1);
        ctx.save();
        ctx.filter = 'none';
        ctx.globalAlpha = a;
        renderTextEffect(ctx, cfg.text, p, cvs.width, cvs.height, TEXT_PRESETS[cfg.outro]);
        ctx.restore();
    }
    rafId = requestAnimationFrame(() => render(vid, cvs, ctx, cfg, isFirst));
}

function getElements(i) {
    return {
        v1: document.getElementById(`video-${i}`),
        v2: document.getElementById(`video2-${i}`),
        a: document.getElementById(`audio-${i}`),
        c: document.getElementById(`canvas-${i}`),
        playBtn: document.getElementById(`play-btn-${i}`),
        pauseBtn: document.getElementById(`pause-btn-${i}`),
        wrap: document.getElementById(`trailer-${i}`)
    };
}

export function play(i) {
    if (activeId && activeId !== i) pause(activeId);
    const { v1, v2, a, c, playBtn, pauseBtn, wrap } = getElements(i);
    if (!v1 || !c) return;

    activeId = i;
    const d = wrap.dataset, ctx = c.getContext('2d');
    const cfg = {
        filter: FILTERS[(parseInt(d.colorEffect, 10) || 0) % FILTERS.length],
        zoom: 1.0 + ((Math.max(10, Math.min(15, parseFloat(d.zoom) || 10)) - 10) * 0.16),
        outro: (parseInt(d.outro, 10) || 0) % TEXT_PRESETS.length,
        text: (d.intro || d.title || 'TRAILER').toUpperCase().trim()
    };

    const reset = () => {
        if (playBtn) playBtn.style.display = 'flex';
        if (pauseBtn) pauseBtn.style.display = 'none';
        if (v1) v1.currentTime = 0;
        if (v2) v2.currentTime = 0;
        if (a) { a.pause(); a.currentTime = 0; }
        activeVid = null;
        if (activeId === i) activeId = null;
        if (rafId) cancelAnimationFrame(rafId);
        ctx.filter = 'none';
        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, c.width, c.height);
    };

    const start = (vid, isFirst) => {
        activeVid = vid;
        vid.play().then(() => {
            if (playBtn) playBtn.style.display = 'none';
            if (pauseBtn) pauseBtn.style.display = 'flex';
            if (a && a.paused) a.play().catch(() => { });
            if (rafId) cancelAnimationFrame(rafId);
            render(vid, c, ctx, cfg, isFirst);
        }).catch(console.error);
    };

    v1.onended = () => (v2 && v2.getAttribute('src') ? (v2.currentTime = 0, start(v2, false)) : reset());
    if (v2) v2.onended = reset;

    if (activeVid && (activeVid === v1 || activeVid === v2) && !activeVid.ended) start(activeVid, activeVid === v1);
    else if (v1.currentTime > 0 && !v1.ended) start(v1, true);
    else if (v1.ended && v2 && !v2.ended) start(v2, false);
    else {
        v1.currentTime = 0;
        if (v2) v2.currentTime = 0;
        if (a) a.currentTime = 0;
        start(v1, true);
    }
}

export function stop(i) {
    const { v1, v2, a, c, playBtn, pauseBtn } = getElements(i);
    if (v1) { v1.pause(); v1.currentTime = 0; }
    if (v2) { v2.pause(); v2.currentTime = 0; }
    if (a) { a.pause(); a.currentTime = 0; }
    if (playBtn) playBtn.style.display = 'flex';
    if (pauseBtn) pauseBtn.style.display = 'none';
    if (c) {
        const ctx = c.getContext('2d');
        ctx.filter = 'none';
        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, c.width, c.height);
    }
    if (activeId == i) {
        activeId = null;
        activeVid = null;
        if (rafId) cancelAnimationFrame(rafId);
    }
}

export function pause(i) {
    const { v1, v2, a, playBtn, pauseBtn } = getElements(i);
    if (v1) v1.pause();
    if (v2) v2.pause();
    if (a) a.pause();
    if (playBtn) playBtn.style.display = 'flex';
    if (pauseBtn) pauseBtn.style.display = 'none';
    if (rafId) cancelAnimationFrame(rafId);
    if (activeId === i) activeId = null;
}

['hide.bs.collapse', 'hidden.bs.collapse'].forEach(evt => {
    document.addEventListener(evt, e => {
        const id = e.target.id?.replace('row', '');
        if (id) stop(id);
    });
});

document.addEventListener('hide.bs.modal', e => {
    const id = e.target.id?.replace('modal-', '');
    if (id) stop(id);
});

document.addEventListener('click', e => {
    const trigger = e.target.closest('[data-bs-toggle="collapse"]');
    if (trigger) {
        const targetSel = trigger.getAttribute('data-bs-target');
        const targetEl = targetSel ? document.querySelector(targetSel) : null;
        if (targetEl && targetEl.classList.contains('show')) {
            const id = targetEl.id?.replace('row', '');
            if (id) stop(id);
        }
    }
});

window.play = play;
window.pause = pause;
window.stop = stop;
