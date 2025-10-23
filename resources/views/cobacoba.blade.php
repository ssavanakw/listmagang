{{-- resources/views/3d/view-membercard.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Preview 3D - Membercard</title>
  <style>
    html,body { height:100%; margin:0; font-family: Inter, system-ui, sans-serif; background:#0b1020; color:#e6eef8; }
    .wrap { display:flex; flex-direction:column; height:100vh; }
    header { padding:12px 20px; background:linear-gradient(90deg, rgba(255,255,255,0.02), transparent); display:flex; align-items:center; justify-content:space-between; }
    h1 { margin:0; font-size:18px; font-weight:600; }
    #canvas-container { flex:1; position:relative; overflow:hidden; }
    #three-canvas { width:100%; height:100%; display:block; }
    .controls { position:absolute; right:12px; top:12px; background:rgba(0,0,0,0.45); padding:8px 10px; border-radius:8px; backdrop-filter:blur(4px); }
    .progress { position:absolute; left:12px; top:12px; background:rgba(255,255,255,0.06); padding:6px 10px; border-radius:8px; font-size:13px; }
    .foot { padding:10px 20px; font-size:13px; color:#9fb0d8; background:linear-gradient(0deg, rgba(255,255,255,0.01), transparent); }
    button { background:#1f6feb; color:white; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; }
    button:active { transform:translateY(1px); }
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <h1>Preview 3D — Membercard.fbx</h1>
      <div>
        <a href="{{ asset('storage/assets/3d/Membercard.fbx') }}" target="_blank" style="color:#9fb0d8; text-decoration:none; margin-right:12px;">Buka file FBX</a>
        <button id="resetBtn">Reset view</button>
      </div>
    </header>

    <div id="canvas-container">
      <div class="progress" id="progress">Mempersiapkan...</div>
      <div class="controls" id="controls" style="display:none;">
        <div style="margin-bottom:6px;"><strong>Controls</strong></div>
        <div style="display:flex; gap:8px;">
          <button id="toggleWire">Toggle Wireframe</button>
          <button id="fitView">Fit View</button>
        </div>
      </div>
      <canvas id="three-canvas"></canvas>
    </div>

    <div class="foot">
      Catatan: pastikan file FBX dapat diakses oleh web server (example: <code>public/storage/assets/3d/Membercard.fbx</code>).
      Jika belum terlihat, jalankan <code>php artisan storage:link</code> lalu taruh file di <code>storage/app/public/assets/3d/Membercard.fbx</code>.
    </div>
  </div>

  <!-- Three.js as ES module + loaders dari unpkg -->
  <script type="module">
    import * as THREE from 'https://unpkg.com/three@0.150.1/build/three.module.js';
    import { OrbitControls } from 'https://unpkg.com/three@0.150.1/examples/jsm/controls/OrbitControls.js';
    import { FBXLoader } from 'https://unpkg.com/three@0.150.1/examples/jsm/loaders/FBXLoader.js';
    // Optional: DRACOLoader if model compressed (not used here)
    // import { DRACOLoader } from 'https://unpkg.com/three@0.150.1/examples/jsm/loaders/DRACOLoader.js';

    const canvas = document.getElementById('three-canvas');
    const container = document.getElementById('canvas-container');
    const progressEl = document.getElementById('progress');
    const controlsBox = document.getElementById('controls');
    const resetBtn = document.getElementById('resetBtn');
    const toggleWireBtn = document.getElementById('toggleWire');
    const fitViewBtn = document.getElementById('fitView');

    // Renderer
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(container.clientWidth, container.clientHeight, false);
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    // Scene & camera
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0b1020);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.set(0, 1.2, 3);

    // Controls
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.08;
    controls.target.set(0, 0.6, 0);

    // Lights
    const hemi = new THREE.HemisphereLight(0xffffff, 0x444444, 0.6);
    scene.add(hemi);
    const dir = new THREE.DirectionalLight(0xffffff, 0.8);
    dir.position.set(3, 10, 10);
    dir.castShadow = true;
    scene.add(dir);

    // Ground grid (subtle)
    const grid = new THREE.GridHelper(10, 40, 0x1b2540, 0x0f1a2b);
    grid.material.opacity = 0.08;
    grid.material.transparent = true;
    scene.add(grid);

    // Load model
    const loader = new FBXLoader();
    // Path to FBX served via public storage url
    const modelUrl = "{{ asset('public\storage\assets\3d\Membercard.fbx') }}";

    let modelRoot = null;
    let originalMaterials = [];

    loader.load(
      modelUrl,
      (object) => {
        modelRoot = object;
        // optionally compute bounding box and scale to fit
        const box = new THREE.Box3().setFromObject(modelRoot);
        const size = new THREE.Vector3();
        box.getSize(size);
        const maxDim = Math.max(size.x, size.y, size.z);
        if (maxDim > 0) {
          const scale = 1.6 / maxDim; // scale to roughly fit view
          modelRoot.scale.setScalar(scale);
        }
        // center
        box.setFromObject(modelRoot);
        const center = new THREE.Vector3();
        box.getCenter(center);
        modelRoot.position.sub(center); // move to origin

        // store materials for wireframe toggle
        modelRoot.traverse((child) => {
          if (child.isMesh) {
            child.castShadow = true;
            child.receiveShadow = true;
            if (child.material) {
              originalMaterials.push(child.material);
            }
          }
        });

        scene.add(modelRoot);
        progressEl.textContent = 'Model dimuat.';
        controlsBox.style.display = 'block';
        // Fit view a bit
        fitModelToView();
      },
      (xhr) => {
        // progress in percent
        if (xhr.lengthComputable) {
          const percent = Math.round((xhr.loaded / xhr.total) * 100);
          progressEl.textContent = `Memuat model: ${percent}%`;
        } else {
          progressEl.textContent = `Memuat model...`;
        }
      },
      (err) => {
        console.error('FBX load error', err);
        progressEl.textContent = 'Gagal memuat model. Cek path atau izin file.';
      }
    );

    // Helpers
    function fitModelToView(padding = 1.2) {
      if (!modelRoot) return;
      const box = new THREE.Box3().setFromObject(modelRoot);
      const size = new THREE.Vector3();
      box.getSize(size);
      const center = new THREE.Vector3();
      box.getCenter(center);

      const maxSize = Math.max(size.x, size.y, size.z);
      const fitHeightDistance = maxSize / (2 * Math.atan(Math.PI * camera.fov / 360));
      const distance = fitHeightDistance * padding;

      const dir = new THREE.Vector3().subVectors(camera.position, controls.target).normalize();
      camera.position.copy(dir.multiplyScalar(distance).add(center));
      camera.near = Math.max(0.1, maxSize / 100);
      camera.far = Math.max(500, maxSize * 100);
      camera.updateProjectionMatrix();
      controls.target.copy(center);
      controls.update();
    }

    // Wireframe toggle (simple approach: toggle material.wireframe if standard material)
    let wireframe = false;
    toggleWireBtn.addEventListener('click', () => {
      wireframe = !wireframe;
      if (!modelRoot) return;
      modelRoot.traverse((child) => {
        if (child.isMesh && child.material) {
          if (Array.isArray(child.material)) {
            child.material.forEach(m => { if ('wireframe' in m) m.wireframe = wireframe; m.needsUpdate = true; });
          } else {
            if ('wireframe' in child.material) child.material.wireframe = wireframe;
            child.material.needsUpdate = true;
          }
        }
      });
    });

    fitViewBtn.addEventListener('click', () => fitModelToView(1.2));
    resetBtn.addEventListener('click', () => {
      // reset camera & controls
      camera.position.set(0, 1.2, 3);
      controls.target.set(0, 0.6, 0);
      controls.update();
    });

    // Resize
    window.addEventListener('resize', onWindowResize);
    function onWindowResize() {
      const w = container.clientWidth;
      const h = container.clientHeight;
      camera.aspect = w / h;
      camera.updateProjectionMatrix();
      renderer.setSize(w, h, false);
    }

    // Animation loop
    const clock = new THREE.Clock();
    function animate() {
      requestAnimationFrame(animate);
      const delta = clock.getDelta();
      controls.update();
      renderer.render(scene, camera);
    }
    animate();

    // Basic error hints if model not accessible
    (async () => {
      try {
        const res = await fetch(modelUrl, { method: 'HEAD' });
        if (!res.ok) {
          progressEl.textContent = 'File FBX tidak dapat diakses (HTTP ' + res.status + '). Periksa storage:link / permission.';
        }
      } catch (e) {
        // ignore fetch errors here (already handled in loader)
      }
    })();
  </script>
</body>
</html>
