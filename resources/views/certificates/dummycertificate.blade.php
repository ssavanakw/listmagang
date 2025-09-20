<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no" />
<title>Guitar Hero Mini - Side-by-Side (Fixed)</title>
<style>
  :root {
    --bg: #0b0f14;
    --panel: #121821;
    --accent: #56ccf2;
    --text: #eaf2ff;
    --sub: #9fb3c8;
    --good: #a1ff6a;
    --great: #ffd93d;
    --perfect: #56ccf2;
    --miss: #ff4b4b;
  }
  * { box-sizing: border-box; }
  html, body { height: 100%; }
  body {
    margin: 0; padding: 0;
    background: radial-gradient(1000px 600px at 20% -20%, #1a2230, #0b0f14 60%);
    color: var(--text);
    font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans";
    overflow: hidden;
  }

  /* Layout: menu kiri + gameplay kanan */
  #wrap {
    height: 100dvh;
    display: grid;
    grid-template-columns: 320px 1fr;
    grid-template-rows: 1fr auto;
    gap: 14px;
    padding: 12px;
  }

  /* Panel Menu (kiri) */
  #menu {
    grid-column: 1; grid-row: 1 / span 2;
    display: flex; flex-direction: column; gap: 12px;
    background: rgba(18,24,33,0.78);
    border: 1px solid rgba(136,152,170,0.15);
    border-radius: 14px; padding: 12px;
    backdrop-filter: blur(6px);
    overflow: auto; min-width: 280px;
  }
  .brand {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 10px; border-radius: 10px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(136,152,170,0.15);
  }
  .title { display: flex; align-items: center; gap: 10px; font-weight: 800; }
  .title .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 10px var(--accent); }
  .btn {
    cursor: pointer; border: none; border-radius: 10px;
    background: linear-gradient(180deg, #2a95d6, #1b6ea3);
    color: white; padding: 8px 12px; font-weight: 700; letter-spacing: 0.4px;
    box-shadow: 0 6px 16px rgba(45,156,219,0.25);
    transition: transform .08s ease, filter .08s ease;
  }
  .btn.secondary { background: linear-gradient(180deg, #364155, #232b3a); box-shadow: none; color: #cfe2ff; }
  .btn.small { padding: 6px 10px; }
  .btn:hover { filter: brightness(1.05); }
  .btn:active { transform: translateY(1px) scale(0.99); }

  .section {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(136,152,170,0.15);
    border-radius: 12px; padding: 10px;
    display: grid; gap: 10px;
  }
  .section h3 {
    margin: 2px 2px 6px; font-size: 13px; font-weight: 800; color: #cfe2ff; letter-spacing: .3px;
  }
  .row { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
  .control {
    display: flex; align-items: center; gap: 8px; padding: 6px 10px; border-radius: 10px;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(136,152,170,0.18);
    width: 100%;
  }
  .control.inline { width: auto; }
  .control label { font-size: 12px; color: var(--sub); white-space: nowrap; }
  .control input[type="range"] { width: 140px; }
  .control small { color: var(--sub); font-size: 11px; }
  .control .grow { flex: 1; }
  .status { color: var(--sub); font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

  /* Grup Audio + Offset rapih bertingkat */
  .control-audio { display: grid; gap: 8px; }
  .control-audio .line {
    display: grid; gap: 8px; align-items: center;
    grid-template-columns: auto 1fr auto;
  }
  .control-audio input[type="file"] { width: 100%; }
  .right-val { min-width: 56px; text-align: right; }

  /* Gameplay (kanan) */
  #game {
    grid-column: 2; grid-row: 1;
    position: relative;
    background: linear-gradient(180deg, #0c1118, #0b0f14 60%);
    border: 1px solid rgba(136,152,170,0.15); border-radius: 16px; overflow: hidden;
    min-height: 0;
  }
  #canvas {
    display: block; width: 100%; height: 100%;
    aspect-ratio: 10/15;
    background: transparent;
  }
  #hudTop {
    position: absolute; inset: 22px 10px auto 10px; display: flex; justify-content: space-between; align-items: center; pointer-events: none;
  }
  #leftHUD, #rightHUD { display: flex; gap: 8px; }
  .pill {
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15);
    padding: 6px 10px; border-radius: 999px; font-size: 12px; color: #d7e6ff;
  }
  .pill strong { color: white; }
  #judgement {
    position: absolute; left: 50%; transform: translateX(-50%); top: 24%; font-size: 28px; font-weight: 800;
    text-shadow: 0 4px 18px rgba(0,0,0,0.45); opacity: 0; pointer-events: none; transition: opacity .12s ease, transform .12s ease;
  }

  /* Touch lanes transparent di bawah */
  .touch-areas {
    position: absolute; inset: auto 0 0 0; height: 24%; display: grid; grid-template-columns: repeat(4,1fr); opacity: 0.02;
  }
  .touch-areas div { background: #fff; }

  /* Overlay hasil */
  #overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center;
    backdrop-filter: blur(2px);
  }
  #overlay .card {
    width: min(92vw, 420px); background: rgba(18,24,33,0.96); border: 1px solid rgba(136,152,170,0.25);
    border-radius: 14px; padding: 18px; color: #eaf2ff; text-align: center; box-shadow: 0 20px 80px rgba(0,0,0,0.35);
  }
  #overlay .card h2 { margin: 8px 0 10px; }
  #overlay .kpis { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin: 14px 0; }
  #overlay .kpis .k { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 10px; }
  #overlay .k .label { color: var(--sub); font-size: 12px; }
  #overlay .k .value { font-size: 20px; font-weight: 800; }

  /* Footer melebar di bawah */
  footer {
    grid-column: 2; grid-row: 2;
    display: flex; gap: 10px; align-items: center; justify-content: space-between;
    padding: 10px 12px; background: rgba(18,24,33,0.7);
    border: 1px solid rgba(136,152,170,0.15); border-radius: 12px; backdrop-filter: blur(6px);
    font-size: 12px; color: var(--sub);
  }
  code.key {
    display: inline-flex; align-items: center; justify-content: center; min-width: 22px; padding: 2px 6px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.18); border-radius: 6px; color: #eaf2ff; font-weight: 700;
  }
