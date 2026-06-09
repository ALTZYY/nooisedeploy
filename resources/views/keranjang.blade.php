<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nooise – Keranjang Anda</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{asset('cssnooise/stylekeranjang.css')}}"/>
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
  <span>@makenooise_&nbsp;&nbsp;+62 333 888 777</span>
  <span>www.nooisealbummusic.com.id</span>
</div>

<!-- NAV -->
<nav>
  <a href="{{route('halaman.home')}}" class="logo">no<span>∞</span>ise</a>
  <button class="cart-icon" id="cartBtn">
    <svg viewBox="0 0 24 24">
      <circle cx="9" cy="21" r="1"/>
      <circle cx="20" cy="21" r="1"/>
      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>
  </button>
  <div class="nav-actions">
    <!-- <button class="btn-ghost" id="btnMasuk">Masuk</button>
    <button class="btn-ghost" id="btnDaftar">Daftar</button> -->
  </div>
</nav>

<!-- MAIN -->
<main>

  <!-- PAGE CARD -->
  <div class="page-card">

    <!-- PAGE HEADER -->
    <div class="page-header">
      <!-- <div class="search-wrap">
        <svg viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Search.."/>
      </div> -->
      <h2 class="page-title">Keranjang Anda</h2>
      <!-- <button class="back-btn" id="backBtn" title="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M9 14l-4-4 4-4"/>
          <path d="M5 10h11a4 4 0 0 1 0 8h-1"/>
        </svg>
      </button> -->
    </div>

    <!-- CART GRID -->
    <div class="cart-grid">

      <!-- LEFT -->
      <div class="cart-left">

        <!-- SELECT ALL BAR -->
        <div class="select-all-bar">
          <!-- <button class="check-btn active" id="checkAll">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </button> -->
<span>Total Semua Product &nbsp;<strong id="cartCountText">({{ $carts->sum('quantity') }})</strong></span>
        </div>

@php
  $totalPrice = 0;
@endphp

        <div id="cartItemsContainer">
          @if($carts->isEmpty())
            <p style="color:#777;margin:16px 0">Keranjang kosong.</p>
          @else
            @foreach($carts as $cart)
              @php
                $itemPrice = $cart->product->price ?? 0;
                $subtotal = $itemPrice * $cart->quantity;
                $totalPrice += $subtotal;
              @endphp
              <div class="cart-item-card" id="cart-item-{{ $cart->id }}">
                <img src="{{ asset($cart->product->image) }}" alt="{{ $cart->product->name }}" onerror="this.style.background='#b8845a'" />
                <div class="item-meta">
                  <p class="item-title">{{ $cart->product->name }}</p>
                  <div style="display:flex; align-items:center; gap: 8px; margin: 4px 0;">
                    <button class="btn-qty" data-id="{{ $cart->id }}" data-action="decrement" style="padding: 2px 10px; border-radius: 4px; border: 1px solid #ccc; background: transparent; color: white; cursor: pointer;">-</button>
                    <p class="item-artist" style="margin:0;">Qty: <span id="qty-val-{{ $cart->id }}">{{ $cart->quantity }}</span></p>
                    <button class="btn-qty" data-id="{{ $cart->id }}" data-action="increment" style="padding: 2px 10px; border-radius: 4px; border: 1px solid #ccc; background: transparent; color: white; cursor: pointer;">+</button>
                  </div>
                  <p class="item-price" data-price="{{ $itemPrice }}" id="price-val-{{ $cart->id }}">Rp {{ number_format($itemPrice, 0, ',', '.') }} x {{ $cart->quantity }} = Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                </div>
              </div>
            @endforeach
          @endif
        </div>

      </div><!-- /cart-left -->

      <!-- RIGHT -->
      <div class="cart-right">
        <div class="order-card">
          <h3 class="order-title">Pesanan Anda</h3>

          <div class="voucher-row">
            <span class="voucher-icon">🏷</span>
            <input
              type="text"
              id="voucherInput"
              class="voucher-input"
              placeholder="Pilih Voucher Diskon Anda..."
            />
          </div>

          <div class="order-total-row">
            <span class="total-label">Total</span>
          </div>

          <div class="order-bottom">
            <span class="total-amount" id="totalAmount">Rp.{{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '0' }}</span>
            <form action="{{ url('/pay') }}" id="formBeli">
              <button class="btn-beli" id="btnBeli" type="submit">BELI</button>
            </form>
            <!-- <button class="btn-beli" id="btnBeli">
              <em>Beli</em>
            </button> -->
          </div>
        </div>
      </div><!-- /cart-right -->

    </div><!-- /cart-grid -->
  </div><!-- /page-card -->

  <!-- UNTUK ANDA -->
  <section class="recommendations">
    <h3 class="rec-title">Untuk Anda</h3>
    <div class="rec-grid">
      @foreach($recommendedProducts as $recProduct)
        <a href="{{ route('halaman.deskripsi', ['id' => $recProduct->id]) }}" style="text-decoration: none; color: inherit; display: block; height: 100%;">
          <div class="rec-card" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div class="rec-img-wrap">
                <img
                  src="{{ asset($recProduct->image) }}"
                  alt="{{ $recProduct->name }}"
                  onerror="this.style.opacity='0'; this.parentElement.style.background='#1a2a4a';"
                />
              </div>
              <p class="rec-name">{{ $recProduct->name }}</p>
              <p class="rec-artist">{{ $recProduct->brand }}</p>
            </div>
            <p class="rec-price" style="margin-top: auto;">Rp {{ number_format($recProduct->price, 0, ',', '.') }}</p>
          </div>
        </a>
      @endforeach
    </div>
  </section>

