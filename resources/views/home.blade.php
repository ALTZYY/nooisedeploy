<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nooise - Album Music Store</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{asset('cssnooise/stylehome.css') }}"/>
  <!-- Swiper.js CSS CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  
  <!-- Tailwind CSS CDN with Preflight disabled to protect existing layout styles -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      corePlugins: {
        preflight: false,
      }
    }
  </script>
  <!-- Alpine.js CDN -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>
<body x-data="{ showModal: false, showResetForm: false }" x-effect="if (!showModal) showResetForm = false">

@if(session('success'))
  <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[10000] bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transition-all duration-300 font-sans" id="successAlert">
    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span class="text-sm font-semibold">{{ session('success') }}</span>
    <button onclick="document.getElementById('successAlert').remove()" class="text-[#10b981] hover:text-[#047857] font-bold border-0 bg-transparent cursor-pointer text-base">&times;</button>
  </div>
@endif

@if(session('error') || $errors->any())
  <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[10000] bg-rose-50 border border-rose-200 text-rose-800 px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transition-all duration-300 font-sans" id="errorAlert">
    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span class="text-sm font-semibold">{{ session('error') ?? $errors->first() }}</span>
    <button onclick="document.getElementById('errorAlert').remove()" class="text-[#f43f5e] hover:text-[#be123c] font-bold border-0 bg-transparent cursor-pointer text-base">&times;</button>
  </div>
@endif

<!-- TOP BAR -->
<div class="topbar">
  <span>@makenooise_&nbsp;&nbsp;+62 333 888 777</span>
  <span>www.nooisealbummusic.com.id</span>
</div>

<!-- NAV -->
<nav>
  <a href="#" class="logo">no<span>&#8734;</span>ise</a>
  <a href="{{ route('halaman.about') }}">


    <button class="cart-icon" id="cartBtn" type="button">
  
    <svg viewBox="0 0 24 24">
      <circle cx="9" cy="21" r="1"/>
      <circle cx="20" cy="21" r="1"/>
      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>
    <span class="cart-badge" id="cartBadge">{{ $cartCount ?? 0 }}</span>
  </button>
  </a>
  <div class="nav-actions">
    <form action="{{route('halaman.login')}}">
      <button class="btn-ghost" id="btnLog-out">Log-out</button>
    </form>
    <button class="btn-ghost" id="btnDaftar" @click="showModal = true">Profile</button>
  </div>
</nav>

<!-- HERO BANNER (SWIPER IMAGE SLIDER) -->
<div class="hero-slider-container">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">
      @foreach($slides as $slide)
        <div class="swiper-slide">
          <img src="{{ asset($slide) }}" alt="Nooise Promo Banner">
        </div>
      @endforeach
    </div>
    <!-- Pagination (titik-titik di bawah) -->
    <div class="swiper-pagination"></div>
    <!-- Navigasi panah kiri-kanan -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</div>

<!-- MAIN CONTENT -->
<main>

  <!-- SIDEBAR + PRODUCTS -->
  <div class="content-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="searchInput" placeholder="Search..." value="{{ request('search') }}"/>
      </div>

      <div class="sidebar-section">
        <h4 class="sidebar-heading">Kategori</h4>
        <div class="sidebar-divider"></div>
        <ul class="category-list">
          <li><button class="cat-btn active" data-cat="all">Semua</button></li>
          <li><button class="cat-btn" data-cat="rnb">R&amp;B</button></li>
          <li><button class="cat-btn" data-cat="hiphop">HipHop</button></li>
        </ul>
        <p class="sidebar-sub">Mendatang</p>
        <ul class="category-list">
          <li><button class="cat-btn" data-cat="pop">POP</button></li>
          <li><button class="cat-btn" data-cat="edm">EDM</button></li>
        </ul>
      </div>

      <div class="sidebar-section">
        <h4 class="sidebar-heading harga-heading">HARGA</h4>
        <div class="price-filters">
          <label class="price-label">
            <input type="checkbox" class="price-check" data-min="300000" data-max="500000" checked/>
            <span>Rp 300.000 - Rp 500.000</span>
          </label>
          <label class="price-label">
            <input type="checkbox" class="price-check" data-min="500000" data-max="1000000" checked/>
            <span>Rp 500.000 - Rp 1.000.000</span>
          </label>
          <label class="price-label">
            <input type="checkbox" class="price-check" data-min="1000000" data-max="9999999" checked/>
            <span>Rp 1.000.000 +</span>
          </label>
        </div>
      </div>
    </aside>

    <!-- PRODUCT GRID -->
    <div class="products-area">
      <div class="product-grid" id="productGrid">
        @foreach($products as $product)
          <div class="product-card" data-id="{{ $product->sku }}" data-price="{{ $product->price }}" data-cat="{{ $product->category }}" data-title="{{ $product->name }}">
            <a href="{{ route('halaman.deskripsi', ['id' => $product->id]) }}" style="text-decoration: none; color: inherit;">
              <div class="product-img-wrap {{ $product->color ?? 'brown' }}">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" onerror="this.parentElement.style.background='#7a4020'"/>
                <div class="vinyl {{ $product->vinyl_class }}">
                  <div class="vinyl-center" style="background-image: url('{{ asset($product->image) }}');"></div>
                </div>
              </div>
              <p class="product-name">{{ $product->name }}</p>
            </a>
            <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <button class="btn-add-cart" data-id="{{ $product->id }}" data-price="{{ $product->price }}" type="button">+ Keranjang</button>
          </div>
        @endforeach
      </div><!-- /product-grid -->

      <p class="no-results" id="noResults" style="display:none">Tidak ada produk ditemukan.</p>
    </div><!-- /products-area -->

  </div><!-- /content-layout -->