</style>
</head>
<body>
<div id="wrap">
  <!-- MENU KIRI -->
  <aside id="menu">
    <div class="brand">
      <div class="title"><span class="dot"></span> <span>Guitar Hero Mini</span></div>
    </div>

    <div class="section">
      <h3>Aksi</h3>
      <div class="row">
        <button id="startBtn" class="btn">Start</button>
        <button id="pauseBtn" class="btn secondary">Pause</button>
        <button id="restartBtn" class="btn secondary">Restart</button>
      </div>
    </div>

    <div class="section">
      <h3>Gameplay</h3>
      <div class="control">
        <label>Kecepatan</label>
        <input id="speed" type="range" min="0.8" max="3" step="0.1" value="1.4" />
      </div>
      <div class="control inline">
        <label>Autoplay</label>
        <input id="autoplay" type="checkbox" />
      </div>
    </div>

    <div class="section">
      <h3>Audio</h3>
      <div class="control">
        <label>SFX</label>
        <input id="volume" type="range" min="0" max="1" step="0.01" value="0.35" />
      </div>
      <div class="control">
        <label>Music</label>
        <input id="musicVol" type="range" min="0" max="1" step="0.01" value="0.8" />
      </div>
    </div>

    <div class="section">
      <h3>Lagu & Offset</h3>
      <div class="control control-audio">
        <div class="line">
          <label>Pilih Lagu</label>
          <input id="audioFile" type="file" accept="audio/*" />
          <button id="clearAudioBtn" class="btn secondary small">Hapus</button>
        </div>
        <div class="line">
          <label>Offset (ms)</label>
          <input id="offset" type="range" min="-300" max="300" step="5" value="0" />
          <small id="offsetVal" class="right-val">0 ms</small>
        </div>
      </div>
      <div class="control">
        <label>Status</label>
        <div class="status grow" id="audioStatus">Tidak ada lagu</div>
      </div>
    </div>

    <div class="section">
      <h3>Tips</h3>
      <div class="status">Kontrol: D F J K • Spasi=Pause • R=Restart • M=Mute</div>
      <div class="status">Geser Offset jika not terasa maju/mundur dibanding musik.</div>
    </div>
  </aside>

  <!-- GAMEPLAY KANAN -->
  <main id="game">
    <canvas id="canvas" width="520" height="780"></canvas>

    <div id="hudTop">
      <div id="leftHUD">
        <div class="pill">Skor: <strong id="scoreTxt">0</strong></div>
        <div class="pill">Combo: <strong id="comboTxt">0</strong></div>
      </div>
      <div id="rightHUD">
        <div class="pill">Akurasi: <strong id="accTxt">0.00%</strong></div>
        <div class="pill">Hit: <strong><span id="pTxt" style="color:var(--perfect)">P</span>/<span id="gTTxt" style="color:var(--great)">G</span>/<span id="gDTxt" style="color:var(--good)">g</span>/<span id="mTxt" style="color:var(--miss)">M</span></strong></div>
      </div>
    </div>

    <div id="judgement">Perfect</div>

    <div class="touch-areas" id="touchAreas">
      <div data-lane="0"></div>
      <div data-lane="1"></div>
      <div data-lane="2"></div>
      <div data-lane="3"></div>
    </div>

    <div id="overlay">
      <div class="card" id="resultCard">
        <h2>Hasil</h2>
        <div class="kpis">
          <div class="k"><div class="label">Skor</div><div class="value" id="rScore">0</div></div>
          <div class="k"><div class="label">Akurasi</div><div class="value" id="rAcc">0%</div></div>
          <div class="k"><div class="label">Max Combo</div><div class="value" id="rCombo">0</div></div>
          <div class="k"><div class="label">Hit (P/G/g/M)</div><div class="value"><span id="rP">0</span>/<span id="rGT">0</span>/<span id="rGD">0</span>/<span id="rM">0</span></div></div>
        </div>
        <div style="display:flex; gap:8px; justify-content:center;">
          <button class="btn" id="againBtn">Main Lagi</button>
          <button class="btn secondary" id="closeBtn">Tutup</button>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <div>
      Kontrol: <code class="key">D</code> <code class="key">F</code> <code class="key">J</code> <code class="key">K</code>
      | <code class="key">Spasi</code> Pause | <code class="key">R</code> Restart | <code class="key">M</code> Mute
    </div>
    <div>1 File • Tanpa library</div>
  </footer>
