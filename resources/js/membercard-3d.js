// resources/js/membercard-3d.js
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

const MODAL_ID = 'modalMembercard3d';
const CANVAS_ID = 'membercard3dCanvas';

let renderer, scene, camera, controls, animId;
let currentModel = null;

function disposeViewer() {
  cancelAnimationFrame(animId);
  if (controls) controls.dispose();
  if (renderer) {
    renderer.dispose();
    renderer.forceContextLoss?.();
    renderer.domElement?.remove();
  }
  if (scene) {
    scene.traverse(obj => {
      if (obj.isMesh) {
        obj.geometry?.dispose?.();
        const mats = Array.isArray(obj.material) ? obj.material : [obj.material];
        mats.forEach(m => {
          if (!m) return;
          ['map','normalMap','roughnessMap','metalnessMap','emissiveMap','aoMap'].forEach(k=>{
            m[k]?.dispose?.();
          });
          m.dispose?.();
        });
      }
    });
  }
  renderer = scene = camera = controls = currentModel = null;
}

function fitCameraToObject(cam, object, controls, offset = 1.35) {
  const box = new THREE.Box3().setFromObject(object);
  const size = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());

  const maxDim = Math.max(size.x, size.y, size.z);
  const fov = cam.fov * (Math.PI / 180);
  let cameraZ = Math.abs(maxDim / (2 * Math.tan(fov / 2)));
  cameraZ *= offset;

  cam.position.set(center.x + cameraZ, center.y + cameraZ * 0.5, center.z + cameraZ);
  cam.near = cameraZ / 100;
  cam.far = cameraZ * 100;
  cam.updateProjectionMatrix();

  controls.target.copy(center);
  controls.update();
}

function initViewer(container, modelUrl) {
  // renderer
  renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(container.clientWidth, container.clientHeight);
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.1;
  container.innerHTML = '';
  container.appendChild(renderer.domElement);

  // scene
  scene = new THREE.Scene();
  scene.background = new THREE.Color(0x0a0f18);

  // camera
  camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.01, 2000);
  camera.position.set(0.6, 0.35, 1.0);

  // lights (elegan)
  const hemi = new THREE.HemisphereLight(0xffffff, 0x101010, 0.8);
  scene.add(hemi);

  const dir1 = new THREE.DirectionalLight(0xffffff, 1.2);
  dir1.position.set(3, 4, 5);
  scene.add(dir1);

  const dir2 = new THREE.DirectionalLight(0x88ccff, 0.4);
  dir2.position.set(-4, 2, -3);
  scene.add(dir2);

  // controls
  controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.06;
  controls.minDistance = 0.1;
  controls.maxDistance = 10;
  controls.rotateSpeed = 0.6;
  controls.zoomSpeed = 0.8;
  controls.panSpeed = 0.6;

  // loader
  const manager = new THREE.LoadingManager();
  const loader = new GLTFLoader(manager);

  manager.onStart = () => container.setAttribute('data-loading', 'true');
  manager.onLoad  = () => container.removeAttribute('data-loading');

  loader.load(
    modelUrl,
    (gltf) => {
      currentModel = gltf.scene;
      scene.add(currentModel);

      currentModel.rotation.x = Math.PI / 2; 
      currentModel.rotation.z = Math.PI;

      // ---- ambil data dari elemen canvas
      const d = container.dataset || {};
      const cardData = {
        brand:    d.brand    || 'magangjogja.com',
        name:     d.name     || 'MUHAMMAD ZAKI AUZAN',
        id:       d.id       || 'MJ25067',
        angkatan: d.angkatan || '2025',
        instansi: d.instansi || 'UNIVERSITAS AHMAD DAHLAN',
      };

      // ---- buat texture kanvas (emerald + gold + badge)
      const tex = buildCardCanvasTexture(cardData);

      // ---- pilih mesh "depan" kartu
      let frontMesh = null;
      let backMesh = null;
      let maxArea = -1;
      currentModel.traverse((o) => {
        if (!o.isMesh) return;
        o.castShadow = false;
        o.receiveShadow = true;

        // pilih mesh depan dan belakang (misal menggunakan nama mesh)
      const name = (o.name || '').toLowerCase();
      if (name.includes('front') || name.includes('face')) frontMesh = o;
      if (name.includes('back') || name.includes('reverse')) backMesh = o;

        // kalau tidak ada nama, ambil yang permukaannya paling luas
        if (maxArea !== Infinity) {
          const box = new THREE.Box3().setFromObject(o);
          const size = box.getSize(new THREE.Vector3());
          const area = size.x * size.y;
          if (area > maxArea) { maxArea = area; frontMesh = o; }
        }
      });

      if (frontMesh) {
        const mat = new THREE.MeshStandardMaterial({
          map: tex,
          metalness: 0.15,
          roughness: 0.35,
          emissive: new THREE.Color(0x0f2b27),
          envMapIntensity: 1.0
        });
        mat.map.anisotropy = 8;
        frontMesh.material = mat;
      }

      // --- material untuk belakang (gelap dengan efek metalic)
      if (backMesh) {
       const matBack = new THREE.MeshStandardMaterial({
          color: new THREE.Color('#0b1216'), // dark
          metalness: 0.25,
          roughness: 0.5,
          envMapIntensity: 0.8
        });
        backMesh.material = matBack;
      }

      // sisi lain: gelap elegan
      currentModel.traverse((o) => {
      if (o.isMesh && o !== frontMesh && o !== backMesh) {
        o.material = new THREE.MeshStandardMaterial({
          color: new THREE.Color(0xC9A23E), // metallic gold
          metalness: 1,
          roughness: 0.25,
          emissive: new THREE.Color(0xC9A23E), // soft glow
          envMapIntensity: 1.0
          });
        }
      });

      fitCameraToObject(camera, currentModel, controls, 1.5);
    },
    undefined,
    (err) => {
      container.innerHTML = `<div class="w-full h-full grid place-items-center text-white/70 text-xs p-4">
        Gagal memuat model. Pastikan file .glb tersedia & storage link aktif.<br>${err?.message || ''}
      </div>`;
    }
  );

  // resize
  const onResize = () => {
    if (!renderer || !camera) return;
    const { clientWidth: w, clientHeight: h } = container;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
  };
  window.addEventListener('resize', onResize);

  // render loop
  const render = () => {
    animId = requestAnimationFrame(render);
    controls?.update();
    renderer?.render(scene, camera);
  };
  render();

  // cleanup hook
  const cleanup = () => {
    window.removeEventListener('resize', onResize);
    disposeViewer();
  };
  return cleanup;
}