</main>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <span class="footer-logo">no&#8734;ise</span>
      <p class="footer-tag">100%</p>
      <p class="footer-sub">AUTHENTIC &amp; GUARANTEED</p>
    </div>
    <div class="footer-col">
      <a href="#">FAQ</a>
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Panduan Beli</a>
      <a href="#">NooiseNews</a>
    </div>
    <div class="footer-col">
      <p class="footer-col-title">Keep in touch with us!</p>
      <div class="social-icons">
        <a href="#" class="social-btn" title="Email">&#9993;</a>
        <a href="#" class="social-btn" title="TikTok">&#9835;</a>
        <a href="#" class="social-btn" title="Instagram">&#9711;</a>
      </div>
    </div>
    <div class="footer-col store-col">
      <a href="#" class="store-badge" id="btnAppStore">&#63743; App Store</a>
      <a href="#" class="store-badge" id="btnGPlay">&#9654; Google Play</a>
    </div>
  </div>
</footer>

<!-- TOAST -->
<div class="toast" id="toast"></div>



<form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}" style="display:none">
  @csrf
  <input type="hidden" name="product_id" id="form-product-id">
  <input type="hidden" name="quantity" value="1">
</form>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-add-cart').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const productId = btn.getAttribute('data-id') || '';

        if (!productId) {
            alert("Produk belum terdaftar di database!");
            return;
        }

        document.getElementById('form-product-id').value = productId;
        
        const form = document.getElementById('add-to-cart-form');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('cartBadge');
                let count = parseInt(badge.innerText) || 0;
                badge.innerText = count + 1;
            }
        })
        .catch(error => console.error('Error:', error));
      });
    });
  });
</script>
<!-- Swiper.js JS CDN & Initialization -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.hero-swiper', {
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      keyboard: {
        enabled: true,
      },
      effect: 'slide',
      speed: 800,
    });
  });
</script>
<script src="{{ asset('script.js') }}"></script>

<!-- FLOATING MODAL PROFILE (Tailwind CSS + Alpine.js) -->
<div 
  x-show="showModal" 
  class="fixed inset-0 z-[9999] flex items-center justify-center p-4" 
  x-cloak
