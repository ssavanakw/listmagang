<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>3D Card Interaktif dengan Bevel</title>
  <style>
    body { margin: 0; overflow: hidden; background: #111; }
    canvas { display: block; }
  </style>
</head>
<body>
  <!-- Three.js -->
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/build/three.min.js"></script>
  <!-- OrbitControls -->
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

  <script>
    // ====== Scene & Camera ======
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 0, 5);

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    document.body.appendChild(renderer.domElement);

    // ====== Lighting ======
    const light = new THREE.PointLight(0xffffff, 1.2);
    light.position.set(5, 5, 5);
    scene.add(light);
    scene.add(new THREE.AmbientLight(0x404040, 1));

    // ====== Rounded Rectangle Path (bevel) ======
    function createRoundedRectShape(width, height, radius) {
      const shape = new THREE.Shape();
      const x = -width / 2;
      const y = -height / 2;
      shape.moveTo(x + radius, y);
      shape.lineTo(x + width - radius, y);
      shape.quadraticCurveTo(x + width, y, x + width, y + radius);
      shape.lineTo(x + width, y + height - radius);
      shape.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
      shape.lineTo(x + radius, y + height);
      shape.quadraticCurveTo(x, y + height, x, y + height - radius);
      shape.lineTo(x, y + radius);
      shape.quadraticCurveTo(x, y, x + radius, y);
      return shape;
    }

    const cardWidth = 3.5;
    const cardHeight = 2;
    const shape = createRoundedRectShape(cardWidth, cardHeight, 0.15);

    const extrudeSettings = {
      steps: 1,
      depth: 0.15,
      bevelEnabled: true,
      bevelThickness: 0.01,
      bevelSize: 0.04,
      bevelSegments: 20
    };

    const geometry = new THREE.ExtrudeGeometry(shape, extrudeSettings);

    // ====== Canvas Texture ======
    const canvas = document.createElement('canvas');
    canvas.width = 1024 * 5;
    canvas.height = 512 * 5;
    const ctx = canvas.getContext('2d');

    function drawTextCard() {
      // ===== Background =====
      ctx.fillStyle = '#0d2721';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // ===== Logo kanan atas =====
      ctx.fillStyle = '#ffff00';
      ctx.font = 'bold 400px Arial';
      ctx.textAlign = 'right';
      ctx.fillText('magangjogja.com', canvas.width - 60, 500);

      // ===== Nama di tengah + garis bawah =====
      const nama = 'SURYA PRATAMA';
      ctx.fillStyle = '#ffff00';
      ctx.font = 'bold 70px Poppins, sans-serif';
      ctx.textAlign = 'center';
      ctx.fillText(nama, canvas.width / 2, 250);

      // Garis bawah otomatis sesuai panjang teks
      const textWidth = ctx.measureText(nama).width / 2;
      ctx.strokeStyle = '#ffff00';
      ctx.lineWidth = 3;
      ctx.beginPath();
      ctx.moveTo(canvas.width / 2 - textWidth - 30, 270);
      ctx.lineTo(canvas.width / 2 + textWidth + 30, 270);
      ctx.stroke();

      // ===== ID dan Angkatan =====
      ctx.fillStyle = '#ffff00';
      ctx.font = '40px Arial';
      ctx.textAlign = 'center';
      ctx.fillText('ID: 230045  •  ANGKATAN: 2025', canvas.width / 2, 340);

      // ===== Kampus/Asal Sekolah =====
      ctx.font = '36px Arial';
      ctx.fillText('POLITEKNIK NEGERI SEMARANG', canvas.width / 2, 400);
    }

    drawTextCard();

    const texture = new THREE.CanvasTexture(canvas);
    const frontMaterial = new THREE.MeshStandardMaterial({ map: texture });

    // ====== Card Material ======
    const sideMaterial = new THREE.MeshStandardMaterial({ color: 0xaaaaaa });
    const materials = [frontMaterial, sideMaterial];

    const card = new THREE.Mesh(geometry, materials);
    card.rotation.y = Math.PI; // arah depan
    scene.add(card);

    // ====== Controls ======
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.enablePan = false;
    controls.minDistance = 3;
    controls.maxDistance = 8;

    // ====== Hover Interaksi ======
    document.addEventListener("mousemove", (e) => {
      const x = (e.clientX / window.innerWidth) * 2 - 1;
      const y = -(e.clientY / window.innerHeight) * 2 + 1;
      card.rotation.x = y * 0.3;
      card.rotation.y = x * 0.3;
    });

    // ====== Animasi ======
    function animate() {
      requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    }
    animate();

    // ====== Resize ======
    window.addEventListener("resize", () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
  </script>
</body>
</html>
