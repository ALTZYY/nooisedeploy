<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nooise – {{ $product->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{asset('cssnooise/styledes.css') }}"/>
  <style>
    /* Styling cart badge in description page matching home page */
    .cart-icon {
      position: relative;
      cursor: pointer;
    }
    .cart-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #3b1f0e;
      color: #fff;
      font-size: 0.7rem;
      font-weight: 600;
      padding: 2px 6px;
      border-radius: 50%;
      border: 1.5px solid #141414;
    }
  </style>
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
  <span>@makenooise_&nbsp;&nbsp;+62 333 888 777</span>
  <span>www.nooisealbummusic.com.id</span>
</div>

<!-- NAV -->
<nav>
  <a href="{{ route('halaman.home') }}" class="logo">no<span>∞</span>ise</a>
  <a href="{{ route('halaman.about') }}">
    <button class="cart-icon" id="cartBtn" type="button">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      <span class="cart-badge" id="cartBadge">{{ $cartCount ?? 0 }}</span>
    </button>
  </a>
  <div class="search-wrap">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" placeholder="Search.."/>
  </div>
  <div class="nav-actions">
    <form action="{{route('halaman.login')}}">
      <button class="btn-ghost" id="btnLog-out">Log-out</button>
    </form>
  </div>
</nav>

<!-- MAIN -->
<main>
  <div class="product-card">

    <!-- ALBUM COVER -->
    <div class="cover-wrap">
      <img id="productImage"
        src="{{ asset($product->image) }}"
        alt="{{ $product->name }}"
        onerror="this.style.background='#b8845a';this.alt='';this.style.minHeight='300px'"
      />
      <span class="parental-badge">Parental Advisory</span>
    </div>

    <!-- INFO -->
    <div class="info">
      <h1 id="productTitle">{{ $product->name }}</h1>
      <p class="artist" id="productArtist">{{ $product->brand }}</p>
      <p class="price" id="productPrice">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

      <ul class="tracklist">
        @if($product->tracklist)
          @foreach(json_decode($product->tracklist) as $track)
            <li>{{ $track }}</li>
          @endforeach
        @endif
      </ul>

      <p class="description">
        {{ $product->description }}
      </p>
    </div>

    <!-- ACTIONS -->
    <div class="actions">
      <div class="qty-control">
        <button class="qty-btn" id="minus">−</button>
        <span class="qty-display" id="qty">1</span>
        <button class="qty-btn" id="plus">+</button>
      </div>
      <button class="btn-primary" id="btnCart">Tambah Ke Keranjang</button>
      <button class="btn-buy" id="btnBuy">Beli Langsung</button>
    </div>

  </div>
</main>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <span class="logo-footer">n∞ise</span>
      <p>100% Authentic &amp; Guaranteed</p>
    </div>
    <div class="footer-col">
      <h4>Info</h4>
      <a href="#">FAQ</a>
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Panduan Beli</a>
      <a href="#">NoiseNews</a>
    </div>
    <div class="footer-col">
      <h4>Keep in touch</h4>
      <div class="social-icons">
        <a href="#" title="Email">✉</a>
        <a href="#" title="TikTok">♪</a>
        <a href="#" title="Instagram">◎</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Download App</h4>
      <div class="store-badges">
        <a class="store-badge" href="#" id="btnAppStore">🍎 App Store</a>
        <a class="store-badge" href="#" id="btnGooglePlay">▶ Google Play</a>
      </div>
    </div>
  </div>
  <p class="footer-bottom">© 2026 Nooise Album Music · All rights reserved</p>
</footer>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const minusBtn = document.getElementById('minus');
    const plusBtn = document.getElementById('plus');
    const qtyDisplay = document.getElementById('qty');
    const btnCart = document.getElementById('btnCart');
    const btnBuy = document.getElementById('btnBuy');

    let qty = 1;

    minusBtn.addEventListener('click', function () {
      if (qty > 1) {
        qty--;
        qtyDisplay.innerText = qty;
      }
    });

    plusBtn.addEventListener('click', function () {
      qty++;
      qtyDisplay.innerText = qty;
    });

    btnCart.addEventListener('click', function () {
      const productId = "{{ $product->id }}";
      
      const formData = new FormData();
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('product_id', productId);
      formData.append('quantity', qty);

      fetch("{{ route('cart.add') }}", {
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
          if (badge) {
            let count = parseInt(badge.innerText) || 0;
            badge.innerText = count + qty;
          }
          alert("Produk berhasil ditambahkan ke keranjang!");
          window.location.href = "{{ route('halaman.about') }}";
        } else {
          alert("Gagal menambahkan ke keranjang: " + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan.");
      });
    });

    btnBuy.addEventListener('click', function () {
      const productId = "{{ $product->id }}";
      
      const formData = new FormData();
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('product_id', productId);
      formData.append('quantity', qty);

      fetch("{{ route('cart.add') }}", {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = "{{ route('halaman.payment') }}";
        } else {
          alert("Gagal memproses pembelian langsung: " + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan.");
      });
    });
  });
</script>
</body>
</html>