>
  <!-- Dark glassmorphism overlay -->
  <div 
    x-show="showModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="showModal = false"
    class="fixed inset-0 bg-[#3b1f0e]/70 backdrop-blur-sm transition-opacity"
  ></div>

  <!-- Modal Box -->
  <div 
    x-show="showModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="relative bg-white rounded-2xl w-full max-w-sm overflow-hidden p-8 shadow-2xl border border-[#c49a6c]/20 z-10 transition-all text-center"
  >
    <!-- Accent Top Bar -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#c49a6c]"></div>

    <!-- Close Button (X) -->
    <button 
      @click="showModal = false" 
      class="absolute top-4 right-4 text-[#5a3a1a] hover:text-[#3b1f0e] bg-[#ede6d6]/50 hover:bg-[#ede6d6] p-1.5 rounded-full transition-all duration-200 cursor-pointer border-0"
      aria-label="Tutup"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    @if($user)
      <!-- Profile Details view (hidden when showResetForm is true) -->
      <div x-show="!showResetForm">
        <!-- Profile User Icon -->
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-[#ede6d6]/50 flex items-center justify-center text-[#5a2d0c]">
          <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
          </svg>
        </div>

        <h3 class="text-lg font-bold text-[#3b1f0e] mb-4 font-serif" style="font-family: 'DM Serif Display', serif;">Informasi Profil</h3>

        <!-- Contact Details from DB -->
        <div class="space-y-3 text-left mb-6">
          <div class="bg-[#ede6d6]/30 border border-[#ede6d6]/60 rounded-xl p-3.5">
            <span class="block text-[10px] uppercase tracking-wider text-[#9a7050] font-semibold mb-0.5">Email</span>
            <span class="text-sm font-bold text-[#3b1f0e] break-all">{{ $user->email }}</span>
          </div>
          
          <div class="bg-[#ede6d6]/30 border border-[#ede6d6]/60 rounded-xl p-3.5">
            <span class="block text-[10px] uppercase tracking-wider text-[#9a7050] font-semibold mb-0.5">Nomor Telepon</span>
            <span class="text-sm font-bold text-[#3b1f0e]">{{ $user->phone_number ?? '-' }}</span>
          </div>
        </div>

        <!-- Reset Password Trigger Button -->
        <button 
          type="button"
          @click="showResetForm = true"
          class="block w-full mb-3 py-2.5 bg-[#ede6d6] hover:bg-[#ebdcb9] text-[#5a2d0c] font-semibold rounded-xl text-center shadow-sm hover:shadow transition-all duration-200 text-sm border-0 cursor-pointer"
        >
          Reset Password
        </button>
      </div>

      <!-- Reset Password form view (shown when showResetForm is true) -->
      <div x-show="showResetForm" x-cloak>
        <!-- Lock Icon -->
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-[#ede6d6]/50 flex items-center justify-center text-[#5a2d0c]">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>

        <h3 class="text-lg font-bold text-[#3b1f0e] mb-4 font-serif" style="font-family: 'DM Serif Display', serif;">Reset Password</h3>
        
        <form action="{{ route('profile.reset-password') }}" method="POST" class="space-y-4 text-left">
          @csrf
          <div class="bg-[#ede6d6]/30 border border-[#ede6d6]/60 rounded-xl p-3.5">
            <label class="block text-[10px] uppercase tracking-wider text-[#9a7050] font-semibold mb-1">Password Baru</label>
            <input type="password" name="password" required class="w-full bg-[#fdf8ed] border-0 rounded-lg px-3 py-2 text-sm text-[#6d422d] outline-none font-sans" style="font-style: normal;">
          </div>
          <div class="bg-[#ede6d6]/30 border border-[#ede6d6]/60 rounded-xl p-3.5">
            <label class="block text-[10px] uppercase tracking-wider text-[#9a7050] font-semibold mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required class="w-full bg-[#fdf8ed] border-0 rounded-lg px-3 py-2 text-sm text-[#6d422d] outline-none font-sans" style="font-style: normal;">
          </div>

          <div class="flex gap-3 mt-6">
            <button 
              type="button" 
              @click="showResetForm = false"
              class="flex-1 py-2.5 bg-[#ede6d6]/60 hover:bg-[#ede6d6] text-[#5a2d0c] font-semibold rounded-xl text-center transition-all duration-200 text-sm border-0 cursor-pointer"
            >
              Batal
            </button>
            <button 
              type="submit" 
              class="flex-1 py-2.5 bg-[#5a2d0c] hover:bg-[#6b3a1f] text-white font-semibold rounded-xl text-center shadow-md hover:shadow-lg transition-all duration-200 text-sm border-0 cursor-pointer"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    @else
      <!-- Not Logged In State -->
      <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-[#ede6d6]/50 flex items-center justify-center text-[#9a7050]">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
      </div>

      <h3 class="text-lg font-bold text-[#3b1f0e] mb-2 font-serif" style="font-family: 'DM Serif Display', serif;">Belum Masuk</h3>
      <p class="text-sm text-[#9a7050] mb-6">Silakan masuk ke akun Anda terlebih dahulu untuk melihat informasi profil Anda.</p>

      <a 
        href="{{ route('halaman.login') }}"
        class="block w-full py-2.5 bg-[#5a2d0c] hover:bg-[#6b3a1f] text-white font-semibold rounded-xl text-center shadow-md hover:shadow-lg transition-all duration-200 text-sm no-underline"
      >
        Login Sekarang
      </a>
    @endif

    <button 
      @click="showModal = false" 
      class="w-full mt-2 py-2 bg-transparent hover:bg-[#ede6d6]/30 text-[#5a3a1a] hover:text-[#3b1f0e] font-semibold rounded-xl transition-all duration-200 cursor-pointer border-0 text-sm"
    >
      Tutup
    </button>
  </div>
</div>

</body>
</html>