</main>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<!-- SUCCESS MODAL -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-box">
    <div class="modal-icon">✓</div>
    <h3>Pembelian Berhasil!</h3>
    <p>Freudian – Daniel Caesar sedang diproses.</p>
    <button class="btn-modal-close" id="modalClose">Tutup</button>
  </div>
</div>



<script>
  document.addEventListener('DOMContentLoaded', function () {
    const qtyButtons = document.querySelectorAll('.btn-qty');
    
    function formatRupiah(n) {
      return 'Rp ' + Number(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function recalculateTotal() {
      let total = 0;
      let count = 0;
      document.querySelectorAll('.item-price').forEach(el => {
        const price = parseInt(el.getAttribute('data-price')) || 0;
        const id = el.id.replace('price-val-', '');
        const qtyEl = document.getElementById('qty-val-' + id);
        if (qtyEl) {
           const qty = parseInt(qtyEl.innerText) || 0;
           total += price * qty;
           count += qty;
        }
      });
      document.getElementById('totalAmount').innerText = formatRupiah(total);
      document.getElementById('cartCountText').innerText = '(' + count + ')';
    }

    qtyButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const cartId = this.getAttribute('data-id');
        const action = this.getAttribute('data-action');
        
        fetch('{{ route("cart.update") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            cart_id: cartId,
            action: action
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.is_deleted) {
              const card = document.getElementById('cart-item-' + cartId);
              if (card) card.remove();
            } else {
              const qtyEl = document.getElementById('qty-val-' + cartId);
              if (qtyEl) qtyEl.innerText = data.new_quantity;
              
              const priceEl = document.getElementById('price-val-' + cartId);
              const price = parseInt(priceEl.getAttribute('data-price'));
              priceEl.innerText = formatRupiah(price) + ' x ' + data.new_quantity + ' = ' + formatRupiah(price * data.new_quantity);
            }
            recalculateTotal();
          } else {
            alert('Gagal memperbarui: ' + data.message);
          }
        })
        .catch(err => console.error(err));
      });
    });
  });
</script>
<script src="scriptkeranjang.js"></script>
</body>
</html>