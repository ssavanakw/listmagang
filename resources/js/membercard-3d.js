// resources/js/membercard-3d.js
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';

const MODAL_ID = 'modalMembercard3d';
const CANVAS_ID = 'membercard3dCanvas';

let renderer, scene, camera, controls, pmrem, envMap, animId;
let currentModel = null;
let resizeObs = null;
let autoRotateTimeout;
let cleaning = false;
let ground = null;

/* ======================= Utilities ======================= */
function disposeMaterial(mat) {
  if (!mat) return;
  [
    'map','normalMap','roughnessMap','metalnessMap','emissiveMap','aoMap',
    'bumpMap','displacementMap','alphaMap','clearcoatNormalMap'
  ].forEach(k => mat[k]?.dispose?.());
  mat.dispose?.();
}
function disposeObject3D(root) {
  if (!root) return;
  root.traverse(obj => {
    if (obj.isMesh) {
      obj.geometry?.dispose?.();
      if (Array.isArray(obj.material)) obj.material.forEach(disposeMaterial);
      else disposeMaterial(obj.material);
    }
  });
}
function estimateFacing(mesh) {
  if (!mesh?.isMesh || !mesh.geometry?.attributes?.position) return 0;
  const pos = mesh.geometry.attributes.position;
  if (pos.count < 3) return 0;
  const v0 = new THREE.Vector3().fromBufferAttribute(pos, 0);
  const v1 = new THREE.Vector3().fromBufferAttribute(pos, 1);
  const v2 = new THREE.Vector3().fromBufferAttribute(pos, 2);
  v0.applyMatrix4(mesh.matrixWorld);
  v1.applyMatrix4(mesh.matrixWorld);
  v2.applyMatrix4(mesh.matrixWorld);
  const e1 = new THREE.Vector3().subVectors(v1, v0);
  const e2 = new THREE.Vector3().subVectors(v2, v0);
  const n = new THREE.Vector3().crossVectors(e1, e2).normalize();
  return n.dot(new THREE.Vector3(0, 0, 1));
}
function fitCameraToObject(cam, object, controls, options = {}) {
  const { offset = 1.24, pad = 0.05, minDist = 0.6, maxDist = 6.0 } = options;
  const box = new THREE.Box3().setFromObject(object);
  const size = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());
  controls.target.copy(center);

  const maxDim = Math.max(size.x, size.y, size.z);
  const fov = THREE.MathUtils.degToRad(cam.fov);
  let camZ = Math.abs((maxDim / 2) / Math.tan(fov / 2));
  camZ *= offset;

  const dir = new THREE.Vector3(0.78, 0.42, 1.0).normalize();
  const pos = center.clone().addScaledVector(dir, camZ);
  cam.position.copy(pos);

  const diag = size.length();
  cam.near = Math.max(0.01, camZ / 120);
  cam.far  = Math.max(100, diag * 40);
  cam.updateProjectionMatrix();

  const distNow = cam.position.distanceTo(center);
  controls.minDistance = Math.max(minDist, Math.min(distNow * 0.25, 2.0));
  controls.maxDistance = Math.max(maxDist, distNow * 3.5);

  const look = center.clone().add(new THREE.Vector3(0, Math.max(0.08, size.y * 0.04), 0));
  controls.target.copy(look);
  controls.update();

  const aspect = cam.aspect || 1;
  const halfFovH = Math.atan(Math.tan(fov / 2) * aspect);
  const needZ = Math.max(size.x, size.y) / (2 * Math.tan(halfFovH)) * (1 + pad);
  if (needZ * 1.05 > camZ) {
    const delta = needZ * 1.05 - camZ;
    cam.position.addScaledVector(dir, delta);
    cam.updateProjectionMatrix();
  }

  // posisikan ground tepat di bawah objek untuk contact shadow elegan
  if (ground) {
    ground.position.y = box.min.y - 0.002;
    ground.scale.setScalar(Math.max(6, Math.max(size.x, size.z) * 6));
  }
}

