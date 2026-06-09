 <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nooise — Pembayaran</title>
  <link rel="stylesheet" href="{{asset('cssnooise/stylepay.css') }}" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <!-- Leaflet Map Assets -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <style>
    /* Map Modal Styles */
    .map-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(14, 8, 5, 0.7);
      backdrop-filter: blur(10px);
      z-index: 2000;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    .map-modal-overlay.show {
      opacity: 1;
      pointer-events: auto;
    }
    .map-modal-box {
      width: 90%;
      max-width: 650px;
      background: #faf6f0;
      border: 1.5px solid rgba(90, 58, 40, 0.15);
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 20px 40px rgba(90,58,40,0.15);
      transform: translateY(20px);
      transition: transform 0.3s ease;
      font-family: 'DM Sans', sans-serif;
      color: #3e2723;
    }
    .map-modal-overlay.show .map-modal-box {
      transform: translateY(0);
    }
    .map-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }
    .map-modal-title {
      font-family: 'DM Serif Display', serif;
      font-size: 20px;
      color: #5a3a28;
      margin: 0;
    }
    .map-modal-close {
      background: none;
      border: none;
      font-size: 28px;
      color: #a58b77;
      cursor: pointer;
      line-height: 1;
      transition: color 0.2s;
    }
    .map-modal-close:hover {
      color: #5a3a28;
    }
    .map-modal-search {
      display: flex;
      gap: 10px;
      margin-bottom: 16px;
    }
    .map-modal-search input {
      flex: 1;
      padding: 10px 14px;
      border-radius: 8px;
      border: 1px solid rgba(90, 58, 40, 0.2);
      background: #fff;
      font-size: 14px;
      outline: none;
      color: #3e2723;
    }
    .map-modal-search button {
      padding: 10px 20px;
      background: #b48264;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.2s ease;
    }
    .map-modal-search button:hover {
      background: #5a3a28;
    }
    .map-container-wrap {
      position: relative;
      height: 300px;
      border-radius: 12px;
      overflow: hidden;
      border: 1.5px solid rgba(90, 58, 40, 0.15);
      margin-bottom: 16px;
    }
    #map {
      width: 100%;
      height: 100%;
      z-index: 1;
    }
    .map-modal-info {
      background: rgba(90, 58, 40, 0.04);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
      border: 1px solid rgba(90, 58, 40, 0.08);
    }
    .info-row {
      display: flex;
      gap: 20px;
      margin-bottom: 12px;
    }
    .info-col {
      flex: 1;
    }
    .info-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #a58b77;
      display: block;
      margin-bottom: 4px;
    }
    .info-col strong {
      font-size: 14px;
      color: #5a3a28;
    }
    .info-address-row p {
      font-size: 13px;
      color: #3e2723;
      margin: 4px 0 0 0;
      line-height: 1.4;
    }
    .map-confirm-btn {
      width: 100%;
      padding: 14px;
      background: #5a3a28;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s ease;
    }
    .map-confirm-btn:hover {
      background: #b48264;
    }
  </style>
