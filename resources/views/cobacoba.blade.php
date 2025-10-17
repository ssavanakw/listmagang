<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>TitipKu — Web Titip Barang (Single File Demo)</title>

  <!-- Simple reset + minimal styles -->
  <style>
    :root{
      --bg:#0f172a; --card:#0b1220; --muted:#94a3b8; --accent:#06b6d4; --glass: rgba(255,255,255,0.04);
      --success:#10b981; --danger:#ef4444; --soft:#111827;
      font-family: Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; background:linear-gradient(180deg,#041025 0%, #071330 60%); color:#e6eef6;
      -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
    }
    .app{
      max-width:1100px; margin:28px auto; padding:20px; display:grid;
      grid-template-columns: 260px 1fr; gap:20px;
    }

    /* SIDEBAR */
    .sidebar{
      background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      border-radius:12px; padding:18px; box-shadow:0 6px 18px rgba(2,6,23,0.6);
      height:calc(100vh - 80px); position:sticky; top:20px; overflow:auto;
      border:1px solid rgba(255,255,255,0.03);
    }
    .brand{font-weight:700; font-size:18px; color:var(--accent); margin-bottom:12px}
    .muted{color:var(--muted); font-size:13px}
    .nav{margin-top:18px; display:flex; flex-direction:column; gap:6px}
    .nav button{
      background:transparent; color:inherit; border:0; text-align:left; padding:10px; border-radius:8px;
      cursor:pointer; font-weight:600; font-size:14px;
    }
    .nav button:hover{background:var(--glass)}
    .nav button.active{background:linear-gradient(90deg, rgba(6,182,212,0.12), rgba(6,182,212,0.06)); color:var(--accent)}

    /* MAIN */
    .main{
      display:flex; flex-direction:column; gap:14px;
    }
    .card{
      background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      padding:18px; border-radius:12px; border:1px solid rgba(255,255,255,0.03);
      box-shadow:0 6px 18px rgba(2,6,23,0.45);
    }
    header.top{
      display:flex; align-items:center; justify-content:space-between; gap:12px;
    }
    .search{display:flex; gap:8px; align-items:center}
    .search input{
      background:transparent; border:1px solid rgba(255,255,255,0.04); color:inherit; padding:8px 10px; border-radius:8px;
      min-width:220px;
    }
    .btn{
      background:var(--accent); color:#042028; border:0; padding:8px 12px; border-radius:10px; cursor:pointer; font-weight:700;
    }
    .btn.ghost{background:transparent; color:var(--muted); border:1px solid rgba(255,255,255,0.03)}
    .grid{display:grid; gap:12px}
    .columns-3{grid-template-columns: repeat(3, 1fr)}
    .columns-2{grid-template-columns: repeat(2, 1fr)}
    .item{
      padding:12px; border-radius:10px; background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00));
      border:1px solid rgba(255,255,255,0.025); display:flex; gap:12px; align-items:center;
    }
    .item .thumb{width:64px; height:64px; border-radius:8px; background:linear-gradient(135deg,#0ea5a7,#0369a1); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:18px}
    .item .meta{flex:1}
    .muted-sm{color:var(--muted); font-size:13px}
    .small{font-size:13px}
    .pill{padding:6px 8px;border-radius:999px;border:1px solid rgba(255,255,255,0.03); background:rgba(255,255,255,0.01); font-weight:700}

    /* forms */
    label{display:block; font-size:13px; margin-bottom:6px; color:var(--muted)}
    input[type="text"], input[type="number"], textarea, select {
      width:100%; padding:10px; border-radius:8px; border:1px solid rgba(255,255,255,0.04); background:transparent; color:inherit;
    }
    textarea{min-height:90px; resize:vertical}
    .row{display:flex; gap:10px}
    .row > *{flex:1}

    /* footer small */
    .muted-foot{font-size:12px; color:var(--muted)}
    .center{display:flex; align-items:center; justify-content:center}

    /* responsive */
    @media (max-width:960px){
      .app{grid-template-columns: 1fr; padding:12px}
      .sidebar{height:auto; position:relative}
      .columns-3{grid-template-columns: repeat(2, 1fr)}
    }
    @media (max-width:520px){
      .columns-3{grid-template-columns: 1fr}
      .search input{min-width:100px}
    }
  </style>
</head>
<body>
  <div class="app" id="app">
    <aside class="sidebar card" aria-label="sidebar">
      <div class="brand">TitipKu — Demo</div>
      <div class="muted">Sistem titip barang sederhana (client-only demo)</div>

      <div style="height:12px"></div>

      <div id="user-summary" class="card" style="padding:10px">
        <!-- dynamic user info -->
        <div id="user-box" class="small muted-sm">Belum login</div>
        <div style="height:8px"></div>
        <div id="auth-actions" style="display:flex; gap:8px">
          <button class="btn" onclick="showView('login')">Masuk</button>
          <button class="btn ghost" onclick="showView('register')">Daftar</button>
        </div>
      </div>

      <nav class="nav" style="margin-top:12px">
        <button data-view="dashboard" class="active" onclick="showView('dashboard')">🏠 Dashboard</button>
        <button data-view="items" onclick="showView('items')">📦 Barang</button>
        <button data-view="titip" onclick="showView('titip')">🧾 Titip Barang</button>
        <button data-view="history" onclick="showView('history')">📜 Riwayat Titip</button>
        <button data-view="account" onclick="showView('account')">👤 Akun Saya</button>
        <button data-view="admin" onclick="showView('admin')">🔧 Admin (jika Admin)</button>
        <button data-view="about" onclick="showView('about')">❓ Tentang</button>
      </nav>

      <div style="height:12px"></div>
      <div class="muted-foot">Demo ini menyimpan data di browser (localStorage). Gunakan akun 'admin' untuk akses admin.</div>
    </aside>

    <main class="main">
      <!-- HEADER -->
      <div class="card top">
        <div style="display:flex; gap:12px; align-items:center">
          <h2 style="margin:0">TitipKu — Sistem Titip Barang</h2>
          <div class="muted" style="font-size:13px">Demo single-file · Penyimpanan lokal</div>
        </div>
        <div class="search">
          <input id="search" placeholder="Cari barang..." oninput="renderItems()" />
          <button class="btn ghost" onclick="openQuickAdd()">+ Barang Cepat</button>
          <div style="width:8px"></div>
          <div id="header-actions"></div>
        </div>
      </div>

      <!-- VIEWS -->
      <div id="view-container">

        <!-- DASHBOARD -->
        <section id="view-dashboard" class="card">
          <h3>Ringkasan</h3>
          <div style="display:flex; gap:12px; margin-top:12px;">
            <div style="flex:1" class="card">
              <div style="display:flex; justify-content:space-between; align-items:center">
                <div>
                  <div class="muted-sm">Jumlah Barang Tersedia</div>
                  <div style="font-size:20px; font-weight:800" id="stat-items">0</div>
                </div>
                <div class="pill" id="stat-admin">—</div>
              </div>
            </div>
            <div style="flex:1" class="card">
              <div class="muted-sm">Titipan Aktif</div>
              <div style="font-size:20px; font-weight:800" id="stat-deposits">0</div>
            </div>
            <div style="flex:1" class="card">
              <div class="muted-sm">Akun Terdaftar</div>
              <div style="font-size:20px; font-weight:800" id="stat-users">0</div>
            </div>
          </div>

          <div style="height:14px"></div>

          <div class="grid columns-3" id="recent-items">
            <!-- cards barang -->
          </div>
        </section>

        <!-- ITEMS LIST & MANAGEMENT -->
        <section id="view-items" class="card" style="display:none">
          <div style="display:flex; justify-content:space-between; align-items:center">
            <h3>Daftar Barang</h3>
            <div style="display:flex; gap:8px">
              <button class="btn" onclick="openAddItemForm()">+ Tambah Barang</button>
              <button class="btn ghost" onclick="seedDemo()">Isi Demo</button>
            </div>
          </div>

          <div style="height:12px"></div>

          <div id="items-list" class="grid">
            <!-- item rows -->
          </div>
        </section>

        <!-- TITIP FORM -->
        <section id="view-titip" class="card" style="display:none">
          <h3>Titip Barang</h3>
          <div id="titip-box">
            <div class="muted-sm">Pilih barang yang ingin dititipkan — kemudian isi jumlah & catatan.</div>
            <div style="height:12px"></div>
            <div id="titip-items" class="grid columns-2"></div>
            <div style="height:12px"></div>
            <div style="display:flex; gap:8px; justify-content:flex-end">
              <button class="btn" onclick="createDepositFromSelected()">Proses Titip</button>
            </div>
          </div>
        </section>

        <!-- HISTORY -->
        <section id="view-history" class="card" style="display:none">
          <h3>Riwayat Titip</h3>
          <div id="history-list" class="grid"></div>
        </section>

        <!-- ACCOUNT -->
        <section id="view-account" class="card" style="display:none">
          <h3>Akun Saya</h3>
          <div id="account-box">
            <div class="muted-sm">Informasi akun</div>
            <div style="height:12px"></div>
            <div id="profile-info" class="card small"></div>
            <div style="height:12px"></div>
            <button class="btn" onclick="logout()">Keluar</button>
          </div>

          <!-- REGISTER / LOGIN -->
          <div id="auth-forms" style="margin-top:12px"></div>
        </section>

        <!-- ADMIN -->
        <section id="view-admin" class="card" style="display:none">
          <h3>Panel Admin</h3>
          <div class="muted-sm">Hanya muncul jika login sebagai admin.</div>

          <div style="height:12px"></div>
          <div class="grid columns-2">
            <div class="card">
              <h4>Manajemen Barang</h4>
              <div id="admin-items"></div>
            </div>
            <div class="card">
              <h4>Manajemen Titipan</h4>
              <div id="admin-deposits"></div>
            </div>
          </div>
        </section>

        <!-- LOGIN -->
        <section id="view-login" class="card" style="display:none">
          <h3>Masuk</h3>
          <div style="height:8px"></div>
          <form onsubmit="event.preventDefault(); submitLogin()">
            <label for="login-email">Email atau username</label>
            <input id="login-email" required placeholder="mis: user@example.com / username" />
            <label for="login-pass">Kata sandi</label>
            <input id="login-pass" type="password" required />
            <div style="height:8px"></div>
            <button class="btn" type="submit">Masuk</button>
            <button type="button" class="btn ghost" onclick="showView('register')">Belum punya akun?</button>
          </form>
        </section>

        <!-- REGISTER -->
        <section id="view-register" class="card" style="display:none">
          <h3>Daftar</h3>
          <form onsubmit="event.preventDefault(); submitRegister()">
            <label>Nama lengkap</label>
            <input id="reg-name" required />
            <label>Username</label>
            <input id="reg-username" required />
            <label>Email</label>
            <input id="reg-email" type="email" required />
            <label>Kata sandi</label>
            <input id="reg-pass" type="password" required />
            <div style="height:8px"></div>
            <div style="display:flex; gap:8px; align-items:center">
              <label style="margin:0" class="muted-sm">Daftar sebagai</label>
              <select id="reg-role"><option value="user">User</option><option value="admin">Admin</option></select>
            </div>
            <div style="height:12px"></div>
            <button class="btn">Daftar</button>
          </form>
        </section>

        <!-- ABOUT -->
        <section id="view-about" class="card" style="display:none">
          <h3>Tentang</h3>
          <p class="muted-sm">Demo sistem titip barang (single-file). Cocok untuk prototyping, belajar, atau uji UI. Semua data disimpan di <code>localStorage</code> browser.</p>
          <div style="height:12px"></div>
          <div class="muted-sm">Tips: Untuk reset data, buka console dan jalankan <code>localStorage.clear()</code> lalu muat ulang.</div>
        </section>
      </div>

      <div class="center muted-foot">© TitipKu — Demo · Built with ❤️ (client-only)</div>

    </main>
  </div>

  <!-- MODAL / FLOATING FORMS -->
  <div id="modal-root" style="position:fixed; inset:0; display:none; align-items:center; justify-content:center; z-index:9999; padding:20px">
    <div id="modal-back" style="position:absolute; inset:0; background:rgba(2,6,23,0.7); border-radius:12px" onclick="closeModal()"></div>
    <div id="modal" style="position:relative; z-index:10; max-width:720px; width:100%"></div>
  </div>

  <!-- SCRIPT -->
  <script>
    /*****************************************************************
     * Simple Single-file App: TitipKu (client-side)
     * - Data in localStorage
     * - Models: users, items, deposits
     * - Basic auth (no security; demo only)
     *****************************************************************/

    /* ---------- Utilities ---------- */
    const LS = {
      users: 'titip_users_v1',
      items: 'titip_items_v1',
      deposits: 'titip_deposits_v1',
      current: 'titip_current_user_v1'
    };

    function uid(prefix='id'){ return prefix + '_' + Math.random().toString(36).slice(2,9) }
    function now(){ return new Date().toISOString() }

    function read(key){ try { return JSON.parse(localStorage.getItem(key) || 'null') } catch(e){ return null } }
    function write(key, val){ localStorage.setItem(key, JSON.stringify(val)) }

    /* ---------- State init ---------- */
    if(!read(LS.users)){
      // create default admin + demo user
      write(LS.users, [
        {id: uid('user'), name:'Admin Demo', username:'admin', email:'admin@example.com', pass:'admin123', role:'admin', createdAt: now()},
        {id: uid('user'), name:'Surya Demo', username:'surya', email:'surya@example.com', pass:'password', role:'user', createdAt: now()}
      ]);
    }
    if(!read(LS.items)){
      write(LS.items, []); // empty
    }
    if(!read(LS.deposits)){
      write(LS.deposits, []);
    }

    let state = {
      view: 'dashboard',
      currentUser: read(LS.current) || null,
      items: read(LS.items) || [],
      users: read(LS.users) || [],
      deposits: read(LS.deposits) || []
    };

    /* ---------- Render helpers ---------- */
    function persistAll(){
      write(LS.items, state.items);
      write(LS.users, state.users);
      write(LS.deposits, state.deposits);
      write(LS.current, state.currentUser);
    }

    function showView(view){
      state.view = view;
      document.querySelectorAll('[id^="view-"]').forEach(el => el.style.display = 'none');
      const el = document.getElementById('view-' + view);
      if(el) el.style.display = '';
      // highlight sidebar
      document.querySelectorAll('.nav button').forEach(b => b.classList.toggle('active', b.dataset.view === view));
      renderHeaderActions();
      renderAll();
      // auto-show login if user clicks account and not logged in
      if(view === 'account' && !state.currentUser){
        showView('login'); return;
      }
    }

    function renderHeaderActions(){
      const box = document.getElementById('header-actions');
      box.innerHTML = '';
      if(state.currentUser){
        const el = document.createElement('div');
        el.style.display='flex'; el.style.gap='8px'; el.style.alignItems='center';
        const u = document.createElement('div');
        u.className='muted-sm'; u.textContent = state.currentUser.name + ' (' + state.currentUser.role + ')';
        const btn = document.createElement('button');
        btn.className='btn ghost'; btn.textContent='Profil'; btn.onclick = () => showView('account');
        const out = document.createElement('button');
        out.className='btn'; out.textContent='Keluar'; out.onclick = logout;
        el.appendChild(u); el.appendChild(btn); el.appendChild(out);
        box.appendChild(el);
      } else {
        const b1 = document.createElement('button'); b1.className='btn'; b1.textContent='Masuk'; b1.onclick = ()=>showView('login');
        const b2 = document.createElement('button'); b2.className='btn ghost'; b2.textContent='Daftar'; b2.onclick = ()=>showView('register');
        box.appendChild(b1); box.appendChild(b2);
      }
    }

    /* ---------- Items ---------- */
    function renderItems(){
      state.items = read(LS.items) || [];
      const q = (document.getElementById('search')?.value || '').toLowerCase().trim();
      const list = document.getElementById('items-list');
      list.innerHTML = '';
      const filtered = state.items.filter(it => it.title.toLowerCase().includes(q) || (it.sku||'').toLowerCase().includes(q));
      if(filtered.length === 0){
        list.innerHTML = '<div class="muted-sm">Belum ada barang. Klik "Tambah Barang" atau gunakan "Isi Demo".</div>'; return;
      }
      filtered.forEach(it => {
        const row = document.createElement('div'); row.className='item';
        const t = document.createElement('div'); t.className='thumb'; t.textContent = (it.title[0]||'B').toUpperCase();
        const meta = document.createElement('div'); meta.className='meta';
        meta.innerHTML = '<div style="display:flex; justify-content:space-between; gap:12px"><div><strong>' + escapeHtml(it.title) + '</strong><div class="muted-sm">' + escapeHtml(it.sku||'') + '</div></div><div style="text-align:right"><div class="small muted-sm">Stok</div><div style="font-weight:800">' + (it.stock||0) + '</div></div></div><div style="height:6px"></div><div class="muted-sm small">' + escapeHtml(it.desc||'—') + '</div>';
        const action = document.createElement('div');
        action.style.display='flex'; action.style.flexDirection='column'; action.style.gap='8px';
        const titipBtn = document.createElement('button'); titipBtn.className='btn'; titipBtn.textContent='Titipkan'; titipBtn.onclick = ()=> toggleSelectItemForTitip(it.id);
        const editBtn = document.createElement('button'); editBtn.className='btn ghost'; editBtn.textContent='Edit'; editBtn.onclick = ()=> openEditItem(it.id);
        action.appendChild(titipBtn);
        action.appendChild(editBtn);
        row.appendChild(t); row.appendChild(meta); row.appendChild(action);
        list.appendChild(row);
      });
      renderTitipItemsUI();
    }

    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])) }

    /* ---------- Quick add / Add item ---------- */
    function openQuickAdd(){
      openModal(`<div class="card"><h3>Tambah Barang Cepat</h3>
        <div style="height:8px"></div>
        <label>Nama barang</label><input id="qa-title" />
        <label>Stok</label><input id="qa-stock" type="number" value="1" />
        <label>SKU (opsional)</label><input id="qa-sku" />
        <div style="height:12px"></div>
        <div style="display:flex; justify-content:flex-end; gap:8px">
          <button class="btn ghost" onclick="closeModal()">Batal</button>
          <button class="btn" onclick="submitQuickAdd()">Tambah</button>
        </div>
      </div>`);
    }
    function submitQuickAdd(){
      const title = document.getElementById('qa-title').value.trim();
      const stock = Number(document.getElementById('qa-stock').value) || 0;
      const sku = document.getElementById('qa-sku').value.trim();
      if(!title){ alert('Nama barang wajib'); return; }
      const it = {id: uid('item'), title, stock, sku, desc:'(ditambahkan cepat)', createdAt: now()};
      state.items.unshift(it); persistAll();
      closeModal(); renderAll();
      toast('Barang ditambahkan');
    }

    function openAddItemForm(){
      openModal(`<div class="card"><h3>Tambah Barang</h3>
        <form onsubmit="event.preventDefault(); submitAddItem()">
        <label>Nama barang</label><input id="ai-title" required />
        <label>Deskripsi</label><textarea id="ai-desc"></textarea>
        <div class="row"><div><label>Stok</label><input id="ai-stock" type="number" required value="1"/></div><div><label>SKU</label><input id="ai-sku" /></div></div>
        <div style="height:12px"></div>
        <div style="display:flex; gap:8px; justify-content:flex-end"><button class="btn ghost" onclick="closeModal();return false;">Batal</button><button class="btn">Simpan</button></div>
        </form></div>`);
    }
    function submitAddItem(){
      const title = document.getElementById('ai-title').value.trim();
      const desc = document.getElementById('ai-desc').value.trim();
      const stock = Number(document.getElementById('ai-stock').value) || 0;
      const sku = document.getElementById('ai-sku').value.trim();
      if(!title){ alert('Nama barang wajib'); return; }
      const it = {id: uid('item'), title, desc, stock, sku, createdAt: now()};
      state.items.unshift(it); persistAll();
      closeModal(); renderAll();
      toast('Barang tersimpan');
    }

    function openEditItem(id){
      const it = state.items.find(i=>i.id===id);
      if(!it) return alert('Barang tidak ditemukan');
      openModal(`<div class="card"><h3>Edit Barang</h3>
        <form onsubmit="event.preventDefault(); submitEditItem('${id}')">
        <label>Nama barang</label><input id="ei-title" required value="${escapeHtml(it.title)}" />
        <label>Deskripsi</label><textarea id="ei-desc">${escapeHtml(it.desc||'')}</textarea>
        <div class="row"><div><label>Stok</label><input id="ei-stock" type="number" required value="${Number(it.stock||0)}"/></div><div><label>SKU</label><input id="ei-sku" value="${escapeHtml(it.sku||'')}" /></div></div>
        <div style="height:12px"></div>
        <div style="display:flex; gap:8px; justify-content:flex-end"><button class="btn ghost" onclick="closeModal();return false;">Batal</button><button class="btn">Simpan</button></div>
        </form></div>`);
    }
    function submitEditItem(id){
      const it = state.items.find(i=>i.id===id);
      if(!it) return alert('Tidak ditemukan');
      it.title = document.getElementById('ei-title').value.trim();
      it.desc = document.getElementById('ei-desc').value.trim();
      it.stock = Number(document.getElementById('ei-stock').value) || 0;
      it.sku = document.getElementById('ei-sku').value.trim();
      persistAll(); closeModal(); renderAll(); toast('Perubahan tersimpan');
    }

    /* ---------- Titip (deposit) ---------- */
    let titipSelection = {}; // {itemId: qty}
    function toggleSelectItemForTitip(itemId){
      const it = state.items.find(i=>i.id===itemId);
      if(!it) return;
      if(titipSelection[itemId]){
        delete titipSelection[itemId];
      } else {
        titipSelection[itemId] = 1;
      }
      renderTitipItemsUI();
      showView('titip');
    }

    function renderTitipItemsUI(){
      const container = document.getElementById('titip-items');
      if(!container) return;
      container.innerHTML = '';
      state.items.forEach(it=>{
        const card = document.createElement('div'); card.className='item';
        const left = document.createElement('div'); left.style.display='flex'; left.style.gap='12px'; left.style.alignItems='center';
        const thumb = document.createElement('div'); thumb.className='thumb'; thumb.textContent = (it.title[0]||'B').toUpperCase();
        const meta = document.createElement('div'); meta.className='meta';
        meta.innerHTML = '<strong>' + escapeHtml(it.title) + '</strong><div class="muted-sm small">Stok: ' + (it.stock||0) + '</div>';
        left.appendChild(thumb); left.appendChild(meta);
        const right = document.createElement('div'); right.style.display='flex'; right.style.flexDirection='column'; right.style.alignItems='flex-end'; right.style.gap='8px';
        const checkbox = document.createElement('input'); checkbox.type='checkbox'; checkbox.checked = !!titipSelection[it.id];
        checkbox.onchange = (e)=> {
          if(e.target.checked) titipSelection[it.id] = 1; else delete titipSelection[it.id];
          renderTitipItemsUI();
        };
        const qty = document.createElement('input'); qty.type='number'; qty.value = titipSelection[it.id] || 1; qty.min=1; qty.style.width='80px';
        qty.oninput = ()=> {
          const v = Number(qty.value) || 1; titipSelection[it.id] = v;
        };
        const note = document.createElement('input'); note.placeholder='Catatan (opsional)'; note.style.width='160px';
        // persist note in selection map: store as object {qty, note}
        if(typeof titipSelection[it.id] === 'number') titipSelection[it.id] = {qty:titipSelection[it.id], note:''};
        if(typeof titipSelection[it.id] === 'object') qty.value = titipSelection[it.id].qty;
        qty.oninput = ()=> {
          if(!titipSelection[it.id]) titipSelection[it.id] = {qty:1, note:''};
          titipSelection[it.id].qty = Number(qty.value) || 1;
        };
        note.oninput = ()=> {
          if(!titipSelection[it.id]) titipSelection[it.id] = {qty:1, note:''};
          titipSelection[it.id].note = note.value;
        };

        right.appendChild(checkbox);
        right.appendChild(qty);
        right.appendChild(note);
        card.appendChild(left); card.appendChild(right);
        container.appendChild(card);
      });
    }

    function createDepositFromSelected(){
      if(!state.currentUser){ alert('Silakan login terlebih dahulu untuk menitipkan barang.'); showView('login'); return; }
      const keys = Object.keys(titipSelection);
      if(keys.length === 0) return alert('Pilih minimal 1 barang untuk dititipkan');
      // transform selection, validate stock
      const itemsToDeposit = [];
      for(const id of keys){
        const sel = titipSelection[id];
        const details = typeof sel === 'object' ? sel : {qty: sel, note: ''};
        const it = state.items.find(x=>x.id===id);
        if(!it) return alert('Barang tidak ditemukan: ' + id);
        if(Number(details.qty) > Number(it.stock)){ return alert('Stok tidak cukup untuk ' + it.title); }
        itemsToDeposit.push({itemId:id, title:it.title, qty: Number(details.qty), note: details.note || ''});
      }
      // reduce stock
      itemsToDeposit.forEach(d=>{
        const it = state.items.find(x=>x.id===d.itemId);
        it.stock = Number(it.stock) - Number(d.qty);
      });
      const deposit = {
        id: uid('dep'), userId: state.currentUser.id, items: itemsToDeposit, status: 'active', createdAt: now()
      };
      state.deposits.unshift(deposit);
      // clear selection
      titipSelection = {};
      persistAll(); renderAll(); toast('Titipan berhasil dibuat');
      showView('history');
    }

    /* ---------- HISTORY ---------- */
    function renderHistory(){
      const list = document.getElementById('history-list');
      list.innerHTML = '';
      const rows = state.deposits.filter(d => !state.currentUser || d.userId === state.currentUser.id);
      if(rows.length === 0) { list.innerHTML = '<div class="muted-sm">Belum ada riwayat titip.</div>'; return; }
      rows.forEach(d=>{
        const card = document.createElement('div'); card.className='card';
        card.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center"><div><strong>ID: '+d.id+'</strong><div class="muted-sm small">Tanggal: '+d.createdAt+'</div></div><div><span class="pill">'+d.status+'</span></div></div>';
        const inner = document.createElement('div'); inner.style.marginTop='8px';
        d.items.forEach(it=>{
          const r = document.createElement('div'); r.style.display='flex'; r.style.justifyContent='space-between'; r.style.gap='12px'; r.style.padding='6px 0'; r.innerHTML = '<div><strong>'+escapeHtml(it.title)+'</strong><div class="muted-sm small">Catatan: '+escapeHtml(it.note||'—')+'</div></div><div class="muted-sm">Qty: '+it.qty+'</div>';
          inner.appendChild(r);
        });
        card.appendChild(inner);
        list.appendChild(card);
      });
    }

    /* ---------- AUTH ---------- */
    function submitRegister(){
      const name = document.getElementById('reg-name').value.trim();
      const username = document.getElementById('reg-username').value.trim();
      const email = document.getElementById('reg-email').value.trim();
      const pass = document.getElementById('reg-pass').value;
      const role = document.getElementById('reg-role').value || 'user';
      if(!name || !username || !email || !pass) return alert('Lengkapi data');
      // unique username/email
      if(state.users.some(u=>u.username===username || u.email===email)) return alert('Username atau email sudah digunakan');
      const u = {id: uid('user'), name, username, email, pass, role, createdAt: now()};
      state.users.unshift(u); persistAll();
      toast('Akun terdaftar. Silakan masuk.');
      showView('login');
    }

    function submitLogin(){
      const val = document.getElementById('login-email').value.trim();
      const pass = document.getElementById('login-pass').value;
      const user = state.users.find(u => u.email === val || u.username === val);
      if(!user || user.pass !== pass) return alert('Akun tidak ditemukan atau kata sandi salah');
      state.currentUser = {...user};
      persistAll();
      toast('Login berhasil — Halo, ' + user.name);
      showView('dashboard');
    }

    function logout(){
      state.currentUser = null;
      persistAll();
      toast('Anda telah keluar');
      renderAll();
      showView('dashboard');
    }

    /* ---------- Admin renders ---------- */
    function renderAdmin(){
      const adminItems = document.getElementById('admin-items');
      const adminDeposits = document.getElementById('admin-deposits');
      adminItems.innerHTML = '';
      adminDeposits.innerHTML = '';

      state.items.forEach(it=>{
        const div = document.createElement('div'); div.style.display='flex'; div.style.justifyContent='space-between'; div.style.gap='8px'; div.style.padding='6px 0';
        div.innerHTML = '<div><strong>' + escapeHtml(it.title) + '</strong><div class="muted-sm small">SKU: '+escapeHtml(it.sku||'')+'</div></div><div><button class="btn ghost" onclick="openEditItem(\\''+it.id+'\\')">Edit</button><button class="btn" style="margin-left:6px" onclick="removeItem(\\''+it.id+'\\')">Hapus</button></div>';
        adminItems.appendChild(div);
      });

      state.deposits.forEach(d=>{
        const div = document.createElement('div'); div.style.borderTop='1px solid rgba(255,255,255,0.03)'; div.style.padding='8px 0';
        div.innerHTML = '<div style="display:flex; justify-content:space-between"><div><strong>'+d.id+'</strong><div class="muted-sm small">User: '+getUserName(d.userId)+'</div></div><div><span class="pill">'+d.status+'</span></div></div>';
        const manage = document.createElement('div'); manage.style.marginTop='6px';
        if(d.status === 'active'){
          const done = document.createElement('button'); done.className='btn ghost'; done.textContent='Selesaikan'; done.onclick = ()=> adminCompleteDeposit(d.id);
          manage.appendChild(done);
        }
        const cancel = document.createElement('button'); cancel.className='btn'; cancel.style.marginLeft='8px'; cancel.textContent='Batalkan'; cancel.onclick = ()=> adminCancelDeposit(d.id);
        manage.appendChild(cancel);
        div.appendChild(manage);
        adminDeposits.appendChild(div);
      });
    }

    function getUserName(id){ const u = state.users.find(x=>x.id===id); return u ? u.name + ' ('+u.username+')' : id }

    function adminCompleteDeposit(depId){
      const d = state.deposits.find(x=>x.id===depId);
      if(!d) return alert('Tidak ditemukan');
      d.status = 'completed';
      persistAll(); renderAll(); toast('Titipan diselesaikan');
    }
    function adminCancelDeposit(depId){
      const d = state.deposits.find(x=>x.id===depId);
      if(!d) return alert('Tidak ditemukan');
      // restore stock
      d.items.forEach(it=> {
        const item = state.items.find(x=>x.id===it.itemId);
        if(item) item.stock = Number(item.stock || 0) + Number(it.qty);
      });
      d.status = 'cancelled';
      persistAll(); renderAll(); toast('Titipan dibatalkan dan stok dikembalikan');
    }

    function removeItem(id){
      if(!confirm('Hapus barang ini?')) return;
      state.items = state.items.filter(i=>i.id!==id); persistAll(); renderAll();
      toast('Barang dihapus');
    }

    /* ---------- UI modals / toast ---------- */
    function openModal(html){
      const root = document.getElementById('modal-root'); root.style.display = 'flex';
      document.getElementById('modal').innerHTML = html;
    }
    function closeModal(){ document.getElementById('modal-root').style.display = 'none'; document.getElementById('modal').innerHTML = ''; }

    function toast(msg, timeout=1800){
      const t = document.createElement('div');
      t.style.position='fixed'; t.style.right='18px'; t.style.bottom='18px'; t.style.background='rgba(2,6,23,0.8)'; t.style.padding='10px 14px'; t.style.borderRadius='10px';
      t.style.boxShadow='0 6px 18px rgba(2,6,23,0.6)'; t.style.zIndex = 99999; t.style.fontWeight = 700; t.textContent = msg;
      document.body.appendChild(t);
      setTimeout(()=> t.style.opacity = '0.0', timeout-300);
      setTimeout(()=> t.remove(), timeout);
    }

    /* ---------- Mini helpers ---------- */
    function openEditItemEscaped(id){
      // helper to handle quoting in admin render
      openEditItem(id);
    }

    /* ---------- Demo seed ---------- */
    function seedDemo(){
      state.items = [
        {id: uid('item'), title:'Kulkas 2 Pintu', desc:'Bekas tapi bagus', stock:2, sku:'KRF-22', createdAt: now()},
        {id: uid('item'), title:'Sepeda Lipat', desc:'Ringan, untuk kota', stock:5, sku:'SPD-LF', createdAt: now()},
        {id: uid('item'), title:'Koper Travel', desc:'Koper 24 inch', stock:7, sku:'KPR-24', createdAt: now()},
        {id: uid('item'), title:'Laptop Bekas', desc:'i5, RAM 8GB', stock:1, sku:'LTP-55', createdAt: now()},
      ];
      persistAll(); renderAll(); toast('Demo items terisi');
    }

    /* ---------- Render whole app ---------- */
    function renderAll(){
      state.items = read(LS.items) || [];
      state.users = read(LS.users) || [];
      state.deposits = read(LS.deposits) || [];
      state.currentUser = read(LS.current) || state.currentUser;

      document.getElementById('stat-items').textContent = state.items.length;
      document.getElementById('stat-deposits').textContent = state.deposits.filter(d=>d.status==='active').length;
      document.getElementById('stat-users').textContent = state.users.length;
      document.getElementById('stat-admin').textContent = state.currentUser ? state.currentUser.username : 'guest';

      // user summary box
      const ub = document.getElementById('user-box');
      const authActions = document.getElementById('auth-actions');
      if(state.currentUser){
        ub.innerHTML = '<strong>' + escapeHtml(state.currentUser.name) + '</strong><div class="muted-sm small">' + escapeHtml(state.currentUser.email || state.currentUser.username) + '</div>';
        authActions.innerHTML = '<button class="btn" onclick="showView(\\'account\\')">Profil</button><button class="btn ghost" onclick="logout()">Keluar</button>';
      } else {
        ub.innerHTML = '<div class="muted-sm">Belum login</div>';
        authActions.innerHTML = `<button class="btn" onclick="showView('login')">Masuk</button><button class="btn ghost" onclick="showView('register')">Daftar</button>`;
      }

      // recent items show in dashboard
      const recent = document.getElementById('recent-items');
      recent.innerHTML = '';
      state.items.slice(0,6).forEach(it=>{
        const card = document.createElement('div'); card.className='card'; card.style.padding='12px';
        card.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center"><div><strong>'+escapeHtml(it.title)+'</strong><div class="muted-sm small">'+escapeHtml(it.sku||'')+'</div></div><div class="muted-sm">Stok: '+(it.stock||0)+'</div></div><div style="height:8px"></div><div class="muted-sm small">'+escapeHtml(it.desc||'—')+'</div>';
        recent.appendChild(card);
      });

      // items list
      renderItems();
      // history
      renderHistory();

      // admin view
      const adminView = document.getElementById('view-admin');
      if(state.currentUser && state.currentUser.role === 'admin'){
        adminView.style.display = state.view === 'admin' ? '' : 'none';
        renderAdmin();
      } else {
        adminView.style.display = 'none';
      }

      // account info
      const profile = document.getElementById('profile-info');
      if(state.currentUser){
        profile.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center"><div><strong>'+escapeHtml(state.currentUser.name)+'</strong><div class="muted-sm small">'+escapeHtml(state.currentUser.email)+'</div></div><div class="muted-sm">'+escapeHtml(state.currentUser.role)+'</div></div>';
      } else profile.innerHTML = '<div class="muted-sm">Silakan masuk untuk melihat profil.</div>';

      // auth forms container
      const af = document.getElementById('auth-forms');
      af.innerHTML = '';
      // keep register/login sections accessible via sidebar
    }

    /* ---------- Small helpers for admin render quoting issue ---------- */
    window.removeItem = removeItem;
    window.openEditItem = openEditItem;
    window.adminCompleteDeposit = adminCompleteDeposit;
    window.adminCancelDeposit = adminCancelDeposit;

    /* ---------- Init ---------- */
    renderAll();
    showView('dashboard');

    /* ---------- Extra: simple escaping for inserted admin HTML ---------- */
    // nothing further

    /* ---------- Some convenience: handle direct opens ---------- */
    function openModalHTMLFromString(str){
      openModal(str);
    }

  </script>
</body>
</html>