/* ======================= Cleanup ======================= */
function disposeViewer() {
  if (cleaning) return;
  cleaning = true;
  try { cancelAnimationFrame(animId); } catch {}
  try { clearTimeout(autoRotateTimeout); } catch {}

  if (resizeObs) { try { resizeObs.disconnect(); } catch {} resizeObs = null; }
  if (controls) { try { controls.dispose(); } catch {} controls = null; }

  if (currentModel) {
    try { disposeObject3D(currentModel); scene?.remove(currentModel); } catch {}
    currentModel = null;
  }
  if (ground) {
    try { ground.geometry?.dispose?.(); ground.material?.dispose?.(); scene?.remove(ground); } catch {}
    ground = null;
  }
  if (pmrem) { try { pmrem.dispose(); } catch {} pmrem = null; }
  if (scene) { try { scene.clear(); } catch {} scene = null; }

  if (renderer) {
    try { renderer.forceContextLoss?.(); renderer.dispose?.(); renderer.domElement?.remove?.(); } catch {}
    renderer = null;
  }
  envMap = null; camera = null; cleaning = false;
}

/* ======================= Viewer ======================= */
function initViewer(container, modelUrl) {
  /* Renderer */
  renderer = new THREE.WebGLRenderer({
    antialias: true,
    alpha: true,
    powerPreference: 'high-performance',
    preserveDrawingBuffer: false,
  });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.setSize(container.clientWidth, container.clientHeight, false);
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.0; // lebih natural/elegan
  // Bayangan lembut elegan
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;

  // Lindungi context
  renderer.domElement.addEventListener('webglcontextlost', (e) => e.preventDefault());
  container.innerHTML = '';
  container.appendChild(renderer.domElement);

  /* Hook tombol Download PNG */
  const dlBtn = document.getElementById('download3dButton');
  if (dlBtn) {
    dlBtn.textContent = 'Download PNG';
    dlBtn.setAttribute('href', '#');
    dlBtn.addEventListener('click', async (e) => {
      e.preventDefault();

      // 1) pastikan model ada; kalau belum, pakai scene
      const targetObj = currentModel || scene;

      // 2) animasi ke sudut "Default Elegan" dulu
      await goToDefaultElegantAngle(targetObj, 420);

      // 3) nama file otomatis dari data-name
      const nm = (renderer?.domElement?.parentElement?.dataset?.name || 'membercard');
      const fname = `membercard-${_slugifyNameForFile(nm).toUpperCase()}.png`;

      // 4) capture PNG transparan 3x
      captureAndDownloadPNG({
        filename: fname,
        scale: 3,
        transparent: true
      });
    });
  }




  /* Scene & Environment */
  scene = new THREE.Scene();
  scene.background = new THREE.Color(0x0a0f18);

  pmrem = new THREE.PMREMGenerator(renderer);
  const env = new RoomEnvironment(renderer);
  envMap = pmrem.fromScene(env).texture;
  scene.environment = envMap;

  /* Camera */
  camera = new THREE.PerspectiveCamera(
    45,
    Math.max(1, container.clientWidth) / Math.max(1, container.clientHeight),
    0.01,
    2000
  );
  camera.position.set(2.1, 0.4, 3.0);
  camera.lookAt(0, 0.2, 0);

  /* ===== Lighting: ELEGAN =====
     - Key light hangat (utama) + bayangan lembut
     - Fill biru muda sangat halus, sekadar mengangkat shadow
     - Rim/back light tipis untuk memisahkan objek dari background
     - EnvMap intensity rendah agar tidak berlebihan
  */
  const hemi = new THREE.HemisphereLight(0xffffff, 0x1a1a1a, 0.45);
  scene.add(hemi);

  const key = new THREE.DirectionalLight(0xfff2e0, 1.15); // hangat, lembut
  key.position.set(6, 7.5, 4.2);
  key.castShadow = true;
  key.shadow.mapSize.set(1024, 1024);
  key.shadow.camera.near = 0.1;
  key.shadow.camera.far = 50;
  key.shadow.camera.left = -8;
  key.shadow.camera.right = 8;
  key.shadow.camera.top = 8;
  key.shadow.camera.bottom = -8;
  key.shadow.radius = 2; // blur lembut
  scene.add(key);

  const fill = new THREE.DirectionalLight(0x9ec9ff, 0.24); // dingin, sangat halus
  fill.position.set(-6, 3.2, -4.5);
  fill.castShadow = false;
  scene.add(fill);

  const rim = new THREE.SpotLight(0xffffff, 0.55, 120, 0.5, 0.4, 1.2);
  rim.position.set(-2.6, 4.5, 6.0);
  rim.target.position.set(0, 0, 0);
  rim.castShadow = true;
  rim.shadow.mapSize.set(1024, 1024);
  rim.shadow.radius = 2;
  scene.add(rim);
  scene.add(rim.target);

  // EnvMap intensity “sopan” via material (lihat di bawah)

  // Contact shadow tipis, elegan
  const shadowMat = new THREE.ShadowMaterial({ opacity: 0.18 });
  ground = new THREE.Mesh(new THREE.PlaneGeometry(20, 20), shadowMat);
  ground.rotation.x = -Math.PI / 2;
  ground.receiveShadow = true;
  ground.position.y = -0.001; // akan diset ulang setelah model loaded
  scene.add(ground);

  /* Controls */
  controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.08;
  controls.rotateSpeed = 0.45;
  controls.zoomSpeed = 0.75;
  controls.enablePan = false;

  controls.minPolarAngle = THREE.MathUtils.degToRad(15);
  controls.maxPolarAngle = THREE.MathUtils.degToRad(165);
  controls.minAzimuthAngle = THREE.MathUtils.degToRad(-80);
  controls.maxAzimuthAngle = THREE.MathUtils.degToRad(80);
  controls.minDistance = 1.2;
  controls.maxDistance = 5.0;

  controls.autoRotate = true;
  controls.autoRotateSpeed = 0.85;

  /* Loading */
  const manager = new THREE.LoadingManager();
  manager.onStart = () => container.setAttribute('data-loading', 'true');
  manager.onLoad = () => container.removeAttribute('data-loading');

  const loader = new GLTFLoader(manager);
  loader.load(
    modelUrl,
    (gltf) => {
      currentModel = gltf.scene;
      currentModel.position.set(0, 0, 0);
      currentModel.rotation.set(0, 0, 0);
      currentModel.scale.set(1, 1, 1);
      currentModel.updateMatrixWorld(true);
      scene.add(currentModel);

      // Data → texture
      const d = container.dataset || {};
      const cardData = {
        brand: d.brand || 'magangjogja.com',
        name: d.name || 'MUHAMMAD ZAKI AUZAN',
        id: d.id || 'MJ25067',
        angkatan: d.angkatan || '2025',
        instansi: d.instansi || 'UNIVERSITAS AHMAD DAHLAN',
      };
      const frontTex = buildCardCanvasTexture(cardData);

      // Pilih front/back/edge
      const meshes = [];
      currentModel.traverse(o => { if (o.isMesh) meshes.push(o); });

      let frontMesh = null, backMesh = null;
      for (const m of meshes) {
        const nm = (m.name || '').toLowerCase();
        if (!frontMesh && (nm.includes('front') || nm.includes('face'))) frontMesh = m;
        if (!backMesh && (nm.includes('back') || nm.includes('reverse') || nm.includes('rear'))) backMesh = m;
      }
      if (!frontMesh || !backMesh) {
        let bestFront = { score: -Infinity, mesh: null };
        let bestBack = { score: +Infinity, mesh: null };
        meshes.forEach(m => {
          m.updateMatrixWorld(true);
          const score = estimateFacing(m);
          if (score > bestFront.score) bestFront = { score, mesh: m };
          if (score < bestBack.score) bestBack = { score, mesh: m };
        });
        if (!frontMesh) frontMesh = bestFront.mesh;
        if (!backMesh) backMesh = bestBack.mesh !== frontMesh ? bestBack.mesh : null;
      }

      // Material elegan + env intensity sopan
      const envIntensity = 0.85; // cukup berkilau tanpa “norak”
      if (frontMesh) {
        frontMesh.material = new THREE.MeshPhysicalMaterial({
          map: frontTex,
          metalness: 0.12,
          roughness: 0.32,
          clearcoat: 0.25,
          clearcoatRoughness: 0.4,
          envMapIntensity: envIntensity,
          side: THREE.FrontSide,
        });
      }
      if (backMesh) {
        backMesh.material = new THREE.MeshPhysicalMaterial({
          color: new THREE.Color(0x0f2b27),
          metalness: 0.12,
          roughness: 0.36,
          clearcoat: 0.18,
          clearcoatRoughness: 0.5,
          envMapIntensity: envIntensity,
          side: THREE.FrontSide,
        });
      }
      meshes.forEach(m => {
        if (m !== frontMesh && m !== backMesh) {
          m.material = new THREE.MeshPhysicalMaterial({
            color: new THREE.Color(0xC9A23E),
            metalness: 1.0,
            roughness: 0.28,        // sedikit lebih matte → elegan
            clearcoat: 0.35,
            clearcoatRoughness: 0.35,
            envMapIntensity: envIntensity,
          });
        }
        // Aktifkan bayangan agar contact shadow tampak tipis-indah
        m.castShadow = true;
        m.receiveShadow = false;
      });

      // Fit kamera + posisikan ground tepat di bawah model
      fitCameraToObject(camera, currentModel, controls, { offset: 1.26 });

      // Interaksi elastis & snap
      setupRubberBandAndSnap(controls);

      // Render loop
      renderLoop();
    },
    undefined,
    (err) => {
      container.innerHTML = `<div class="w-full h-full grid place-items-center text-white/70 text-xs p-4">
        Gagal memuat model. Pastikan file .glb tersedia & link storage aktif.<br>${err?.message || ''}
      </div>`;
    }
  );

  /* Resize handling */
  const onResize = () => {
    if (!renderer || !camera) return;
    const w = Math.max(1, container.clientWidth);
    const h = Math.max(1, container.clientHeight);
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
  };
  resizeObs = new ResizeObserver(onResize);
  resizeObs.observe(container);

  const onDPR = () => {
    if (!renderer) return;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  };
  window.addEventListener('resize', onDPR, { passive: true });

  const onKey = (e) => {
    if (!controls) return;
    if (e.key === 'r' || e.key === 'R') {
      fitCameraToObject(camera, currentModel || scene, controls, { offset: 1.26 });
    }
  };
  window.addEventListener('keydown', onKey);

  /* Auto-rotate pause saat interaksi */
  function wheel() {
    if (!controls) return;
    controls.autoRotate = false;
    clearTimeout(autoRotateTimeout);
    autoRotateTimeout = setTimeout(() => { if (controls) controls.autoRotate = true; }, 900);
  }
  function pointerDown() {
    if (!controls) return;
    controls.autoRotate = false;
    clearTimeout(autoRotateTimeout);
  }
  function pointerUp() {
    if (!controls) return;
    clearTimeout(autoRotateTimeout);
    autoRotateTimeout = setTimeout(() => { if (controls) controls.autoRotate = true; }, 900);
  }

  renderer.domElement.addEventListener('wheel', wheel, { passive: true });
  renderer.domElement.addEventListener('pointerdown', pointerDown, { passive: true });
  renderer.domElement.addEventListener('pointerup', pointerUp, { passive: true });
  renderer.domElement.addEventListener('pointercancel', pointerUp, { passive: true });
  renderer.domElement.addEventListener('pointerleave', pointerUp, { passive: true });

  const cleanup = () => {
    window.removeEventListener('resize', onDPR);
    window.removeEventListener('keydown', onKey);
    renderer?.domElement?.removeEventListener('pointerdown', pointerDown);
    renderer?.domElement?.removeEventListener('pointerup', pointerUp);
    renderer?.domElement?.removeEventListener('pointercancel', pointerUp);
    renderer?.domElement?.removeEventListener('pointerleave', pointerUp);
    renderer?.domElement?.removeEventListener('wheel', wheel);
    try { setupRubberBandAndSnap._detach?.(); } catch {}
    disposeViewer();
  };
  return cleanup;
}