</div>

<script>
(() => {
  // Canvas setup
  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');

  // UI elements
  const scoreTxt = document.getElementById('scoreTxt');
  const comboTxt = document.getElementById('comboTxt');
  const accTxt = document.getElementById('accTxt');
  const pTxt = document.getElementById('pTxt');
  const gTTxt = document.getElementById('gTTxt');
  const gDTxt = document.getElementById('gDTxt');
  const mTxt = document.getElementById('mTxt');
  const startBtn = document.getElementById('startBtn');
  const pauseBtn = document.getElementById('pauseBtn');
  const restartBtn = document.getElementById('restartBtn');
  const speedSlider = document.getElementById('speed');
  const sfxVolSlider = document.getElementById('volume');
  const musicVolSlider = document.getElementById('musicVol');
  const autoplayToggle = document.getElementById('autoplay');
  const overlay = document.getElementById('overlay');
  const againBtn = document.getElementById('againBtn');
  const closeBtn = document.getElementById('closeBtn');
  const rScore = document.getElementById('rScore');
  const rAcc = document.getElementById('rAcc');
  const rCombo = document.getElementById('rCombo');
  const rP = document.getElementById('rP');
  const rGT = document.getElementById('rGT');
  const rGD = document.getElementById('rGD');
  const rM = document.getElementById('rM');

  const judgementEl = document.getElementById('judgement');
  const touchAreas = document.getElementById('touchAreas'); // deklarasi SATU KALI

  const audioFile = document.getElementById('audioFile');
  const clearAudioBtn = document.getElementById('clearAudioBtn');
  const audioStatus = document.getElementById('audioStatus');
  const offsetSlider = document.getElementById('offset');
  const offsetVal = document.getElementById('offsetVal');

  // Device pixel ratio scaling
  function resizeCanvas() {
    const dpr = Math.max(1, window.devicePixelRatio || 1);
    const rect = canvas.getBoundingClientRect();
    canvas.width = Math.round(rect.width * dpr);
    canvas.height = Math.round(rect.height * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }
  window.addEventListener('resize', () => { resizeCanvas(); computeLayout(); }, { passive: true });
  resizeCanvas();

  // Audio setup
  let audioCtx = null;
  let masterGain = null; // final
  let sfxGain = null;    // hit beep
  let musicGain = null;  // music file
  let muted = false;

  function ensureAudio() {
    if (!audioCtx) {
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      masterGain = audioCtx.createGain();
      masterGain.gain.value = muted ? 0 : 1;
      masterGain.connect(audioCtx.destination);

      sfxGain = audioCtx.createGain();
      sfxGain.gain.value = parseFloat(sfxVolSlider.value);
      sfxGain.connect(masterGain);

      musicGain = audioCtx.createGain();
      musicGain.gain.value = parseFloat(musicVolSlider.value);
      musicGain.connect(masterGain);
    }
  }

  sfxVolSlider.addEventListener('input', (e) => { ensureAudio(); sfxGain.gain.value = parseFloat(e.target.value); });
  musicVolSlider.addEventListener('input', (e) => { ensureAudio(); musicGain.gain.value = parseFloat(e.target.value); });

  function playHitSound(lane, judgement='Perfect') {
    if (!audioCtx || muted) return;
    const freqs = [261.63, 329.63, 392.00, 523.25];
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'sine';
    let f = freqs[lane] || 440;
    if (judgement === 'Great') f *= 0.98;
    if (judgement === 'Good') f *= 0.95;
    osc.frequency.value = f;
    gain.gain.setValueAtTime(0.001, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.3, audioCtx.currentTime + 0.005);
    gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.12);
    osc.connect(gain);
    gain.connect(sfxGain);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.15);
  }

  // Music file handling
  let musicBuffer = null;
  let musicSource = null;
  let musicStartCtxTime = 0;
  let musicPlayhead = 0;
  let musicIsPlaying = false;

  function formatTime(s) {
    const m = Math.floor(s/60);
    const ss = Math.floor(s%60).toString().padStart(2,'0');
    return `${m}:${ss}`;
  }
  function updateAudioStatus() {
    if (!musicBuffer) audioStatus.textContent = 'Tidak ada lagu';
    else audioStatus.textContent = `Loaded (${formatTime(musicBuffer.duration)})`;
  }
  async function loadMusicFile(file) {
    try {
      ensureAudio();
      audioStatus.textContent = 'Memuat...';
      const ab = await file.arrayBuffer();
      const buf = await audioCtx.decodeAudioData(ab);
      stopMusic();
      musicBuffer = buf;
      musicPlayhead = 0;
      updateAudioStatus();
    } catch (e) {
      console.error(e);
      musicBuffer = null;
      audioStatus.textContent = 'Gagal memuat file';
    }
  }
  function startMusic() {
    if (!musicBuffer || !audioCtx) return;
    stopMusic();
    musicSource = audioCtx.createBufferSource();
    musicSource.buffer = musicBuffer;
    musicSource.connect(musicGain);
    musicStartCtxTime = audioCtx.currentTime;
    musicSource.start(0, Math.max(0, Math.min(musicBuffer.duration, musicPlayhead)));
    musicIsPlaying = true;
    musicSource.onended = () => { musicIsPlaying = false; };
  }
  function pauseMusic() {
    if (!musicBuffer || !musicSource) return;
    musicPlayhead = Math.min(musicBuffer.duration, musicPlayhead + (audioCtx.currentTime - musicStartCtxTime));
    try { musicSource.stop(); } catch(_) {}
    try { musicSource.disconnect(); } catch(_) {}
    musicSource = null;
    musicIsPlaying = false;
  }
  function stopMusic() {
    if (musicSource) {
      try { musicSource.stop(); } catch(_) {}
      try { musicSource.disconnect(); } catch(_) {}
    }
    musicSource = null;
    musicIsPlaying = false;
  }

  // Game constants and state
  const LANES = 4;
  const KEYS = ['KeyD', 'KeyF', 'KeyJ', 'KeyK'];
  const KEY_LABELS = ['D', 'F', 'J', 'K'];
  const LANE_COLORS = ['#ff4b4b', '#ffd93d', '#4cd964', '#5ac8fa'];
  const HIT_WINDOWS = { perfect: 0.04, great: 0.09, good: 0.14 };
  const NOTE_SCORE = { Perfect: 300, Great: 200, Good: 100, Miss: 0 };

  // Geometry
  const padding = { x: 28, top: 20, bottom: 96 };
  let playfield = { x: padding.x, y: padding.top, w: 0, h: 0, laneW: 0, hitY: 0 };
  function computeLayout() {
    const rect = canvas.getBoundingClientRect();
    const viewW = rect.width;
    const viewH = rect.height;
    const w = viewW - padding.x * 2;
    const h = viewH - (padding.top + padding.bottom);
    const laneW = w / LANES;
    const hitY = padding.top + h - 12;
    playfield = { x: padding.x, y: padding.top, w, h, laneW, hitY };
  }
  computeLayout();

  // Chart generation (contoh default)
  function generateChart() {
    const notes = [];
    const bpm = 120;
    const spb = 60 / bpm;
    let t = 2.0;

    function add(time, lane) { notes.push({ id: notes.length, time, lane, judged: false, hit: false }); }

    for (let i = 0; i < 8; i++) add(t + i * spb, i % 4);
    t += 8 * spb;

    const pattern = [0,1,2,3,1,2,3,0,2,3,1,2,0,3,1,0];
    for (let r = 0; r < 2; r++) {
      for (let i = 0; i < pattern.length; i++) add(t + i * (spb/2), pattern[i]);
      t += pattern.length * (spb/2);
    }

    for (let i = 0; i < 8; i++) {
      const pair = [[0,2],[1,3],[0,3],[1,2]][i % 4];
      add(t + i * spb, pair[0]); add(t + i * spb, pair[1]);
      if (i % 2 === 1) add(t + i * spb - spb/4, i % 4);
    }
    t += 8 * spb;

    for (let b = 0; b < 8; b++) {
      const base = t + b * spb;
      const lanes = (b % 2 === 0) ? [0,1] : [2,3];
      for (let k = 0; k < 3; k++) add(base + k * (spb/3), lanes[k % 2]);
    }
    t += 8 * spb;

    for (let i = 0; i < 16; i++) add(t + i * (spb/2), (3 - (i % 4)));

    const duration = Math.max(...notes.map(n => n.time)) + 2.0;
    const laneNotes = [...Array(LANES)].map(() => []);
    for (const n of notes) laneNotes[n.lane].push(n);
    for (const arr of laneNotes) arr.sort((a,b)=>a.time-b.time);
    return { notes, laneNotes, duration, bpm };
  }

  // Game state variables
  let chart = generateChart();
  let laneIndex = new Array(LANES).fill(0);
  let started = false, paused = false, finished = false;
  let pauseAt = 0;
  let approachBase = 2.2;
  let approach = approachBase / parseFloat(speedSlider.value);
  let keyboard = new Array(LANES).fill(false);
  let laneFlash = new Array(LANES).fill(0);
  let autoplay = false;

  // Timer fallback (tanpa audio file)
  const nowSec = () => performance.now() / 1000;
  let startTime = 0;

  // Global offset (ms)
  let globalOffsetSec = 0;
  offsetSlider.addEventListener('input', (e) => {
    globalOffsetSec = parseInt(e.target.value, 10) / 1000;
    offsetVal.textContent = `${parseInt(e.target.value,10)} ms`;
  });
  offsetVal.textContent = '0 ms';

  // Score stats
  let score = 0;
  let combo = 0, maxCombo = 0;
  let counts = { Perfect: 0, Great: 0, Good: 0, Miss: 0 };
  let maxScore = chart.notes.length * NOTE_SCORE.Perfect;

  function resetGame() {
    chart = generateChart();
    laneIndex = new Array(LANES).fill(0);
    score = 0; combo = 0; maxCombo = 0;
    counts = { Perfect: 0, Great: 0, Good: 0, Miss: 0 };
    maxScore = chart.notes.length * NOTE_SCORE.Perfect;
    paused = false; started = false; finished = false;
    pauseAt = 0;
    autoplay = !!autoplayToggle.checked;
    approach = approachBase / Math.max(0.8, parseFloat(speedSlider.value));
    updateHUD();
    hideOverlay();
    drawFrame(0); // clear frame
  }

  // Waktu lagu/notes: pakai posisi audio jika ada musik, jika tidak pakai timer performa.
  const songTime = () => {
    const off = globalOffsetSec;
    if (!started) return 0;
    if (musicBuffer) {
      let audioPos = musicPlayhead;
      if (!paused && musicIsPlaying) {
        audioPos = musicPlayhead + (audioCtx.currentTime - musicStartCtxTime);
      }
      return Math.max(0, audioPos) - off;
    } else {
      if (paused) return Math.max(0, (pauseAt || nowSec()) - startTime) - off;
      return Math.max(0, nowSec() - startTime) - off;
    }
  };

  function judge(delta) {
    const a = Math.abs(delta);
    if (a <= HIT_WINDOWS.perfect) return 'Perfect';
    if (a <= HIT_WINDOWS.great) return 'Great';
    if (a <= HIT_WINDOWS.good) return 'Good';
    return 'Miss';
  }

  function tryHit(lane, t) {
    const idx = laneIndex[lane];
    const arr = chart.laneNotes[lane];
    if (idx >= arr.length) return false;

    const note = arr[idx];
    const dt = t - note.time;
    if (Math.abs(dt) <= HIT_WINDOWS.good) {
      const res = judge(dt);
      note.judged = true; note.hit = (res !== 'Miss'); note.judgement = res; note.hitTime = t;
      laneIndex[lane]++;
      applyJudgement(lane, res, dt);
      return true;
    }
    return false;
  }

  function applyJudgement(lane, res, delta) {
    if (res === 'Miss') {
      combo = 0;
      counts.Miss++;
      flashJudgement('Miss', 'var(--miss)');
    } else {
      counts[res]++;
      combo += 1;
      maxCombo = Math.max(maxCombo, combo);
      score += NOTE_SCORE[res];
      flashJudgement(res, res === 'Perfect' ? 'var(--perfect)' : res === 'Great' ? 'var(--great)' : 'var(--good)');
      playHitSound(lane, res);
      laneFlash[lane] = nowSec();
    }
    updateHUD();
  }

  function updateHUD() {
    scoreTxt.textContent = score.toLocaleString('id-ID');
    comboTxt.textContent = combo;
    const acc = maxScore ? (score / maxScore) * 100 : 0;
    accTxt.textContent = acc.toFixed(2) + '%';
    pTxt.textContent = counts.Perfect;
    gTTxt.textContent = counts.Great;
    gDTxt.textContent = counts.Good;
    mTxt.textContent = counts.Miss;
  }

  function flashJudgement(text, color) {
    judgementEl.textContent = text;
    judgementEl.style.color = color;
    judgementEl.style.opacity = '1';
    judgementEl.style.transform = 'translateX(-50%) scale(1.05)';
    clearTimeout(judgementEl._t);
    judgementEl._t = setTimeout(() => {
      judgementEl.style.opacity = '0';
      judgementEl.style.transform = 'translateX(-50%) scale(1.0)';
    }, 280);
  }

  function updateMisses(t) {
    for (let l = 0; l < LANES; l++) {
      const arr = chart.laneNotes[l];
      while (laneIndex[l] < arr.length) {
        const n = arr[laneIndex[l]];
        if (t > n.time + HIT_WINDOWS.good) {
          n.judged = true; n.hit = false; n.judgement = 'Miss';
          laneIndex[l]++;
          applyJudgement(l, 'Miss', t - n.time);
        } else break;
      }
    }
  }

  function updateAutoplay(t) {
    if (!autoplay) return;
    for (let l = 0; l < LANES; l++) {
      const idx = laneIndex[l];
      const arr = chart.laneNotes[l];
      if (idx >= arr.length) continue;
      const n = arr[idx];
      const dt = n.time - t;
      if (dt <= 0.0 && Math.abs(dt) <= HIT_WINDOWS.perfect) {
        tryHit(l, t);
      }
    }
  }

  function drawFrame(t) {
    const rect = canvas.getBoundingClientRect();
    const w = rect.width;
    const h = rect.height;

    ctx.clearRect(0, 0, w, h);
    const grd = ctx.createLinearGradient(0, playfield.y, 0, playfield.y + playfield.h);
    grd.addColorStop(0, '#0e141d');
    grd.addColorStop(1, '#0a0f15');
    ctx.fillStyle = grd;
    ctx.fillRect(0, 0, w, h);

    // lanes
    const LCOL = ['#ff4b4b', '#ffd93d', '#4cd964', '#5ac8fa'];
    for (let l = 0; l < LANES; l++) {
      const x = playfield.x + l * playfield.laneW;
      const isHeld = keyboard[l];
      const flashAge = nowSec() - laneFlash[l];
      const flash = Math.max(0, 1 - flashAge * 5);
      ctx.fillStyle = `rgba(255,255,255,${isHeld ? 0.06 : 0.03})`;
      ctx.fillRect(x + 2, playfield.y, playfield.laneW - 4, playfield.h);

      ctx.fillStyle = LCOL[l];
      ctx.globalAlpha = 0.05 + flash * 0.15;
      ctx.fillRect(x + playfield.laneW/2 - 2, playfield.y, 4, playfield.h);
      ctx.globalAlpha = 1;
    }

    // hit line
    ctx.fillStyle = 'rgba(255,255,255,0.25)';
    ctx.fillRect(playfield.x, playfield.hitY, playfield.w, 2);

    // notes
    if(started){
      const approachPxPerSec = playfield.h / approach;
      const NOTE_RATIO = 0.88;  
      const NOTE_MARGIN = 6;     
      const noteW = Math.max(24, Math.min(playfield.laneW - NOTE_MARGIN*2, playfield.laneW * NOTE_RATIO));
      const noteH = 14;  

      for (let l = 0; l < LANES; l++) {
        const arr = chart.laneNotes[l];
        const idx = laneIndex[l];
        const startIdx = Math.max(0, idx - 2);
        for (let i = startIdx; i < arr.length; i++) {
          const n = arr[i];
          if (n.judged && (nowSec() - (n.hitTime || 0)) > 1.0) continue;

          const dt = n.time - t;
          if (dt < -0.6 && n.judged) continue;
          if (dt > approach + 0.2) break;

          const y = playfield.hitY - Math.max(0, (dt * approachPxPerSec));
          const x = playfield.x + l * playfield.laneW + (playfield.laneW - noteW) / 2;

          const alpha = n.judged ? (n.hit ? 0.25 : 0.2) : 1.0;
          ctx.globalAlpha = Math.max(0, Math.min(1, alpha));
          roundRect(ctx, x, y - noteH, noteW, noteH, 8, LCOL[l], true);

          ctx.globalAlpha = 0.8 * alpha;
          roundRect(ctx, x, y - noteH, noteW, noteH, 8, 'rgba(255,255,255,0.18)', false, 2);

          ctx.globalAlpha = 1.0;

        }
      }
    }

    drawKeycaps();

    // progress
    ctx.fillStyle = 'rgba(255,255,255,0.08)';
    ctx.fillRect(playfield.x, playfield.y - 6, playfield.w, 4);
    const progress = Math.min(1, t / chart.duration);
    ctx.fillStyle = 'rgba(255,255,255,0.35)';
    ctx.fillRect(playfield.x, playfield.y - 6, playfield.w * progress, 4);
  }

  function drawKeycaps() {
    const baseY = playfield.hitY + 12;
    const capW = playfield.laneW - 12;
    const capH = 32;
    for (let l = 0; l < LANES; l++) {
      const x = playfield.x + l * playfield.laneW + (playfield.laneW - capW)/2;
      const pressed = keyboard[l];
      const c = ['#ff4b4b', '#ffd93d', '#4cd964', '#5ac8fa'][l];
      const bg = pressed ? c : 'rgba(255,255,255,0.06)';
      roundRect(ctx, x, baseY, capW, capH, 9, bg, true);
      roundRect(ctx, x, baseY, capW, capH, 9, 'rgba(255,255,255,0.15)', false, 1.5);

      ctx.fillStyle = pressed ? '#0b0f14' : '#eaf2ff';
      ctx.font = 'bold 16px system-ui';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(['D','F','J','K'][l], x + capW/2, baseY + capH/2);
    }
  }

  function roundRect(ctx, x, y, w, h, r, color, fill = true, lw = 2) {
    const rr = Math.min(r, w/2, h/2);
    ctx.beginPath();
    ctx.moveTo(x + rr, y);
    ctx.arcTo(x + w, y, x + w, y + h, rr);
    ctx.arcTo(x + w, y + h, x, y + h, rr);
    ctx.arcTo(x, y + h, x, y, rr);
    ctx.arcTo(x, y, x + w, y, rr);
    ctx.closePath();
    if (fill) { ctx.fillStyle = color; ctx.fill(); }
    else { ctx.lineWidth = lw; ctx.strokeStyle = color; ctx.stroke(); }
  }

  // Main loop
  function loop() {
    const t = songTime();
    if (started && !paused) {
      updateMisses(t);
      updateAutoplay(t);
    }
    drawFrame(t);

    if (started && !finished && t >= chart.duration) {
      finished = true;
      stopMusic();
      showResult();
    }
    requestAnimationFrame(loop);
  }

  function showResult() {
    overlay.style.display = 'flex';
    rScore.textContent = score.toLocaleString('id-ID');
    rCombo.textContent = maxCombo;
    rP.textContent = counts.Perfect;
    rGT.textContent = counts.Great;
    rGD.textContent = counts.Good;
    rM.textContent = counts.Miss;
    const acc = maxScore ? (score / maxScore) * 100 : 0;
    rAcc.textContent = acc.toFixed(2) + '%';
  }
  function hideOverlay() { overlay.style.display = 'none'; }

  // Start/Pause/Restart controls
  function startGame() {
    resetGame();
    ensureAudio();
    audioCtx.resume?.();

    started = true;
    paused = false;
    finished = false;

    if (musicBuffer) {
      musicPlayhead = 0;
      startMusic();
    } else {
      startTime = nowSec();
    }
  }
  function togglePause() {
    if (!started || finished) return;
    if (!paused) {
      paused = true;
      pauseAt = nowSec();
      if (musicBuffer) pauseMusic();
    } else {
      paused = false;
      if (musicBuffer) startMusic();
      else startTime += nowSec() - pauseAt;
    }
  }
  function restartGame() {
    ensureAudio();
    stopMusic();
    startGame();
  }

  startBtn.addEventListener('click', () => { if (!started || finished) startGame(); });
  pauseBtn.addEventListener('click', togglePause);
  restartBtn.addEventListener('click', restartGame);

  againBtn.addEventListener('click', () => { hideOverlay(); restartGame(); });
  closeBtn.addEventListener('click', hideOverlay);

  // Keyboard input
  window.addEventListener('keydown', (e) => {
    if (e.repeat) return;
    if (e.code === 'Space') { e.preventDefault(); togglePause(); return; }
    if (e.code === 'KeyR') { e.preventDefault(); restartBtn.click(); return; }
    if (e.code === 'KeyM') { e.preventDefault();
      ensureAudio();
      muted = !muted;
      masterGain.gain.value = muted ? 0 : 1;
      return;
    }

    const lane = KEYS.indexOf(e.code);
    if (lane >= 0) {
      keyboard[lane] = true;
      if (started && !paused && !finished) tryHit(lane, songTime());
    }
  });
  window.addEventListener('keyup', (e) => {
    const lane = KEYS.indexOf(e.code);
    if (lane >= 0) keyboard[lane] = false;
  });

  // Touch input (mobile)
  touchAreas.addEventListener('touchstart', (e) => {
    if (!started) return;
    for (const t of e.changedTouches) {
      const target = document.elementFromPoint(t.clientX, t.clientY);
      const lane = parseInt(target?.getAttribute?.('data-lane'), 10);
      if (!isNaN(lane)) {
        keyboard[lane] = true;
        tryHit(lane, songTime());
      }
    }
    e.preventDefault();
  }, { passive: false });
  touchAreas.addEventListener('touchend', (e) => {
    for (const t of e.changedTouches) {
      const target = document.elementFromPoint(t.clientX, t.clientY);
      const lane = parseInt(target?.getAttribute?.('data-lane'), 10);
      if (!isNaN(lane)) keyboard[lane] = false;
    }
    e.preventDefault();
  }, { passive: false });

  // Sliders / toggles
  speedSlider.addEventListener('input', () => {
    approach = approachBase / Math.max(0.8, parseFloat(speedSlider.value));
    if (!started) drawFrame(0);
  });
  autoplayToggle.addEventListener('change', (e) => { autoplay = e.target.checked; });

  // File handling
  audioFile.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (file) loadMusicFile(file);
  });
  clearAudioBtn.addEventListener('click', () => {
    stopMusic();
    musicBuffer = null;
    musicPlayhead = 0;
    updateAudioStatus();
  });

  // Init
  updateAudioStatus();
  resetGame();
  loop();

})();
</script>
</body>
</html>