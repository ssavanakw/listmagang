<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Mini Racing — Single File</title>
  <style>
    :root{
      --bg:#0b1220;
      --road:#2a2f36;
      --stripe:#cfcfcf;
      --panel:#0f1724;
      --accent:#ff4d4d;
      --text:#e6eef8;
    }
    html,body{height:100%;margin:0;font-family:Inter,system-ui,Arial;background:linear-gradient(180deg,#071021 0%, #0b1220 100%);color:var(--text)}
    .wrap{display:flex;height:100vh;gap:16px;padding:16px;box-sizing:border-box}
    .game{
      flex:1;min-width:300px;display:flex;align-items:center;justify-content:center;
      background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);
      border-radius:12px;padding:12px;box-shadow:0 10px 30px rgba(2,6,23,0.6)
    }
    canvas{border-radius:8px;background:linear-gradient(#1b2330,#11161b);display:block}
    .sidebar{width:320px;max-width:40%;display:flex;flex-direction:column;gap:12px}
    .card{background:rgba(255,255,255,0.03);padding:12px;border-radius:10px;box-shadow:inset 0 -4px 8px rgba(0,0,0,0.4)}
    h1{font-size:18px;margin:0 0 6px 0}
    .stats{display:flex;flex-direction:column;gap:8px}
    .stat-row{display:flex;justify-content:space-between;font-size:14px}
    .controls{display:flex;gap:8px;flex-wrap:wrap}
    button{background:var(--accent);color:white;border:none;padding:8px 10px;border-radius:8px;cursor:pointer;font-weight:600}
    button.secondary{background:#1f2937}
    .meter{height:10px;background:rgba(255,255,255,0.06);border-radius:6px;overflow:hidden}
    .meter > span{display:block;height:100%;background:linear-gradient(90deg,var(--accent),#ffa8a8);width:50%}
    .mobile-controls{display:none;gap:8px;margin-top:8px}
    .touch-btn{flex:1;padding:12px;border-radius:10px;background:rgba(255,255,255,0.03);text-align:center;user-select:none}
    footer{font-size:12px;opacity:0.7;margin-top:auto}
    @media (max-width:900px){
      .wrap{flex-direction:column}
      .sidebar{width:100%;max-width:100%}
      .mobile-controls{display:flex}
    }
    /* tiny HUD inside canvas will be drawn by canvas too for crisp look */
  </style>
</head>
<body>
  <div class="wrap">
    <div class="game card">
      <canvas id="gameCanvas" width="720" height="1080" aria-label="Mini Racing Game"></canvas>
    </div>

    <div class="sidebar">
      <div class="card">
        <h1>Mini Racing</h1>
        <div class="stats">
          <div class="stat-row"><span>Score</span><strong id="ui-score">0</strong></div>
          <div class="stat-row"><span>Speed</span><strong id="ui-speed">0</strong></div>
          <div class="stat-row"><span>Health</span><strong id="ui-health">100</strong></div>
          <div class="stat-row"><span>Distance</span><strong id="ui-distance">0 m</strong></div>
          <div class="stat-row"><span>Active</span><strong id="ui-active">—</strong></div>
          <div>
            <div class="meter"><span id="ui-health-bar" style="width:100%"></span></div>
          </div>
        </div>
        <div style="height:8px"></div>
        <div class="controls">
          <button id="btn-start">Start</button>
          <button id="btn-restart" class="secondary">Restart</button>
          <button id="btn-toggle-sound" class="secondary">Sound: On</button>
        </div>
        <div style="height:8px"></div>
        <p style="margin:0;font-size:13px;line-height:1.3">Kontrol: ← → (gerak), ↑ Nitro, ↓ Rem. On-screen buttons tersedia di perangkat mobile.</p>
      </div>

      <div class="card">
        <h1>Power-ups / Behavior</h1>
        <ul style="margin:6px 0 0 18px;padding:0;line-height:1.5">
          <li>Green box: Health +20</li>
          <li>Blue box: Nitro (short speed burst)</li>
          <li>Obstacles: Hindari atau kena damage</li>
        </ul>
      </div>

      <div class="card mobile-controls">
        <div style="display:flex;gap:8px">
          <div class="touch-btn" id="touch-left">◀</div>
          <div class="touch-btn" id="touch-boost">▲</div>
          <div class="touch-btn" id="touch-right">▶</div>
        </div>
      </div>

      <footer class="card">
        Tekan tombol Start untuk memulai. Kode ini mudah dimodifikasi — ubah kecepatan spawn, warna, ukuran mobil, dll.
      </footer>
    </div>
  </div>

<script>
/*
  Mini Racing — single file.
  Clean, commented, and self-contained.
*/

(() => {
  // Canvas setup
  const canvas = document.getElementById('gameCanvas');
  const ctx = canvas.getContext('2d');
  const W = canvas.width, H = canvas.height;

  // UI elements
  const uiScore = document.getElementById('ui-score');
  const uiSpeed = document.getElementById('ui-speed');
  const uiHealth = document.getElementById('ui-health');
  const uiDistance = document.getElementById('ui-distance');
  const uiActive = document.getElementById('ui-active');
  const uiHealthBar = document.getElementById('ui-health-bar');
  const btnStart = document.getElementById('btn-start');
  const btnRestart = document.getElementById('btn-restart');
  const btnToggleSound = document.getElementById('btn-toggle-sound');

  // Mobile touch controls
  const touchLeft = document.getElementById('touch-left');
  const touchRight = document.getElementById('touch-right');
  const touchBoost = document.getElementById('touch-boost');

  // Game constants
  const LANES = 3;
  const ROAD_WIDTH = Math.min(520, W * 0.6);
  const ROAD_X = (W - ROAD_WIDTH) / 2;
  const LANE_WIDTH = ROAD_WIDTH / LANES;
  const PLAYER_WIDTH = Math.min(70, LANE_WIDTH * 0.62);
  const PLAYER_HEIGHT = Math.min(120, LANE_WIDTH * 1.0);
  const MAX_HEALTH = 100;

  // Game state
  let running = false;
  let lastTime = 0;
  let accumDist = 0;
  let score = 0;
  let speed = 6; // base world speed (px per frame)
  let speedScale = 1; // multiplier temporary (nitro)
  let difficulty = 1;
  let spawnTimer = 0;
  let spawnInterval = 1000; // ms
  let entities = []; // enemies, powerups
  let keys = {};
  let player = {
    lane: 1, // 0..LANES-1
    x: 0, y: H - PLAYER_HEIGHT - 80,
    w: PLAYER_WIDTH, h: PLAYER_HEIGHT,
    health: MAX_HEALTH,
    color: '#ff4d4d',
    speedX: 0,
    nitro: 0 // nitro cooldown / amount
  };
  let soundOn = true;

  // Audio setup (simple beeps)
  const AudioCtx = window.AudioContext || window.webkitAudioContext;
  const audioCtx = AudioCtx ? new AudioCtx() : null;

  function playBeep(freq=440, duration=0.08, gain=0.06) {
    if(!audioCtx || !soundOn) return;
    const o = audioCtx.createOscillator();
    const g = audioCtx.createGain();
    o.type = 'sine';
    o.frequency.value = freq;
    g.gain.value = gain;
    o.connect(g); g.connect(audioCtx.destination);
    o.start();
    o.stop(audioCtx.currentTime + duration);
  }

  // Util functions
  function laneCenter(l) { return ROAD_X + LANE_WIDTH * l + LANE_WIDTH / 2; }

  function resetGame() {
    running = false;
    lastTime = performance.now();
    accumDist = 0;
    score = 0;
    speed = 6;
    speedScale = 1;
    difficulty = 1;
    spawnTimer = 0;
    spawnInterval = 900;
    entities = [];
    player.lane = 1;
    player.x = laneCenter(player.lane) - player.w / 2;
    player.y = H - player.h - 80;
    player.health = MAX_HEALTH;
    player.nitro = 0;
    updateUI();
    clearCanvas();
    drawStartScreen();
  }

  // Entities: {type: 'enemy'|'health'|'nitro', lane, y, w, h, speedOffset}
  function spawnEntity() {
    const rand = Math.random();
    if (rand < 0.68) {
      // enemy
      const e = {
        type: 'enemy',
        lane: Math.floor(Math.random() * LANES),
        y: -140 - Math.random()*200,
        w: PLAYER_WIDTH * (0.9 + Math.random()*0.3),
        h: PLAYER_HEIGHT * (0.9 + Math.random()*0.3),
        speedOffset: (Math.random()*1.4 - 0.4) // some move speed diff
      };
      entities.push(e);
    } else if (rand < 0.86) {
      entities.push({
        type:'health',
        lane: Math.floor(Math.random() * LANES),
        y: -80 - Math.random()*300,
        w: 36, h: 36
      });
    } else {
      entities.push({
        type:'nitro',
        lane: Math.floor(Math.random() * LANES),
        y: -80 - Math.random()*300,
        w: 36, h: 36
      });
    }
  }

  function rectsOverlap(a,b){
    return !(a.x + a.w < b.x || a.x > b.x + b.w || a.y + a.h < b.y || a.y > b.y + b.h);
  }

  // Input handling
  window.addEventListener('keydown', e => {
    keys[e.code] = true;
    if (['ArrowLeft','ArrowRight','ArrowUp','ArrowDown','KeyA','KeyD','KeyW','KeyS'].includes(e.code)) e.preventDefault();
    if (e.code === 'Space') {
      if (!running) startGame();
    }
  });
  window.addEventListener('keyup', e => { keys[e.code] = false; });

  // Touch buttons
  function touchHold(btn, code){
    let id = null;
    function onDown(e){
      e.preventDefault(); keys[code] = true;
      id = setInterval(()=>keys[code]=true, 60);
    }
    function onUp(e){ e.preventDefault(); keys[code] = false; if (id) clearInterval(id); id = null; }
    btn.addEventListener('touchstart', onDown);
    btn.addEventListener('mousedown', onDown);
    btn.addEventListener('touchend', onUp);
    btn.addEventListener('mouseup', onUp);
    btn.addEventListener('mouseleave', onUp);
  }
  if (touchLeft && touchRight && touchBoost) {
    touchHold(touchLeft, 'ArrowLeft');
    touchHold(touchRight, 'ArrowRight');
    touchHold(touchBoost, 'ArrowUp');
  }

  // UI events
  btnStart.addEventListener('click', () => startGame());
  btnRestart.addEventListener('click', () => { resetGame(); startGame(); });
  btnToggleSound.addEventListener('click', () => { soundOn = !soundOn; btnToggleSound.textContent = 'Sound: ' + (soundOn ? 'On' : 'Off'); if (!soundOn && audioCtx) audioCtx.suspend(); else if (audioCtx) audioCtx.resume(); });

  // Game loop
  function startGame() {
    if (!audioCtx || audioCtx.state === 'suspended') {
      try { audioCtx && audioCtx.resume(); } catch(e){}
    }
    running = true;
    lastTime = performance.now();
    playBeep(660,0.06,0.08);
    requestAnimationFrame(loop);
  }

  function loop(now) {
    const dt = Math.min(40, now - lastTime); // ms
    lastTime = now;
    if (running) {
      update(dt);
      render();
      requestAnimationFrame(loop);
    }
  }

  // Update logic
  function update(dt) {
    const dtSec = dt/1000;

    // Input: lane change (instant but with small animation)
    if ((keys['ArrowLeft'] || keys['KeyA']) && player.lane > 0) {
      player.lane -= 1;
      player.x = laneCenter(player.lane) - player.w/2;
      playBeep(320,0.04,0.03);
      keys['ArrowLeft'] = false;
      keys['KeyA'] = false;
    }
    if ((keys['ArrowRight'] || keys['KeyD']) && player.lane < LANES-1) {
      player.lane += 1;
      player.x = laneCenter(player.lane) - player.w/2;
      playBeep(420,0.04,0.03);
      keys['ArrowRight'] = false;
      keys['KeyD'] = false;
    }

    // Nitro (ArrowUp / W)
    if ((keys['ArrowUp'] || keys['KeyW']) && player.nitro <= 0) {
      // apply speed burst
      player.nitro = 0.9; // seconds
      speedScale = 2.0;
      playBeep(880,0.08,0.08);
      keys['ArrowUp'] = false; keys['KeyW'] = false;
    }

    // Braking (ArrowDown/S)
    if (keys['ArrowDown'] || keys['KeyS']) {
      speed = Math.max(2, speed - 0.15 * (dtSec*60));
    } else {
      // gradually recover to base speed scaled by difficulty
      const base = 6 + difficulty*0.5;
      speed += (base - speed) * 0.02;
    }

    // Nitro decay
    if (player.nitro > 0) {
      player.nitro = Math.max(0, player.nitro - dtSec);
      if (player.nitro <= 0) speedScale = 1;
    }

    // Difficulty increases with distance
    difficulty = 1 + Math.floor(accumDist / 1000) * 0.06;
    // spawn interval shortens slowly
    spawnInterval = Math.max(500, 1000 - Math.floor(accumDist/1000) * 60);

    // spawn entities
    spawnTimer += dt;
    if (spawnTimer >= spawnInterval) {
      spawnTimer = 0;
      spawnEntity();
    }

    // update entities positions
    const worldSpeed = speed * speedScale;
    for (let i = entities.length-1; i>=0; i--) {
      const e = entities[i];
      e.y += worldSpeed * (1 + (e.speedOffset || 0));
      // set x per lane for collision
      e.x = laneCenter(e.lane) - (e.w/2 || 0);
      // remove off-screen
      if (e.y > H + 200) entities.splice(i,1);
    }

    // collision detection
    const pBox = {x: player.x, y: player.y, w: player.w, h: player.h};
    for (let i = entities.length-1; i>=0; i--) {
      const e = entities[i];
      const eBox = {x: e.x, y: e.y, w: e.w, h: e.h};
      if (rectsOverlap(pBox, eBox)) {
        if (e.type === 'enemy') {
          player.health -= 18 + Math.floor(difficulty*2);
          playBeep(150,0.12,0.14);
          score = Math.max(0, score - 20);
        } else if (e.type === 'health') {
          player.health = Math.min(MAX_HEALTH, player.health + 20);
          playBeep(980,0.08,0.06);
          score += 40;
        } else if (e.type === 'nitro') {
          player.nitro = Math.max(player.nitro, 0.9);
          speedScale = 2.4;
          playBeep(1200,0.06,0.07);
          score += 30;
        }
        // remove entity on pickup/collision
        entities.splice(i,1);
      }
    }

    // update accumulative stats
    accumDist += worldSpeed * (dt / 16.67); // convert to "frames" approx
    score += Math.floor(worldSpeed * 0.04);
    // small speed scaling over time
    speed += 0.0008 * difficulty * dt;

    // UI updates
    updateUI();

    // death
    if (player.health <= 0) {
      player.health = 0;
      running = false;
      uiActive.textContent = 'Game Over';
      playBeep(80,0.5,0.12);
      // final draw to show crash
      render();
      return;
    }
  }

  function updateUI() {
    uiScore.textContent = Math.floor(score);
    uiSpeed.textContent = Math.round(speed * 10) / 10;
    uiHealth.textContent = Math.floor(player.health);
    uiDistance.textContent = Math.floor(accumDist) + ' m';
    uiActive.textContent = running ? 'Playing' : 'Stopped';
    uiHealthBar.style.width = Math.max(0, (player.health / MAX_HEALTH) * 100) + '%';
  }

  // Rendering
  function clearCanvas() {
    ctx.clearRect(0,0,W,H);
  }

  function drawRoad() {
    // background gradient road + side grass
    ctx.fillStyle = '#071021';
    ctx.fillRect(0,0,W,H);

    // side area
    ctx.fillStyle = '#08121a';
    ctx.fillRect(0,0,ROAD_X,H);
    ctx.fillRect(ROAD_X + ROAD_WIDTH,0,W - (ROAD_X + ROAD_WIDTH),H);

    // road
    ctx.fillStyle = '#2b2f36';
    roundRect(ctx, ROAD_X, 0, ROAD_WIDTH, H, 12);
    ctx.fill();

    // lane stripes
    ctx.lineWidth = 4;
    ctx.strokeStyle = '#1b2026';
    ctx.strokeRect(ROAD_X+2, 20, ROAD_WIDTH-4, H-40);

    // dashed lines
    ctx.strokeStyle = '#d9d9d9';
    ctx.lineWidth = 6;
    ctx.setLineDash([30, 22]);
    for (let i=1;i<LANES;i++){
      const x = ROAD_X + LANE_WIDTH * i;
      ctx.beginPath();
      ctx.moveTo(x, -200 + (accumDist % 60));
      ctx.lineTo(x, H + 200);
      ctx.stroke();
    }
    ctx.setLineDash([]);
  }

  function roundRect(ctx, x, y, w, h, r){
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
  }

  function drawPlayer() {
    // shadow
    ctx.save();
    ctx.globalAlpha = 0.15;
    ctx.fillStyle = '#000';
    ctx.fillRect(player.x + 6, player.y + player.h - 8, player.w - 8, 10);
    ctx.restore();

    // main body
    roundRect(ctx, player.x, player.y, player.w, player.h, 8);
    ctx.fillStyle = player.color;
    ctx.fill();

    // windows detail
    ctx.fillStyle = 'rgba(255,255,255,0.12)';
    ctx.fillRect(player.x + player.w*0.12, player.y + player.h*0.12, player.w*0.76, player.h*0.38);
    // stripes
    ctx.fillStyle = 'rgba(255,255,255,0.06)';
    ctx.fillRect(player.x + 6, player.y + player.h - 18, player.w - 12, 6);
  }

  function drawEntities() {
    for (const e of entities) {
      const x = laneCenter(e.lane) - e.w/2;
      if (e.type === 'enemy') {
        // enemy car
        ctx.save();
        roundRect(ctx, x, e.y, e.w, e.h, 8);
        ctx.fillStyle = '#2a9df4';
        ctx.fill();
        ctx.fillStyle = 'rgba(255,255,255,0.09)';
        ctx.fillRect(x + 6, e.y + 8, e.w - 12, 10);
        ctx.restore();
      } else if (e.type === 'health') {
        // health box
        ctx.save();
        ctx.fillStyle = '#36c95b';
        ctx.beginPath();
        ctx.rect(x + (e.w/2 - 18), e.y, 36, 36);
        ctx.fill();
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 18px sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('+', x + e.w/2, e.y + 18);
        ctx.restore();
      } else if (e.type === 'nitro') {
        ctx.save();
        ctx.fillStyle = '#5bc0ff';
        ctx.beginPath();
        ctx.rect(x + (e.w/2 - 18), e.y, 36, 36);
        ctx.fill();
        ctx.fillStyle = '#002';
        ctx.font = 'bold 16px sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('N', x + e.w/2, e.y + 18);
        ctx.restore();
      }
    }
  }

  function drawHUD(){
    // small HUD at top-left
    ctx.save();
    ctx.font = '16px Inter, Arial';
    ctx.fillStyle = 'rgba(255,255,255,0.9)';
    ctx.fillText('Score: ' + Math.floor(score), 18, 28);
    ctx.fillStyle = 'rgba(255,255,255,0.7)';
    ctx.fillText('Speed: ' + Math.round(speed*10)/10, 18, 50);
    ctx.restore();
  }

  function drawStartScreen(){
    clearCanvas();
    drawRoad();
    ctx.save();
    ctx.fillStyle = 'rgba(0,0,0,0.45)';
    roundRect(ctx, W/2 - 240, H/2 - 120, 480, 240, 14);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.font = '28px Inter, Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Mini Racing', W/2, H/2 - 40);
    ctx.font = '16px Inter, Arial';
    ctx.fillText('Tekan Start atau Space untuk mulai', W/2, H/2 - 4);
    ctx.fillText('← → pindah jalur · ↑ Nitro · ↓ Rem', W/2, H/2 + 18);
    ctx.fillStyle = '#ffcccb';
    ctx.font = '14px Inter, Arial';
    ctx.fillText('Good luck!', W/2, H/2 + 64);
    ctx.restore();
  }

  function render() {
    clearCanvas();
    drawRoad();
    drawEntities();
    player.x = laneCenter(player.lane) - player.w / 2;
    drawPlayer();
    drawHUD();
    // if game over draw overlay
    if (!running) {
      ctx.save();
      ctx.fillStyle = 'rgba(0,0,0,0.45)';
      roundRect(ctx, W/2 - 220, H/2 - 100, 440, 200, 12);
      ctx.fill();
      ctx.fillStyle = '#fff';
      ctx.font = '22px Inter, Arial';
      ctx.textAlign = 'center';
      ctx.fillText(player.health <= 0 ? 'Game Over' : 'Paused', W/2, H/2 - 20);
      ctx.font = '16px Inter, Arial';
      ctx.fillText('Score: ' + Math.floor(score) + ' · Distance: ' + Math.floor(accumDist) + ' m', W/2, H/2 + 4);
      ctx.fillText('Klik Restart untuk main lagi', W/2, H/2 + 36);
      ctx.restore();
    }
  }

  // bootstrap
  resetGame();
  drawStartScreen();

  // auto-resize canvas for crispness on HiDPI
  function adjustForDPR(){
    const ratio = window.devicePixelRatio || 1;
    const displayW = canvas.clientWidth || canvas.width;
    const displayH = canvas.clientHeight || canvas.height;
    canvas.width = Math.floor(displayW * ratio);
    canvas.height = Math.floor(displayH * ratio);
    ctx.setTransform(ratio,0,0,ratio,0,0);
  }
  function responsiveSetup(){
    // adapt canvas to container size:
    const container = canvas.parentElement;
    const rect = container.getBoundingClientRect();
    const targetW = Math.min(900, Math.max(320, rect.width - 32));
    const targetH = Math.min(1200, Math.max(520, window.innerHeight - 180));
    canvas.style.width = targetW + 'px';
    canvas.style.height = targetH + 'px';
    adjustForDPR();
  }
  window.addEventListener('resize', responsiveSetup);
  responsiveSetup();

})();
</script>
</body>
</html>