/* ======================= Rubber band & Snap ======================= */
function setupRubberBandAndSnap(controls) {
  const eps = THREE.MathUtils.degToRad(0.5);
  const rubber = {
    slack: THREE.MathUtils.degToRad(26),
    k: 9.5,
    c: 8.8,
    maxStep: THREE.MathUtils.degToRad(2.2),
  };

  let dragging = false;
  let settling = false;
  let azVel = 0;
  let lastTime = performance.now();

  function shortestDeltaAngle(current, target) {
    let d = target - current;
    while (d > Math.PI) d -= Math.PI * 2;
    while (d < -Math.PI) d += Math.PI * 2;
    return d;
  }
  function softTargetAz(az, minA, maxA, slack) {
    if (az < minA) {
      const over = minA - az;
      const tail = slack * (1 - Math.exp(-over / slack));
      return minA - tail;
    } else if (az > maxA) {
      const over = az - maxA;
      const tail = slack * (1 - Math.exp(-over / slack));
      return maxA + tail;
    }
    return az;
  }
  function handleAutoReverse() {
    if (!controls?.autoRotate) return;
    const az = controls.getAzimuthalAngle();
    const minA = controls.minAzimuthAngle ?? -Infinity;
    const maxA = controls.maxAzimuthAngle ?? +Infinity;
    if (controls.autoRotateSpeed > 0 && az <= minA + eps) {
      controls.autoRotateSpeed = -Math.abs(controls.autoRotateSpeed || 0.6);
    } else if (controls.autoRotateSpeed < 0 && az >= maxA - eps) {
      controls.autoRotateSpeed =  Math.abs(controls.autoRotateSpeed || 0.6);
    }
  }
  function applyRubberBand(now) {
    if (!dragging || settling) return;
    const dt = Math.max(0.001, Math.min(0.033, (now - lastTime) / 1000));
    lastTime = now;

    const minA = controls.minAzimuthAngle ?? -Infinity;
    const maxA = controls.maxAzimuthAngle ?? +Infinity;

    const az = controls.getAzimuthalAngle();
    const target = softTargetAz(az, minA, maxA, rubber.slack);

    const err = shortestDeltaAngle(az, target);
    azVel += (-rubber.k * err - rubber.c * azVel) * dt;

    let step = azVel * dt * 60;
    if (step > rubber.maxStep) step = rubber.maxStep;
    if (step < -rubber.maxStep) step = -rubber.maxStep;

    if (Math.abs(step) > 1e-6) controls.rotateLeft(step);
  }
  function easeOutCubic(t){ return 1 - Math.pow(1 - t, 3); }
  function animateAzTo(targetAz, duration = 420) {
    if (settling) return;
    settling = true;

    const start = performance.now();
    const startAz = controls.getAzimuthalAngle();
    const prevAutoRotate = controls.autoRotate;
    controls.autoRotate = false;

    function tick(now) {
      const t = Math.min(1, (now - start) / duration);
      const k = easeOutCubic(t);
      const delta = shortestDeltaAngle(startAz, targetAz) * k;
      const current = startAz + delta;

      const live = controls.getAzimuthalAngle();
      const step = shortestDeltaAngle(live, current);
      controls.rotateLeft(step);
      controls.update();

      if (t < 1) {
        requestAnimationFrame(tick);
      } else {
        const live2 = controls.getAzimuthalAngle();
        const step2 = shortestDeltaAngle(live2, targetAz);
        if (Math.abs(step2) > 1e-6) controls.rotateLeft(step2);
        controls.update();
        settling = false;
        setTimeout(() => { controls.autoRotate = prevAutoRotate; }, 300);
      }
    }
    requestAnimationFrame(tick);
  }

  setupRubberBandAndSnap._state = {
    setDragging: v => (dragging = v),
    settling: () => settling,
    handleAutoReverse,
    applyRubberBand,
    animateAzTo,
    eps,
    controls,
  };

  const dom = controls.domElement;
  const down = () => { setupRubberBandAndSnap._state.setDragging(true); azVel = 0; };
  const up = () => {
    setupRubberBandAndSnap._state.setDragging(false);
    const az = controls.getAzimuthalAngle();
    const minA = controls.minAzimuthAngle ?? -Infinity;
    const maxA = controls.maxAzimuthAngle ?? +Infinity;
    if (az < minA - eps) setupRubberBandAndSnap._state.animateAzTo(minA);
    else if (az > maxA + eps) setupRubberBandAndSnap._state.animateAzTo(maxA);
  };

  dom.addEventListener('pointerdown', down, { passive: true });
  dom.addEventListener('pointerup', up, { passive: true });
  dom.addEventListener('pointercancel', up, { passive: true });
  dom.addEventListener('pointerleave', up, { passive: true });

  setupRubberBandAndSnap._detach = () => {
    dom.removeEventListener('pointerdown', down);
    dom.removeEventListener('pointerup', up);
    dom.removeEventListener('pointercancel', up);
    dom.removeEventListener('pointerleave', up);
  };
}
setupRubberBandAndSnap._state = null;
setupRubberBandAndSnap._detach = null;