</head>
<body>

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="topbar-left">
      <span>@makenoooise_</span>
      <span>+62 333 888 777</span>
    </div>
    <div class="topbar-right">
      <span>www.nooisealbummusic.com.id</span>
    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-left">
      <!-- <div class="logo">nooise</div> -->
      <a href="{{route('halaman.home')}}" class="logo">no<span>∞</span>ise</a>
      <button class="cart-btn" id="cartBtn">
        <!-- <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <span class="cart-badge">1</span> -->
      </button>
    </div>
    <div class="nav-right">
      <!-- <button class="nav-btn" id="masukBtn">Masuk</button>
      <button class="nav-btn nav-btn-primary" id="daftarBtn">Daftar</button> -->
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <main class="main-wrapper">
    <div class="content-grid">

      <!-- LEFT PANEL -->
      <div class="left-panel">

        <!-- Search + Title Row -->
        <div class="search-title-row">
          <!-- <div class="search-box">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Search.." id="searchInput"/>
          </div> -->
          <h2 class="section-title">Pembayaran</h2>
          <!-- <button class="back-btn" id="backBtn" title="Kembali">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/></svg>
          </button> -->
        </div>

        <!-- Select All -->
        <div class="select-all-row">
          <span>Total Semua Product &nbsp;<strong id="cartCountText">({{ $carts->sum('quantity') }})</strong></span>
        </div>

        <!-- Dynamic Album Cards -->
        <div id="cartItemsContainer" style="display: flex; flex-direction: column; gap: 14px;">
          @if($carts->isEmpty())
            <p style="color:#a58b77;margin:16px 0;text-align:center;font-family:'DM Sans',sans-serif;">Keranjang Anda kosong.</p>
          @else
            @foreach($carts as $cart)
              <a href="{{ route('halaman.deskripsi', ['id' => $cart->product->id]) }}" class="cart-item-link" style="text-decoration: none; color: inherit; display: block;">
                <div class="album-card">
                  <div class="album-img-wrap">
                    <img src="{{ asset($cart->product->image) }}" alt="{{ $cart->product->name }}" class="album-img" onerror="this.style.background='#6b4c3b';"/>
                  </div>
                  <div class="album-info">
                    <div class="album-name">{{ $cart->product->name }}</div>
                    <div class="album-artist">{{ $cart->product->brand }}</div>
                    <div class="album-price">Rp {{ number_format($cart->product->price, 0, ',', '.') }} x {{ $cart->quantity }}</div>
                  </div>
                </div>
              </a>
            @endforeach
          @endif
        </div>

        <!-- Shipping Address -->
        <div class="section-block">
          <h3 class="block-title">Alamat Pengiriman</h3>
          <div class="address-input-row">
            <div class="address-input-wrap">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pin-icon"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <input type="text" placeholder="Pilih Lokasi Anda..." class="address-input" id="addressInput" readonly style="cursor: pointer;" title="Klik tombol Pilih untuk membuka Peta"/>
              <input type="hidden" id="latitudeInput" name="latitude" />
              <input type="hidden" id="longitudeInput" name="longitude" />
            </div>
            <button class="pilih-btn" id="pilihBtn">Pilih</button>
          </div>
        </div>

        <!-- Email Input -->
        <div class="section-block">
          <h3 class="block-title">Masukkan Email</h3>
          <div class="address-input-row" style="padding-right: 12px;">
            <div class="address-input-wrap">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-light)" stroke-width="2" class="pin-icon"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" placeholder="Masukkan Email Anda..." class="address-input" id="emailInput" value="{{ session('user_email') ?? '' }}"/>
            </div>
          </div>
        </div>

        <!-- Transaction Protection -->
        <div class="section-block">
          <h3 class="block-title protection-title">Perlindungan transaksi</h3>
          <div class="protection-row" id="protectionRow">
            <button type="button" class="checkbox-btn active" id="protectionBtn" aria-label="Aktifkan Layanan Premium">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </button>
            <div class="protection-info">
              <div class="protection-name">Aktifkan Layanan Premium</div>
              <div class="protection-price">Rp 5.000</div>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT PANEL -->
      <div class="right-panel">
        <div class="detail-card">
          <h3 class="detail-title">Detail Pembayaran</h3>

          <div class="detail-rows">
            <div class="detail-row">
              <span class="detail-label">Email Pembeli</span>
              <span class="detail-value" id="detailEmailValue">{{ session('user_email') ?? 'guest' }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Total Pesanan</span>
              <span class="detail-value">Rp 690.000</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Perlindungan transaksi</span>
              <span class="detail-value">Rp 5.000</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Biaya admin</span>
              <span class="detail-value">Rp 181</span>
            </div>
            <div class="detail-row total-row">
              <span class="detail-label total-label">Total Pembayaran</span>
              <span class="detail-value total-value">Rp 695.181</span>
            </div>
          </div>

          <button class="bayar-btn" id="bayarBtn">Bayar</button>
        </div>
      </div>

    </div>

    <!-- UNTUK ANDA SECTION -->
    <section class="untuk-anda">
      <h2 class="untuk-title">Untuk Anda</h2>
      <div class="recommendations-grid">
        @php
          $recommendations = \App\Models\Product::whereIn('sku', [
              'SKU-NEVER-ENOUGH-DC',
              'SKU-MORE-LIFE-DR',
              'SKU-CASE-STUDY-01-DC',
              'SKU-SOS-SZA',
              'SKU-GKMC'
          ])->get();
        @endphp

        @foreach($recommendations as $rec)
          <a href="{{ route('halaman.deskripsi', ['id' => $rec->id]) }}" class="rec-card-link" style="text-decoration: none; color: inherit; display: block;">
            <div class="rec-card" data-name="{{ $rec->name }}" data-artist="{{ $rec->brand }}" data-price="{{ $rec->price }}">
              <div class="rec-img-wrap">
                <img src="{{ asset($rec->image) }}" alt="{{ $rec->name }}" onerror="this.style.display='none'"/>
                <div class="rec-img-placeholder" style="background: linear-gradient(135deg, #5a3a28, #7a4f38)"></div>
              </div>
              <div class="rec-info">
                <div class="rec-name">{{ $rec->name }}</div>
                <div class="rec-artist">{{ $rec->brand }}</div>
                <div class="rec-price">Rp {{ number_format($rec->price, 0, ',', '.') }}</div>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </section>
  </main>

  <!-- TOAST NOTIFICATION -->
  <div class="toast" id="toast"></div>

  <!-- PAYMENT SUCCESS MODAL -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal-box" id="modalBox">
      <div class="modal-icon">✓</div>
      <h3 class="modal-title">Pembayaran Berhasil!</h3>
      <p class="modal-desc">Terima kasih! Pesanan kamu sedang diproses.</p>
      <div class="modal-detail">
        <span>Total Dibayar</span>
        <strong>Rp 695.181</strong>
      </div>
      <button class="modal-close-btn" id="modalCloseBtn">Kembali ke Toko</button>
    </div>
  </div>

  @php
    $paymentItems = $carts->map(function($cart) {
        return [
            'id' => $cart->product->sku,
            'title' => $cart->product->name,
            'price' => $cart->product->price,
            'qty' => $cart->quantity
        ];
    });
  @endphp

  <!-- Leaflet Map JS Library -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <!-- Midtrans Snap JS -->
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

  <script>
    (function () {
      const USER_EMAIL = @json(session('user_email'));

      function parseCart() {
        return @json($paymentItems);
      }

      function formatRupiah(n) {
        const num = Number(n) || 0;
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }

      function renderPaymentFromCart() {
        const items = parseCart();
        const selectCountEl = document.querySelector('.select-count');
        
        const detailValues = document.querySelectorAll('.detail-row .detail-value');
        const totalPesanan = detailValues && detailValues[0] ? detailValues[0] : null;
        const protectionDetailValue = detailValues && detailValues[1] ? detailValues[1] : null;

        const feeAdmin = 181;
        
        let isPremiumActive = true;
        const protectionBtn = document.getElementById('protectionBtn');

        // update select all count
        if (selectCountEl) {
          const count = items.reduce((sum, it) => sum + (Number(it.qty) || 1), 0);
          selectCountEl.textContent = '(' + count + ')';
        }

        function updateTotal() {
          const totalCart = items.reduce((sum, it) => sum + (Number(it.price) || 0) * (Number(it.qty) || 1), 0);
          const protectionVal = isPremiumActive ? 5000 : 0;
          const totalBayar = totalCart + protectionVal + feeAdmin;

          if (totalPesanan) totalPesanan.textContent = formatRupiah(totalCart);
          if (protectionDetailValue) protectionDetailValue.textContent = formatRupiah(protectionVal);

          const totalValueEl = document.querySelector('.total-row .total-value');
          if (totalValueEl) totalValueEl.textContent = formatRupiah(totalBayar);

          return totalBayar;
        }

        // Initial total render
        updateTotal();

        const protectionRow = document.getElementById('protectionRow');
        if (protectionRow && protectionBtn) {
          protectionRow.addEventListener('click', function() {
            isPremiumActive = !isPremiumActive;
            if (isPremiumActive) {
              protectionBtn.classList.add('active');
            } else {
              protectionBtn.classList.remove('active');
            }
            updateTotal();
          });
        }

        // Modal elements
        const bayarBtn = document.getElementById('bayarBtn');
        const modalOverlay = document.getElementById('modalOverlay');
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        const modalDetailStrong = modalOverlay ? modalOverlay.querySelector('.modal-detail strong') : null;

        const emailInput = document.getElementById('emailInput');
        const detailEmailValue = document.getElementById('detailEmailValue');
        if (emailInput && detailEmailValue) {
          emailInput.addEventListener('input', function() {
            detailEmailValue.textContent = emailInput.value || 'guest';
          });
        }

        if (bayarBtn && modalOverlay) {
          bayarBtn.addEventListener('click', function () {
            const emailInput = document.getElementById('emailInput');
            const addressInput = document.getElementById('addressInput');
            const latitudeInput = document.getElementById('latitudeInput');
            const longitudeInput = document.getElementById('longitudeInput');

            if (emailInput && !emailInput.value.trim()) {
              alert('Harap masukkan alamat email Anda terlebih dahulu.');
              emailInput.focus();
              return;
            }
            if (addressInput && !addressInput.value.trim()) {
              alert('Harap pilih alamat pengiriman Anda terlebih dahulu menggunakan peta.');
              return;
            }

            const currentTotal = updateTotal();

            // Tampilkan loading state pada tombol
            const originalBtnText = bayarBtn.textContent;
            bayarBtn.disabled = true;
            bayarBtn.textContent = 'Memproses...';

            // Kirim request ke backend untuk membuat order dan token Snap
            fetch('/pay/checkout', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({
                email: emailInput.value.trim(),
                address: addressInput.value.trim(),
                latitude: latitudeInput ? latitudeInput.value : '',
                longitude: longitudeInput ? longitudeInput.value : '',
                premium_protection: isPremiumActive
              })
            })
            .then(res => res.json())
            .then(data => {
              bayarBtn.disabled = false;
              bayarBtn.textContent = originalBtnText;

              if (data.success) {
                // Panggil Midtrans Snap
                window.snap.pay(data.snap_token, {
                  onSuccess: function(result) {
                    // Konfirmasi sukses ke server agar cart dikosongkan
                    fetch('/pay/success', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({
                        order_id: data.order_id
                      })
                    })
                    .then(res => res.json())
                    .then(successData => {
                      if (modalDetailStrong) {
                        modalDetailStrong.textContent = formatRupiah(currentTotal);
                      }
                      modalOverlay.classList.add('show');
                    });
                  },
                  onPending: function(result) {
                    alert('Pembayaran tertunda. Harap selesaikan pembayaran Anda.');
                  },
                  onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                  },
                  onClose: function() {
                    alert('Anda menutup popup pembayaran sebelum menyelesaikan transaksi.');
                  }
                });
              } else {
                alert(data.message || 'Gagal memproses pembayaran.');
              }
            })
            .catch(err => {
              bayarBtn.disabled = false;
              bayarBtn.textContent = originalBtnText;
              alert('Terjadi kesalahan koneksi. Silakan coba kembali.');
              console.error(err);
            });
          });
        }

        if (modalCloseBtn && modalOverlay) {
          modalCloseBtn.addEventListener('click', function () {
            modalOverlay.classList.remove('show');
            // Redirect back to home
            window.location.href = '{{ route('halaman.home') }}';
          });
        }

        // ==========================================
        // LEAFLET MAP PICKER LOGIC
        // ==========================================
        const pilihBtn = document.getElementById('pilihBtn');
        const addressInput = document.getElementById('addressInput');
        const mapModalOverlay = document.getElementById('mapModalOverlay');
        const mapModalClose = document.getElementById('mapModalClose');
        const mapConfirmBtn = document.getElementById('mapConfirmBtn');

        let mapInstance = null;
        let markerInstance = null;

        function updateCoordinates(lat, lng) {
          const infoLat = document.getElementById('infoLat');
          const infoLng = document.getElementById('infoLng');
          const infoAddress = document.getElementById('infoAddress');

          if (infoLat) infoLat.textContent = Number(lat).toFixed(6);
          if (infoLng) infoLng.textContent = Number(lng).toFixed(6);
          if (infoAddress) infoAddress.textContent = 'Mencari alamat terpilih...';

          fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
              if (infoAddress) {
                infoAddress.textContent = data.display_name || `Koordinat: ${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
              }
            })
            .catch(err => {
              if (infoAddress) {
                infoAddress.textContent = `Koordinat: ${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
              }
            });
        }

        if (pilihBtn && mapModalOverlay) {
          const openMapModal = function(e) {
            e.preventDefault();
            mapModalOverlay.classList.add('show');

            setTimeout(function() {
              if (!mapInstance) {
                // Default to Jakarta
                const defaultLat = -6.2088;
                const defaultLng = 106.8456;

                mapInstance = L.map('map').setView([defaultLat, defaultLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapInstance);

                markerInstance = L.marker([defaultLat, defaultLng], {
                  draggable: true
                }).addTo(mapInstance);

                markerInstance.on('dragend', function(event) {
                  const position = markerInstance.getLatLng();
                  updateCoordinates(position.lat, position.lng);
                });

                mapInstance.on('click', function(event) {
                  markerInstance.setLatLng(event.latlng);
                  updateCoordinates(event.latlng.lat, event.latlng.lng);
                });

                updateCoordinates(defaultLat, defaultLng);
              } else {
                mapInstance.invalidateSize();
              }
            }, 300);
          };

          pilihBtn.addEventListener('click', openMapModal);
          if (addressInput) {
            addressInput.addEventListener('click', openMapModal);
          }
        }

        if (mapModalClose && mapModalOverlay) {
          mapModalClose.addEventListener('click', function() {
            mapModalOverlay.classList.remove('show');
          });
        }

        // Search geocoding
        const mapSearchInput = document.getElementById('mapSearchInput');
        const mapSearchBtn = document.getElementById('mapSearchBtn');
        if (mapSearchBtn && mapSearchInput) {
          mapSearchBtn.addEventListener('click', function() {
            const query = mapSearchInput.value.trim();
            if (!query) return;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
              .then(res => res.json())
              .then(data => {
                if (data && data.length > 0) {
                  const lat = parseFloat(data[0].lat);
                  const lon = parseFloat(data[0].lon);

                  mapInstance.setView([lat, lon], 15);
                  markerInstance.setLatLng([lat, lon]);
                  updateCoordinates(lat, lon);
                } else {
                  alert('Lokasi tidak ditemukan.');
                }
              })
              .catch(err => {
                alert('Terjadi kesalahan saat mencari lokasi.');
              });
          });

          mapSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
              mapSearchBtn.click();
            }
          });
        }

        if (mapConfirmBtn && mapModalOverlay) {
          mapConfirmBtn.addEventListener('click', function() {
            const lat = document.getElementById('infoLat').textContent;
            const lng = document.getElementById('infoLng').textContent;
            const address = document.getElementById('infoAddress').textContent;

            const latInput = document.getElementById('latitudeInput');
            const lngInput = document.getElementById('longitudeInput');

            if (addressInput) {
              addressInput.value = address;
            }
            if (latInput) latInput.value = lat;
            if (lngInput) lngInput.value = lng;

            mapModalOverlay.classList.remove('show');
          });
        }
      }

      document.addEventListener('DOMContentLoaded', renderPaymentFromCart);
    })();
  </script>

  <!-- MAP PICKER MODAL CONTAINER -->
  <div class="map-modal-overlay" id="mapModalOverlay">
    <div class="map-modal-box">
      <div class="map-modal-header">
        <h3 class="map-modal-title">Pilih Lokasi Pengiriman</h3>
        <button class="map-modal-close" id="mapModalClose">&times;</button>
      </div>
      <div class="map-modal-search">
        <input type="text" id="mapSearchInput" placeholder="Cari alamat, jalan, kota atau gedung..."/>
        <button id="mapSearchBtn">Cari</button>
      </div>
      <div class="map-container-wrap">
        <div id="map"></div>
      </div>
      <div class="map-modal-info">
        <div class="info-row">
          <div class="info-col">
            <span class="info-label">Latitude</span>
            <strong id="infoLat">-</strong>
          </div>
          <div class="info-col">
            <span class="info-label">Longitude</span>
            <strong id="infoLng">-</strong>
          </div>
        </div>
        <div class="info-address-row">
          <span class="info-label">Alamat Terpilih</span>
          <p id="infoAddress">Klik pada peta atau geser penanda untuk mendapatkan alamat...</p>
        </div>
      </div>
      <button class="map-confirm-btn" id="mapConfirmBtn">Konfirmasi Lokasi</button>
    </div>
  </div>
</body>
</html>
