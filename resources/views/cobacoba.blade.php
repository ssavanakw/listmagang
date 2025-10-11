<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard Top Up Game</title>
<style>
  :root{
    --bg:#0b1020;
    --card:#121831;
    --card-2:#0f1530;
    --muted:#7d8db3;
    --text:#eaf0ff;
    --primary:#5c7cfa;
    --primary-2:#4c6ef5;
    --accent:#22c55e;
    --danger:#ef4444;
    --warning:#f59e0b;
    --border:rgba(125,141,179,0.2);
    --ring:rgba(92,124,250,0.35);
    --shadow: 0 10px 30px rgba(0,0,0,.35);
  }
  :root[data-theme="light"]{
    --bg:#f7f9ff;
    --card:#ffffff;
    --card-2:#f1f5ff;
    --muted:#5a678a;
    --text:#0b1020;
    --primary:#3b82f6;
    --primary-2:#2563eb;
    --accent:#16a34a;
    --danger:#dc2626;
    --warning:#d97706;
    --border:rgba(11,16,32,0.12);
    --ring:rgba(59,130,246,.25);
    --shadow: 0 10px 25px rgba(11,16,32,.08);
  }
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0;
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji";
    color:var(--text);
    background: radial-gradient(1000px 500px at 120% -10%, rgba(92,124,250,.15), transparent 40%) , radial-gradient(900px 400px at -20% 10%, rgba(34,197,94,.12), transparent 40%), var(--bg);
    line-height:1.4;
  }

  .app{
    max-width:1200px;
    margin:0 auto;
    padding:20px;
  }
  header{
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:16px;
  }
  .brand{
    display:flex;align-items:center;gap:10px;font-weight:800;font-size:20px;letter-spacing:.3px;
  }
  .logo{
    width:36px;height:36px;border-radius:10px;
    background: conic-gradient(from 210deg, var(--primary), #7c3aed, var(--accent));
    display:grid;place-items:center;color:white;
    box-shadow: 0 6px 20px rgba(92,124,250,.35);
    font-size:14px;
  }
  .search{
    position:relative; flex:1; max-width:620px;
  }
  .search input{
    width:100%; padding:12px 40px 12px 40px;
    border-radius:12px; border:1px solid var(--border);
    background:linear-gradient(0deg,var(--card),var(--card));
    color:var(--text);
    outline:none; box-shadow:none;
  }
  .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);opacity:.7}
  .right-actions{margin-left:auto; display:flex;gap:8px; align-items:center}
  .btn{
    border:1px solid var(--border); background:linear-gradient(0deg,var(--card),var(--card));
    color:var(--text); padding:10px 14px; border-radius:10px; cursor:pointer;
    transition:all .15s ease; display:inline-flex; align-items:center; gap:8px;
  }
  .btn:hover{border-color:var(--ring); box-shadow:0 0 0 3px var(--ring)}
  .btn.primary{background:linear-gradient(180deg, var(--primary), var(--primary-2)); border-color:transparent;}
  .btn.primary:hover{filter:brightness(1.05)}
  .pill{font-size:12px; padding:2px 8px; border-radius:999px; background:var(--card-2); border:1px solid var(--border)}
  .toggle{display:flex; align-items:center; gap:8px}

  .layout{
    display:grid; grid-template-columns: 1fr 360px; gap:16px;
  }
  @media (max-width: 980px){
    .layout{grid-template-columns:1fr}
    .summary{position:sticky; top:10px}
  }

  .panel{
    background:linear-gradient(0deg,var(--card),var(--card));
    border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow);
  }
  .panel .head{
    padding:14px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between
  }
  .panel .body{padding:14px}

  /* Game grid */
  .games{display:grid; grid-template-columns: repeat( auto-fill, minmax(180px, 1fr) ); gap:12px; padding:12px}
  .game-card{
    border:1px solid var(--border); border-radius:14px; overflow:hidden; cursor:pointer;
    background:linear-gradient(180deg, rgba(255,255,255,.02), transparent 60%) , linear-gradient(0deg,var(--card),var(--card));
    transition:transform .12s ease, border-color .12s ease, box-shadow .12s ease;
  }
  .game-card:hover{transform:translateY(-2px); border-color:var(--ring); box-shadow:0 6px 20px rgba(92,124,250,.15)}
  .game-banner{height:72px; display:flex; align-items:center; justify-content:center; color:white; font-weight:800; letter-spacing:.5px}
  .game-info{padding:10px 12px; display:flex; align-items:center; justify-content:space-between}
  .game-info small{color:var(--muted)}

  /* Summary / History */
  .summary .body{display:flex; flex-direction:column; gap:10px}
  .row{display:flex; align-items:center; justify-content:space-between; gap:8px}
  .divider{height:1px; background:var(--border); margin:8px 0}

  .history{display:flex; flex-direction:column; gap:10px}
  .order{
    border:1px solid var(--border); border-radius:12px; padding:10px; display:flex; gap:10px; align-items:center;
    background:linear-gradient(0deg,var(--card),var(--card));
  }
  .avatar{
    width:42px;height:42px;border-radius:10px; display:grid;place-items:center; color:white; font-weight:800;
  }
  .order .meta{flex:1}
  .order .meta .title{font-weight:700}
  .order .meta .sub{color:var(--muted); font-size:12px}
  .order .actions{display:flex; gap:8px; align-items:center}

  /* Drawer */
  .drawer{
    position: fixed; inset:0; display:none; z-index:30;
  }
  .drawer.open{display:block}
  .drawer .overlay{position:absolute; inset:0; background:rgba(0,0,0,.45); backdrop-filter: blur(2px)}
  .drawer .panel{
    position:absolute; right:0; top:0; height:100%; width:420px; max-width:100%;
    border-radius:0; display:flex; flex-direction:column; animation: slideIn .2s ease;
  }
  @keyframes slideIn { from{ transform: translateX(20px); opacity:0 } to{ transform:translateX(0); opacity:1 } }
  .drawer .panel .body{flex:1; overflow:auto}

  /* Form */
  .form{display:flex; flex-direction:column; gap:12px; padding:10px}
  label{font-size:13px; color:var(--muted); margin-bottom:6px; display:block}
  .input, .select{
    width:100%; padding:12px; border-radius:12px; border:1px solid var(--border);
    background:linear-gradient(0deg,var(--card),var(--card)); color:var(--text); outline:none;
  }
  .grid{display:grid; gap:10px}
  .grid.cols-2{grid-template-columns: repeat(2, 1fr)}
  .grid.cols-3{grid-template-columns: repeat(3, 1fr)}
  .opt{
    border:1px solid var(--border); border-radius:12px; padding:10px; cursor:pointer; position:relative; background:linear-gradient(0deg,var(--card),var(--card));
  }
  .opt input{position:absolute; inset:0; opacity:0; cursor:pointer}
  .opt .t{font-weight:700}
  .opt .s{font-size:12px; color:var(--muted)}
  .opt.active{border-color:var(--ring); box-shadow:0 0 0 3px var(--ring)}
  .badge{font-size:10px; padding:2px 6px; border-radius:999px; background:var(--card-2); border:1px solid var(--border)}
  .price{font-weight:800}
  .muted{color:var(--muted)}
  .center{display:grid; place-items:center}
  .hidden{display:none !important}
  .toast{
    position: fixed; bottom:20px; right:20px; background:linear-gradient(0deg,var(--card),var(--card));
    border:1px solid var(--border); padding:10px 14px; border-radius:12px; box-shadow:var(--shadow); z-index:40;
  }
  .qr{
    width:180px; height:180px; background:
      linear-gradient(90deg, #000 10px, transparent 10px) 0 0/30px 30px,
      linear-gradient(0deg, #000 10px, transparent 10px) 0 0/30px 30px,
      linear-gradient(90deg, #000 10px, transparent 10px) 15px 15px/30px 30px,
      linear-gradient(0deg, #000 10px, transparent 10px) 15px 15px/30px 30px;
    filter: drop-shadow(0 4px 12px rgba(0,0,0,.25));
    background-color:#fff; border:8px solid #fff; border-radius:8px;
  }
  .help{font-size:12px; color:var(--muted)}

  .empty{
    padding:18px; text-align:center; color:var(--muted);
    border:1px dashed var(--border); border-radius:12px; background:linear-gradient(0deg,var(--card),var(--card));
  }
  code.k{background:var(--card-2); border:1px solid var(--border); padding:2px 6px; border-radius:8px}
</style>
</head>
<body>
<div class="app">
  <header>
    <div class="brand">
      <div class="logo">TG</div>
      TopUp Game
      <span class="pill">Dashboard</span>
    </div>
    <div class="search">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M21 21l-4.3-4.3M10.8 18.5a7.7 7.7 0 1 1 0-15.4 7.7 7.7 0 0 1 0 15.4z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
      <input id="search" placeholder="Cari game (cth: ML, FF, Valorant, Genshin)..." />
    </div>
    <div class="right-actions">
      <div class="toggle">
        <button id="themeBtn" class="btn" title="Toggle tema">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3v2m0 14v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M3 12h2m14 0h2M4.9 19.1l1.4-1.4m11.4-11.4 1.4-1.4M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
          Tema
        </button>
      </div>
      <button id="newOrderBtn" class="btn primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
        Buat Top Up
      </button>
    </div>
  </header>

  <div class="layout">
    <section class="panel">
      <div class="head">
        <div style="font-weight:800">Pilih Game</div>
        <div class="help">Klik salah satu untuk mulai top up</div>
      </div>
      <div id="games" class="games"></div>
    </section>

    <aside class="panel summary">
      <div class="head">
        <div style="font-weight:800">Ringkasan & Riwayat</div>
        <div class="help" id="orderCount">0 transaksi</div>
      </div>
      <div class="body">
        <div class="row"><div class="muted">Promo aktif</div><div><code class="k">HEMAT10</code> (maks Rp10.000)</div></div>
        <div class="divider"></div>

        <div class="history" id="history"></div>

        <div id="emptyHistory" class="empty">
          Belum ada transaksi. Mulai dengan memilih game di kiri.
        </div>
      </div>
    </aside>
  </div>
</div>

<!-- Drawer -->
<div id="drawer" class="drawer">
  <div class="overlay" data-close></div>
  <div class="panel">
    <div class="head">
      <div style="display:flex; align-items:center; gap:10px">
        <div id="drawerAvatar" class="avatar" style="width:34px;height:34px;border-radius:8px">GM</div>
        <div>
          <div id="drawerTitle" style="font-weight:800">Game</div>
          <div id="drawerSubtitle" class="help">Isi data player & pilih nominal</div>
        </div>
      </div>
      <button class="btn" data-close>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        Tutup
      </button>
    </div>
    <div class="body">
      <form id="orderForm" class="form">
        <div>
          <label>ID Player</label>
          <input id="fPlayerId" class="input" placeholder="Masukkan ID player" required />
          <div class="help">Contoh: 123456789. Pastikan ID benar agar top up tepat sasaran.</div>
        </div>
        <div class="grid cols-2">
          <div>
            <label>Server (opsional)</label>
            <input id="fServer" class="input" placeholder="Server/Zone ID" />
          </div>
          <div>
            <label>Nickname</label>
            <div style="display:flex; gap:8px">
              <input id="fNickname" class="input" placeholder="Cek ID dulu" />
              <button id="btnCekId" class="btn" type="button">Cek</button>
            </div>
            <div class="help">Klik Cek untuk simulasi verifikasi nickname.</div>
          </div>
        </div>

        <div>
          <label>Pilih Nominal</label>
          <div id="fPackages" class="grid cols-2"></div>
        </div>

        <div>
          <label>Metode Pembayaran</label>
          <div id="fPayments" class="grid cols-2"></div>
        </div>

        <div>
          <label>Kode Promo</label>
          <div style="display:flex; gap:8px">
            <input id="fVoucher" class="input" placeholder="Masukkan kode (cth: HEMAT10)" />
            <button id="btnApplyVoucher" class="btn" type="button">Pakai</button>
          </div>
        </div>

        <div class="divider"></div>
        <div style="display:flex; flex-direction:column; gap:6px">
          <div class="row"><div class="muted">Subtotal</div><div id="pSubtotal" class="price">Rp0</div></div>
          <div class="row"><div class="muted">Biaya layanan</div><div id="pFee">Rp0</div></div>
          <div class="row"><div class="muted">Diskon</div><div id="pDiscount" style="color:var(--accent)">-Rp0</div></div>
          <div class="row"><div class="muted">Kode unik</div><div id="pUnique" class="muted">Rp0</div></div>
          <div class="divider"></div>
          <div class="row" style="font-size:18px"><div style="font-weight:800">Total Bayar</div><div id="pTotal" class="price">Rp0</div></div>
        </div>

        <div id="qrisBox" class="center hidden">
          <div>
            <div class="center"><div class="qr"></div></div>
            <div class="help" style="margin-top:8px; text-align:center">Contoh QRIS (dummy). Lanjutkan untuk simulasikan pembayaran.</div>
          </div>
        </div>

        <div class="divider"></div>
        <div style="display:flex; gap:8px; align-items:center; justify-content:space-between">
          <div class="help">Dengan menekan Bayar, Anda menyetujui ketentuan transaksi.</div>
          <button class="btn primary" id="btnPay" type="submit">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h10" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            Bayar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="toast" class="toast hidden"></div>

<script>
  // Data
  const GAMES = [
    {id:'ml', name:'Mobile Legends', code:'MLBB', colors:['#1d4ed8','#3b82f6'], unit:'Diamonds', avatar:'#1d4ed8'},
    {id:'ff', name:'Free Fire', code:'FF', colors:['#ea580c','#f97316'], unit:'Diamonds', avatar:'#ea580c'},
    {id:'pubg', name:'PUBG Mobile', code:'PUBG', colors:['#0ea5e9','#22d3ee'], unit:'UC', avatar:'#0ea5e9'},
    {id:'val', name:'Valorant', code:'VAL', colors:['#ef4444','#f43f5e'], unit:'VP', avatar:'#ef4444'},
    {id:'gi', name:'Genshin Impact', code:'GI', colors:['#6d28d9','#7c3aed'], unit:'Genesis', avatar:'#6d28d9'},
    {id:'rbx', name:'Roblox', code:'RBX', colors:['#16a34a','#22c55e'], unit:'Robux', avatar:'#16a34a'},
    {id:'hi', name:'Honkai: Star Rail', code:'HSR', colors:['#0891b2','#0ea5e9'], unit:'Stellar', avatar:'#0891b2'},
    {id:'codm', name:'COD Mobile', code:'CODM', colors:['#64748b','#94a3b8'], unit:'CP', avatar:'#64748b'},
  ];

  const PACKAGE_PRESET = {
    Diamonds: [
      {id:'d1', name:'86 Diamonds', value:86, price:20000},
      {id:'d2', name:'172 Diamonds', value:172, price:40000},
      {id:'d3', name:'257 Diamonds', value:257, price:60000},
      {id:'d4', name:'344 Diamonds', value:344, price:80000},
      {id:'d5', name:'514 Diamonds', value:514, price:120000},
      {id:'d6', name:'Starlight Member', value:0, price:149000, tag:'Best'}
    ],
    UC: [
      {id:'u1', name:'60 UC', value:60, price:14000},
      {id:'u2', name:'300 UC', value:300, price:68000},
      {id:'u3', name:'660 UC', value:660, price:145000},
      {id:'u4', name:'1800 UC', value:1800, price:379000}
    ],
    VP: [
      {id:'v1', name:'125 VP', value:125, price:16000},
      {id:'v2', name:'420 VP', value:420, price:52000},
      {id:'v3', name:'700 VP', value:700, price:84000},
      {id:'v4', name:'1375 VP', value:1375, price:159000}
    ],
    Genesis: [
      {id:'g1', name:'60 Genesis', value:60, price:15000},
      {id:'g2', name:'330 Genesis', value:330, price:75000},
      {id:'g3', name:'1090 Genesis', value:1090, price:239000}
    ],
    Robux: [
      {id:'r1', name:'80 Robux', value:80, price:15000},
      {id:'r2', name:'400 Robux', value:400, price:75000},
      {id:'r3', name:'800 Robux', value:800, price:149000}
    ],
    Stellar: [
      {id:'s1', name:'60 Stellar', value:60, price:15000},
      {id:'s2', name:'330 Stellar', value:330, price:75000},
      {id:'s3', name:'1090 Stellar', value:1090, price:239000}
    ],
    CP: [
      {id:'c1', name:'80 CP', value:80, price:15000},
      {id:'c2', name:'420 CP', value:420, price:75000},
      {id:'c3', name:'880 CP', value:880, price:149000}
    ]
  };

  const PAYMENTS = [
    {id:'ovo', name:'OVO', kind:'ewallet', color:'#6b21a8'},
    {id:'dana', name:'DANA', kind:'ewallet', color:'#0ea5e9'},
    {id:'gopay', name:'GoPay', kind:'ewallet', color:'#0ea5e9'},
    {id:'spay', name:'ShopeePay', kind:'ewallet', color:'#ef4444'},
    {id:'qris', name:'QRIS', kind:'qris', color:'#111827'},
    {id:'bca', name:'BCA', kind:'bank', color:'#2563eb'},
    {id:'bri', name:'BRI', kind:'bank', color:'#0ea5e9'},
    {id:'mandiri', name:'Mandiri', kind:'bank', color:'#f59e0b'},
  ];

  const VOUCHERS = [
    {code:'HEMAT10', type:'percent', value:10, cap:10000}
  ];

  // State
  let state = {
    theme: localStorage.getItem('theme') || 'dark',
    orders: JSON.parse(localStorage.getItem('orders') || '[]'),
    currentGame: null,
    selectedPackage: null,
    selectedPayment: null,
    uniqueCode: 0,
    discount: 0,
  };

  // Utils
  const $ = (s, r=document) => r.querySelector(s);
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));
  const rupiah = n => new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', maximumFractionDigits:0}).format(Math.max(0, Math.round(n||0)));
  const randomId = (p='INV') => p + '-' + Date.now().toString(36).toUpperCase() + '-' + Math.floor(Math.random()*999).toString().padStart(3,'0');

  function setTheme(t){
    document.documentElement.setAttribute('data-theme', t === 'light' ? 'light' : 'dark');
    localStorage.setItem('theme', t);
    state.theme = t;
  }

  function toast(msg, timeout=2200){
    const t = $('#toast');
    t.textContent = msg;
    t.classList.remove('hidden');
    setTimeout(()=>t.classList.add('hidden'), timeout);
  }

  function calcFees(method, subtotal){
    // platform fee flat
    const platform = 500;
    let fee = 0;
    let unique = 0;
    if(!method) return {fee: platform, unique: 0, total: subtotal + platform};
    switch(method.kind){
      case 'ewallet':
        fee = Math.max(subtotal * 0.018, 1000) + platform;
        break;
      case 'qris':
        fee = Math.max(subtotal * 0.007, 1000) + platform;
        break;
      case 'bank':
        unique = Math.floor(100 + Math.random()*899); // 3 digit
        fee = 1500 + platform;
        break;
      default:
        fee = platform;
    }
    return {fee: Math.round(fee), unique, total: subtotal + fee + unique};
  }

  function applyVoucher(code, subtotal){
    if(!code) return 0;
    const v = VOUCHERS.find(x => x.code.toLowerCase() === code.toLowerCase());
    if(!v) return 0;
    if(v.type === 'percent'){
      const cut = Math.floor(subtotal * (v.value/100));
      return Math.min(cut, v.cap || cut);
    }
    return 0;
  }

  // Render games
  function renderGames(list=GAMES){
    const wrap = $('#games');
    wrap.innerHTML = '';
    list.forEach(g=>{
      const el = document.createElement('div');
      el.className = 'game-card';
      el.dataset.id = g.id;
      el.innerHTML = `
        <div class="game-banner" style="background: linear-gradient(90deg, ${g.colors[0]}, ${g.colors[1]});">
          ${g.code}
        </div>
        <div class="game-info">
          <div>
            <div style="font-weight:800">${g.name}</div>
            <small>${g.unit}</small>
          </div>
          <button class="btn" style="padding:6px 10px">Top Up</button>
        </div>
      `;
      el.addEventListener('click', ()=> openDrawer(g));
      wrap.appendChild(el);
    });
  }

  function filterGames(term){
    term = (term||'').trim().toLowerCase();
    if(!term) return renderGames(GAMES);
    renderGames(GAMES.filter(g => g.name.toLowerCase().includes(term) || g.code.toLowerCase().includes(term) || g.unit.toLowerCase().includes(term)));
  }

  // Drawer / Order form
  function openDrawer(game){
    state.currentGame = game;
    state.selectedPackage = null;
    state.selectedPayment = null;
    state.uniqueCode = 0;
    state.discount = 0;
    $('#fPlayerId').value = '';
    $('#fServer').value = '';
    $('#fNickname').value = '';
    $('#fVoucher').value = '';
    $('#pSubtotal').textContent = rupiah(0);
    $('#pFee').textContent = rupiah(0);
    $('#pDiscount').textContent = '-'+rupiah(0);
    $('#pUnique').textContent = rupiah(0);
    $('#pTotal').textContent = rupiah(0);
    $('#qrisBox').classList.add('hidden');

    // Header
    $('#drawerAvatar').style.background = game.avatar;
    $('#drawerAvatar').textContent = game.code.slice(0,2).toUpperCase();
    $('#drawerTitle').textContent = game.name;
    $('#drawerSubtitle').textContent = `Top up ${game.unit}`;

    // Packages
    const pkWrap = $('#fPackages');
    pkWrap.innerHTML = '';
    const packs = PACKAGE_PRESET[game.unit] || PACKAGE_PRESET['Diamonds'];
    packs.forEach((p, idx)=>{
      const div = document.createElement('div');
      div.className = 'opt';
      div.innerHTML = `
        <input type="radio" name="pkg" value="${p.id}">
        <div class="t">${p.name} ${p.tag ? `<span class="badge">${p.tag}</span>`:''}</div>
        <div class="row" style="margin-top:6px">
          <div class="s">${p.value ? `${p.value} ${game.unit}` : `Paket khusus`}</div>
          <div class="price">${rupiah(p.price)}</div>
        </div>
      `;
      div.addEventListener('click', ()=>{
        $$('.opt', pkWrap).forEach(o=>o.classList.remove('active'));
        div.classList.add('active');
        state.selectedPackage = p;
        recalc();
      });
      pkWrap.appendChild(div);
      if(idx===0){
        // preselect first
        setTimeout(()=>div.click(), 0);
      }
    });

    // Payments
    const payWrap = $('#fPayments');
    payWrap.innerHTML = '';
    PAYMENTS.forEach((p, idx)=>{
      const div = document.createElement('div');
      div.className = 'opt';
      div.innerHTML = `
        <input type="radio" name="pay" value="${p.id}">
        <div class="row">
          <div class="t" style="display:flex; align-items:center; gap:8px">
            <span class="avatar" style="width:22px;height:22px;border-radius:6px;background:${p.color};font-size:11px">${p.name[0]}</span>
            ${p.name}
          </div>
          <span class="s">${p.kind.toUpperCase()}</span>
        </div>
      `;
      div.addEventListener('click', ()=>{
        $$('.opt', payWrap).forEach(o=>o.classList.remove('active'));
        div.classList.add('active');
        state.selectedPayment = p;
        $('#qrisBox').classList.toggle('hidden', p.id!=='qris');
        recalc();
      });
      payWrap.appendChild(div);
      if(idx===0){
        setTimeout(()=>div.click(), 0);
      }
    });

    // Show drawer
    $('#drawer').classList.add('open');
  }

  function closeDrawer(){
    $('#drawer').classList.remove('open');
  }

  function recalc(){
    const pkg = state.selectedPackage;
    const pay = state.selectedPayment;
    const subtotal = pkg ? pkg.price : 0;
    const discount = applyVoucher($('#fVoucher').value, subtotal);
    const afterDiscount = Math.max(0, subtotal - discount);
    const fees = calcFees(pay || {}, afterDiscount);
    state.uniqueCode = fees.unique;
    state.discount = discount;

    $('#pSubtotal').textContent = rupiah(subtotal);
    $('#pDiscount').textContent = '-' + rupiah(discount);
    $('#pFee').textContent = rupiah(fees.fee);
    $('#pUnique').textContent = rupiah(fees.unique);
    $('#pTotal').textContent = rupiah(fees.total);
  }

  function saveOrders(){
    localStorage.setItem('orders', JSON.stringify(state.orders));
    updateHistory();
  }

  function updateHistory(){
    const h = $('#history');
    const cnt = $('#orderCount');
    h.innerHTML = '';
    const orders = state.orders.slice().reverse(); // recent first
    if(orders.length === 0){
      $('#emptyHistory').classList.remove('hidden');
      cnt.textContent = '0 transaksi';
      return;
    }
    $('#emptyHistory').classList.add('hidden');
    cnt.textContent = `${orders.length} transaksi`;
    orders.forEach(o=>{
      const item = document.createElement('div');
      item.className = 'order';
      const statusColor = o.status === 'Berhasil' ? 'var(--accent)' : (o.status === 'Menunggu Pembayaran' ? 'var(--warning)' : 'var(--danger)');
      item.innerHTML = `
        <div class="avatar" style="background:${o.game.avatar}">${o.game.code.slice(0,2).toUpperCase()}</div>
        <div class="meta">
          <div class="title">${o.game.name} • ${o.pkg.name}</div>
          <div class="sub">ID: ${o.playerId}${o.server ? ' • Srv: '+o.server:''} • ${o.payment.name} • ${new Date(o.createdAt).toLocaleString('id-ID')}</div>
        </div>
        <div class="actions">
          <span class="price">${rupiah(o.total)}</span>
          <span class="pill" style="border-color:${statusColor}; color:${statusColor}">${o.status}</span>
          <button class="btn" data-copy="${o.id}" title="Salin Kode">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M9 9h9v10H9zM6 6h9v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="btn" data-print="${o.id}" title="Cetak Struk">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M7 8V4h10v4M7 20v-6h10v6M5 12h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
          </button>
        </div>
      `;
      h.appendChild(item);
    });

    // Bind actions
    $$('#history [data-copy]').forEach(b=>{
      b.addEventListener('click', ()=>{
        const id = b.getAttribute('data-copy');
        navigator.clipboard.writeText(id);
        toast('Kode pesanan disalin: ' + id);
      });
    });
    $$('#history [data-print]').forEach(b=>{
      b.addEventListener('click', ()=>{
        const id = b.getAttribute('data-print');
        const ord = state.orders.find(x=>x.id===id);
        if(ord) printInvoice(ord);
      });
    });
  }

  function printInvoice(o){
    const w = window.open('', '_blank');
    const html = `
      <html>
        <head>
          <meta charset="utf-8" />
          <title>Struk ${o.id}</title>
          <style>
            body{font-family:Arial, sans-serif; padding:20px; color:#111}
            .box{max-width:640px;margin:0 auto;border:1px solid #ddd;border-radius:12px;padding:16px}
            .row{display:flex;justify-content:space-between;margin:6px 0}
            .muted{color:#666}
            .title{font-weight:800;font-size:20px}
            .divider{height:1px;background:#eee;margin:10px 0}
            .badge{font-size:12px;padding:2px 8px;border:1px solid #ddd;border-radius:999px}
          </style>
        </head>
        <body onload="window.print()">
          <div class="box">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div class="title">TopUp Game • Struk</div>
              <span class="badge">${o.status}</span>
            </div>
            <div class="muted">${new Date(o.createdAt).toLocaleString('id-ID')}</div>
            <div class="divider"></div>

            <div class="row"><div>Game</div><div><b>${o.game.name}</b> (${o.game.code})</div></div>
            <div class="row"><div>Paket</div><div>${o.pkg.name}</div></div>
            <div class="row"><div>ID Player</div><div>${o.playerId}${o.server ? ' • Srv: '+o.server:''}</div></div>
            <div class="row"><div>Nickname</div><div>${o.nickname || '-'}</div></div>
            <div class="row"><div>Metode</div><div>${o.payment.name}</div></div>
            <div class="divider"></div>
            <div class="row"><div>Subtotal</div><div>${rupiah(o.subtotal)}</div></div>
            <div class="row"><div>Biaya layanan</div><div>${rupiah(o.fee)}</div></div>
            ${o.uniqueCode ? `<div class="row"><div>Kode unik</div><div>${rupiah(o.uniqueCode)}</div></div>` : ''}
            <div class="row"><div>Diskon</div><div>-${rupiah(o.discount)}</div></div>
            <div class="divider"></div>
            <div class="row"><div>Total</div><div><b>${rupiah(o.total)}</b></div></div>
            <div class="divider"></div>
            <div class="muted">Kode Pesanan: ${o.id}</div>
          </div>
        </body>
      </html>
    `;
    w.document.write(html);
    w.document.close();
  }

  // Handlers
  $('#themeBtn').addEventListener('click', ()=>{
    setTheme(state.theme === 'dark' ? 'light' : 'dark');
  });

  $('#newOrderBtn').addEventListener('click', ()=>{
    openDrawer(GAMES[0]);
  });

  $('#search').addEventListener('input', (e)=> filterGames(e.target.value));

  $$('#drawer [data-close]').forEach(el=> el.addEventListener('click', closeDrawer));

  $('#btnCekId').addEventListener('click', ()=>{
    const id = ($('#fPlayerId').value||'').trim();
    if(!id){
      toast('Masukkan ID Player dulu ya.');
      return;
    }
    // Simulasi cek ID
    const name = 'Player_' + id.slice(-4).padStart(4,'0');
    $('#fNickname').value = name;
    toast('ID terverifikasi: ' + name);
  });

  $('#btnApplyVoucher').addEventListener('click', ()=>{
    const code = ($('#fVoucher').value||'').trim();
    if(!code){
      toast('Masukkan kode promo.');
      return;
    }
    const disc = applyVoucher(code, state.selectedPackage ? state.selectedPackage.price : 0);
    if(disc > 0){
      toast('Voucher diterapkan: ' + code.toUpperCase());
    }else{
      toast('Voucher tidak valid.');
    }
    recalc();
  });

  $('#orderForm').addEventListener('submit', (e)=>{
    e.preventDefault();
    const game = state.currentGame;
    const pkg = state.selectedPackage;
    const pay = state.selectedPayment;
    const playerId = ($('#fPlayerId').value||'').trim();
    const server = ($('#fServer').value||'').trim();
    const nickname = ($('#fNickname').value||'').trim();
    if(!game || !pkg || !pay) return toast('Lengkapi pilihan game, paket, dan pembayaran.');
    if(!playerId) return toast('ID Player wajib diisi.');

    const subtotal = pkg.price;
    const discount = applyVoucher($('#fVoucher').value, subtotal);
    const afterDiscount = Math.max(0, subtotal - discount);
    const fees = calcFees(pay, afterDiscount);

    const order = {
      id: randomId('INV'),
      game,
      pkg,
      payment: pay,
      playerId,
      server,
      nickname,
      subtotal,
      fee: fees.fee,
      uniqueCode: fees.unique,
      discount,
      total: fees.total,
      createdAt: Date.now(),
      status: 'Menunggu Pembayaran'
    };

    state.orders.push(order);
    saveOrders();
    toast('Pesanan dibuat. Memproses pembayaran...');

    // Simulasikan pembayaran sukses
    setTimeout(()=>{
      const idx = state.orders.findIndex(o=>o.id===order.id);
      if(idx>=0){
        state.orders[idx].status = 'Berhasil';
        saveOrders();
        toast('Pembayaran berhasil. Top up akan segera diproses.');
      }
    }, pay.kind === 'bank' ? 3000 : 1500);

    closeDrawer();
  });

  // Init
  (function init(){
    setTheme(state.theme);
    renderGames(GAMES);
    updateHistory();
  })();
</script>
</body>
</html>