/* ======================= Render loop ======================= */
function renderLoop() {
  const st = setupRubberBandAndSnap._state;
  const loop = (now) => {
    animId = requestAnimationFrame(loop);
    if (st) {
      st.handleAutoReverse?.();
      st.applyRubberBand?.(now || performance.now());
    }
    controls?.update();
    renderer?.render(scene, camera);
  };
  loop();
}

/* ======================= Texture Canvas ======================= */
function buildCardCanvasTexture({ brand, name, id, angkatan, instansi }) {
  const W = 2048, H = 1280;
  const c = document.createElement('canvas');
  c.width = W; c.height = H;
  const ctx = c.getContext('2d');

  const emerald = '#0f2b27';
  const emeraldDeep = '#0b201d';
  const grd = ctx.createLinearGradient(0, 0, W, H);
  grd.addColorStop(0, emerald);
  grd.addColorStop(1, emeraldDeep);
  ctx.fillStyle = grd;
  ctx.fillRect(0, 0, W, H);

  const img = ctx.getImageData(0, 0, W, H);
  const density = 0.08;
  for (let i = 0; i < img.data.length; i += 4) {
    const n = (Math.random() - 0.5) * 255 * density;
    img.data[i]   = Math.max(0, Math.min(255, img.data[i]   + n));
    img.data[i+1] = Math.max(0, Math.min(255, img.data[i+1] + n));
    img.data[i+2] = Math.max(0, Math.min(255, img.data[i+2] + n));
  }
  ctx.putImageData(img, 0, 0);

  const gold = '#e7c865';
  const goldDim = '#caa94f';

  ctx.font = 'bold 96px system-ui, -apple-system, Segoe UI, Roboto, Arial';
  ctx.textAlign = 'right';
  ctx.textBaseline = 'top';
  ctx.fillStyle = gold;
  ctx.fillText(brand, W - 120, 90);

  ctx.textAlign = 'left';
  ctx.fillStyle = gold;
  ctx.font = '700 110px system-ui, -apple-system, Segoe UI, Roboto, Arial';
  ctx.fillText(String(name || '').toUpperCase(), 160, 470);

  ctx.strokeStyle = gold;
  ctx.lineWidth = 6;
  ctx.beginPath();
  ctx.moveTo(160, 560);
  ctx.lineTo(W - 160, 560);
  ctx.stroke();

  const drawPill = (x, y, w, h, text, align='left') => {
    const r = h/2;
    ctx.fillStyle = gold;
    ctx.beginPath();
    ctx.moveTo(x+r, y);
    ctx.arcTo(x+w, y,   x+w, y+h, r);
    ctx.arcTo(x+w, y+h, x,   y+h, r);
    ctx.arcTo(x,   y+h, x,   y,   r);
    ctx.arcTo(x,   y,   x+w, y,   r);
    ctx.closePath();
    ctx.fill();

    ctx.fillStyle = '#2b2b2b';
    const lines = String(text).split('\n');
    const lineH = lines.length > 1 ? 44 : 56;
    ctx.textAlign = align;
    ctx.textBaseline = 'middle';
    ctx.font = lines.length > 1
      ? '700 44px system-ui, -apple-system, Segoe UI, Roboto, Arial'
      : '700 56px system-ui, -apple-system, Segoe UI, Roboto, Arial';
    const tx = align==='left' ? x+36 : x+w-36;

    if (lines.length === 1) {
      ctx.fillText(lines[0], tx, y + h/2);
    } else {
      const totalHeight = lineH * lines.length;
      let startY = y + (h - totalHeight)/2 + lineH/2;
      lines.forEach(line => { ctx.fillText(line, tx, startY); startY += lineH; });
    }
  };

  drawPill(160, 620, 620, 120, `ID: ${id}`, 'left');
  drawPill(820, 620, 420, 120, `ANGKATAN:\n${angkatan}`, 'left');
  drawPill(160, 780, W-320, 120, `KAMPUS/SEKOLAH:\n${instansi}`, 'left');

  ctx.globalAlpha = 0.15;
  ctx.fillStyle = goldDim;
  ctx.fillRect(0, 0, W, 20);
  ctx.globalAlpha = 1;

  const tex = new THREE.CanvasTexture(c);
  tex.colorSpace = THREE.SRGBColorSpace;
  tex.anisotropy = 8;
  tex.flipY = false;
  tex.wrapS = THREE.ClampToEdgeWrapping;
  tex.wrapT = THREE.ClampToEdgeWrapping;
  tex.needsUpdate = true;
  return tex;
}
/* ======================= Export PNG (transparent, hi-res) ======================= */
function _slugifyNameForFile(s) {
  const base = String(s || 'membercard').trim();
  const slug = base
    .normalize('NFKD')                  // buang diakritik
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/\s+/g, '-')
    .replace(/[^A-Za-z0-9\-_]/g, '')
    .replace(/-+/g, '-');
  return slug || 'membercard';
}