/* ======================= Texture Canvas ======================= */
function buildCardCanvasTexture({ brand, name, id, angkatan, instansi }) {
  const W = 2048, H = 1280;
  const c = document.createElement('canvas');
  c.width = W; c.height = H;
  const ctx = c.getContext('2d');

  // background emerald + subtle noise
  const emerald = '#0f2b27';
  const emeraldDeep = '#0b201d';
  const grd = ctx.createLinearGradient(0, 0, W, H);
  grd.addColorStop(0, emerald);
  grd.addColorStop(1, emeraldDeep);
  ctx.fillStyle = grd;
  ctx.fillRect(0, 0, W, H);

  // noise
  const img = ctx.getImageData(0, 0, W, H);
  const density = 0.08;
  for (let i = 0; i < img.data.length; i += 4) {
    const n = (Math.random() - 0.5) * 255 * density;
    img.data[i]   = Math.max(0, Math.min(255, img.data[i]   + n));
    img.data[i+1] = Math.max(0, Math.min(255, img.data[i+1] + n));
    img.data[i+2] = Math.max(0, Math.min(255, img.data[i+2] + n));
  }
  ctx.putImageData(img, 0, 0);

  // gold palette
  const gold = '#e7c865';
  const goldDim = '#caa94f';

  // brand (top-right)
  ctx.font = 'bold 96px system-ui, -apple-system, Segoe UI, Roboto, Arial';
  ctx.textAlign = 'right';
  ctx.textBaseline = 'top';
  ctx.fillStyle = gold;
  ctx.fillText(brand, W - 120, 90);

  // name + line
  ctx.textAlign = 'left';
  ctx.fillStyle = gold;
  ctx.font = '700 110px system-ui, -apple-system, Segoe UI, Roboto, Arial';
  ctx.fillText(String(name || '').toUpperCase(), 160, 470);

  ctx.strokeStyle = gold;
  ctx.lineWidth = 6;
  ctx.beginPath();
  ctx.moveTo(160, 520);
  ctx.lineTo(W - 160, 520);
  ctx.stroke();

  // pill helper (support multiline with \n)
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
      lines.forEach(line => {
        ctx.fillText(line, tx, startY);
        startY += lineH;
      });
    }
  };

  drawPill(160, 620, 620, 120, `ID: ${id}`, 'left');
  drawPill(820, 620, 420, 120, `ANGKATAN:\n${angkatan}`, 'left');
  drawPill(160, 780, W-320, 120, `KAMPUS/SEKOLAH:\n${instansi}`, 'left');

  // tiny gold overlay
  ctx.globalAlpha = 0.15;
  ctx.fillStyle = goldDim;
  ctx.fillRect(0, 0, W, 20);
  ctx.globalAlpha = 1;

  const tex = new THREE.CanvasTexture(c);
  tex.colorSpace = THREE.SRGBColorSpace;
  tex.anisotropy = 8;
  tex.needsUpdate = true;
  return tex;
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
      cleanup?.();
      cleanup = null;
    }
  });
  obs.observe(modal, { attributes: true, attributeFilter: ['class'] });

  // safety for initial open
  setTimeout(() => {
    const isOpen = !modal.classList.contains('hidden');
    if (isOpen && !renderer && canvasWrap && modelUrl) {
      cleanup = initViewer(canvasWrap, modelUrl);
    }
  }, 0);
})();