function captureAndDownloadPNG({
  filename = 'membercard.png',
  scale = 3,            // 3x resolusi seperti permintaan
  transparent = true    // latar transparan
} = {}) {
  if (!renderer || !scene || !camera) return;

  // simpan state
  const prevAutoRotate = controls?.autoRotate;
  const prevBg = scene.background;
  const prevTone = renderer.toneMappingExposure;
  const prevPR = renderer.getPixelRatio();
  const hadGround = !!ground;

  const container = renderer.domElement.parentElement;
  const viewW = Math.max(1, container.clientWidth);
  const viewH = Math.max(1, container.clientHeight);

  try {
    if (controls) controls.autoRotate = false;

    // siapkan transparansi
    scene.background = null;         // hilangkan background scene
    renderer.setClearAlpha?.(0);     // pastikan alpha = 0
    if (hadGround) ground.visible = false; // sembunyikan plane bayangan saat export

    // render hi-res satu kali
    renderer.setPixelRatio(1);                 // kendalikan via setSize
    renderer.setSize(viewW * scale, viewH * scale, false);
    camera.aspect = (viewW * scale) / (viewH * scale);
    camera.updateProjectionMatrix();
    controls?.update();

    // exposure sedikit diturunkan agar tidak blow-out saat hi-res
    renderer.toneMappingExposure = prevTone;   // tetap seperti sebelumnya
    renderer.render(scene, camera);

    // ambil dataURL PNG dan unduh
    const dataURL = renderer.domElement.toDataURL('image/png');
    const a = document.createElement('a');
    a.href = dataURL;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();

  } finally {
    // pulihkan state
    if (hadGround) ground.visible = true;
    scene.background = prevBg;
    renderer.setClearAlpha?.(1);
    const w = Math.max(1, container.clientWidth);
    const h = Math.max(1, container.clientHeight);
    renderer.setSize(w, h, false);
    renderer.setPixelRatio(prevPR);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
    if (controls) controls.autoRotate = prevAutoRotate;
  }
}

/* ======================= Camera Preset: Default Elegan ======================= */
/** Animasi orbit ke sudut elegan (azimuth ≈ -30°, polar ≈ 60°) lalu resolve */
function goToDefaultElegantAngle(object3D, duration = 420) {
  if (!controls || !camera) return Promise.resolve();

  // Hitung target tengah objek + sedikit naik agar proporsional
  const box = new THREE.Box3().setFromObject(object3D || scene);
  const size = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());
  const lift = Math.max(0.08, size.y * 0.04);
  const target = center.clone().add(new THREE.Vector3(0, lift, 0));
  controls.target.copy(target);

  // Radius/jarak: pertahankan jarak sekarang (sudah “pas” dari fit)
  const radius = camera.position.distanceTo(target);

  // Sudut tujuan (OrbitControls: theta=azimuth, phi=polar [0=atas, π/2=horisontal])
  const azTarget  = THREE.MathUtils.degToRad(-30); // miring kanan
  const polTarget = THREE.MathUtils.degToRad(101);  // sedikit dari atas

  // Clamp ke batasan controls
  const minA = controls.minAzimuthAngle ?? -Infinity;
  const maxA = controls.maxAzimuthAngle ?? +Infinity;
  const minP = controls.minPolarAngle ?? 0;
  const maxP = controls.maxPolarAngle ?? Math.PI;
  const azTo = THREE.MathUtils.clamp(azTarget,  minA, maxA);
  const poTo = THREE.MathUtils.clamp(polTarget, minP, maxP);

  // Baca posisi saat ini dalam koordinat spherical relatif ke target
  const offset = camera.position.clone().sub(target);
  const sph = new THREE.Spherical().setFromVector3(offset);
  const azFrom = sph.theta;
  const poFrom = sph.phi;
  const rFrom  = sph.radius;

  function shortestDeltaAngle(current, target) {
    let d = target - current;
    while (d > Math.PI) d -= Math.PI * 2;
    while (d < -Math.PI) d += Math.PI * 2;
    return d;
  }
  const dAz = shortestDeltaAngle(azFrom, azTo);
  const dPo = poTo - poFrom;
  const dR  = radius - rFrom;

  function easeOutCubic(t){ return 1 - Math.pow(1 - t, 3); }

  const prevAuto = controls.autoRotate;
  controls.autoRotate = false;

  return new Promise(resolve => {
    const t0 = performance.now();
    (function anim(now){
      const t = Math.min(1, (now - t0) / duration);
      const k = easeOutCubic(t);
      const theta = azFrom + dAz * k;
      const phi   = poFrom + dPo * k;
      const r     = rFrom + dR  * k;

      const s = new THREE.Spherical(r, phi, theta);
      const pos = new THREE.Vector3().setFromSpherical(s).add(target);
      camera.position.copy(pos);
      camera.lookAt(target);
      controls.update();

      if (t < 1) requestAnimationFrame(anim);
      else {
        controls.autoRotate = prevAuto;
        resolve();
      }
    })(t0);
  });
}





/* ======================= Modal lifecycle ======================= */
(function bootstrapModal3D() {
  const modal = document.getElementById(MODAL_ID);
  if (!modal) return;

  let cleanup = null;
  const canvasWrap = document.getElementById(CANVAS_ID);
  const modelUrl = canvasWrap?.getAttribute('data-model-url');

  const obs = new MutationObserver(() => {
    const isOpen = !modal.classList.contains('hidden');
    if (isOpen && !renderer && canvasWrap && modelUrl) {
      cleanup = initViewer(canvasWrap, modelUrl);
    }
    if (!isOpen && renderer) {
      try { setupRubberBandAndSnap._detach?.(); } catch {}
      cleanup?.();
      cleanup = null;
    }
  });
  obs.observe(modal, { attributes: true, attributeFilter: ['class'] });

  setTimeout(() => {
    const isOpen = !modal.classList.contains('hidden');
    if (isOpen && !renderer && canvasWrap && modelUrl) {
      cleanup = initViewer(canvasWrap, modelUrl);
    }
  }, 0);
})();

