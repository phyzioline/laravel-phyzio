@extends('web.layouts.app')
@section('content')
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", "Noto Sans Arabic", "Cairo", sans-serif;
  background: #f9fafb;
  min-height: 100vh;
  padding: 20px;
}

.orders-log-page {
  max-width: 1200px;
  margin: 0 auto;
  background: #fff;
  border-radius: 16px;
  padding: 30px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  border: 1px solid #e5e7eb;
}

.header {
  text-align: center;
  margin-bottom: 40px;
  position: relative;
}

.header::after {
  content: '';
  position: absolute;
  bottom: -12px;
  left: 50%;
  transform: translateX(-50%);
  width: 90px;
  height: 4px;
  background: linear-gradient(90deg, #10b8c4, #0b8d96);
  border-radius: 2px;
}

.header h1 {
  background: linear-gradient(135deg, #10b8c4, #0b8d96);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-size: 30px;
  font-weight: 700;
  margin-bottom: 8px;
}

.header p {
  color: #6b7280;
  font-size: 15px;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 25px;
}

.card {
  background: #fff;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 3px 12px rgba(0,0,0,0.06);
  transition: all 0.25s ease;
  border: 1px solid #f1f5f9;
  position: relative;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #10b8c4, #0b8d96);
}

.card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.card:hover img {
  transform: scale(1.04);
}

.card-content {
  padding: 18px;
}

.card-content h3 {
  margin: 0 0 12px;
  font-size: 19px;
  color: #111827;
  font-weight: 600;
}

.order-number {
  background: linear-gradient(135deg, #10b8c4, #0b8d96);
  color: white;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 12px;
}

.order-details {
  background: #f9fafb;
  border-radius: 10px;
  padding: 12px 15px;
  margin: 12px 0;
  border-left: 4px solid #10b8c4;
}

.order-details .detail-row {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin-bottom: 6px;
}

.order-details .detail-row:last-child {
  margin-bottom: 0;
}

.order-details .detail-label {
  color: #6b7280;
  font-weight: 500;
}

.order-details .detail-value {
  color: #111827;
  font-weight: 600;
}

.price-highlight {
  background: linear-gradient(135deg, #10b8c4, #0b8d96);
  color: white;
  padding: 10px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 15px;
  margin: 14px 0;
  text-align: center;
}

.delivery-progress {
  margin: 12px 0;
  padding: 12px;
  background: #f1f5f9;
  border-radius: 10px;
}

.progress-steps {
  display: flex;
  justify-content: space-between;
  position: relative;
}

.progress-steps::before {
  content: '';
  position: absolute;
  top: 15px;
  left: 12%;
  right: 12%;
  height: 2px;
  background: #e5e7eb;
  z-index: 0;
}

.progress-step {
  text-align: center;
  z-index: 1;
}

.step-icon {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: auto;
  color: #64748b;
  font-size: 12px;
}

.progress-step.completed .step-icon {
  background: #10b981;
  color: #fff;
}

.progress-step.current .step-icon {
  background: #10b8c4;
  color: #fff;
  animation: pulse 1.5s infinite;
}

.step-label {
  font-size: 11px;
  color: #6b7280;
  margin-top: 5px;
}

@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(16, 184, 196, 0.6); }
  70% { box-shadow: 0 0 0 10px rgba(16, 184, 196, 0); }
  100% { box-shadow: 0 0 0 0 rgba(16, 184, 196, 0); }
}

.status {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status.delivering {
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #10b981;
}

.status.pending {
  background: #fff7ed;
  color: #b45309;
  border: 1px solid #f59e0b;
}

.status.done {
  background: #f0fdf4;
  color: #065f46;
  border: 1px solid #10b981;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}
</style>

<section id="slider-section" class="slider-section clearfix">
  <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}" style="height: 70vh">
    <div class="container text-center">
      <h1 class="hero-text mb-3">Physicaltherapy Software Solutions</h1>
      <h5 class="hero-praph">All Physical Therapist Needs is Our Mission From PT to PT</h5>
    </div>
  </div>
</section>

<div class="orders-log-page mt-5">
  <div class="header">
    <h1><i class="fas fa-shopping-bag"></i> سجل الطلبات</h1>
    <p>تابع جميع طلباتك ومراحل تقدمها</p>
  </div>

  <div class="cards">
    @foreach ($orders as $order)
      @foreach ($order->items as $item)
      <div class="card">
        @php
          $productImage = $item->product && $item->product->productImages && $item->product->productImages->first() 
            ? asset($item->product->productImages->first()->image) 
            : asset('web/assets/images/default-product.png');
        @endphp
        <img src="{{ $productImage }}" alt="{{ $item->product->{'product_name_' . app()->getLocale()} ?? 'Product' }}" style="object-fit: contain; background: #f9fafb;">
        <div class="card-content">
          <div class="order-number">طلب رقم: {{ $order->order_number }}</div>
          <h3>{{ $item->product ? $item->product->{'product_name_' . app()->getLocale()} : 'Product Not Available' }}</h3>

          <div class="order-details">
            <div class="detail-row"><span class="detail-label">تاريخ الطلب:</span><span class="detail-value">{{ $order->created_at->format('Y-m-d H:i') }}</span></div>
            <div class="detail-row"><span class="detail-label">الكمية:</span><span class="detail-value">{{ $item->quantity }} قطعة</span></div>
            @if($item->engineer_selected)
            <div class="detail-row">
                <span class="detail-label">{{ __('Medical Engineer') }}:</span>
                <span class="detail-value badge bg-info">
                    <i class="fa fa-user-md me-1"></i>
                    {{ __('Included') }} (+{{ number_format($item->engineer_price, 2) }} {{ config('currency.default_symbol', 'EGP') }})
                </span>
            </div>
            @endif
            <div class="detail-row"><span class="detail-label">طريقة الدفع:</span><span class="detail-value">{{ $order->payment_method == 'cash' ? __('Cash on Delivery') : __('Credit Card') }}</span></div>
            <div class="detail-row"><span class="detail-label">العنوان:</span><span class="detail-value">{{ $order->address }}</span></div>
          </div>

          <div class="price-breakdown" style="background: #f9fafb; border-radius: 10px; padding: 12px; margin: 12px 0;">
            <div class="detail-row" style="margin-bottom: 8px;">
              <span class="detail-label">{{ __('Subtotal') }}:</span>
              <span class="detail-value">{{ number_format($item->price * $item->quantity, 2) }} {{ config('currency.default_symbol', 'EGP') }}</span>
            </div>
            @if($item->shipping_fee && $item->shipping_fee > 0)
            <div class="detail-row" style="margin-bottom: 8px;">
              <span class="detail-label">{{ __('Shipping Fee') }}:</span>
              <span class="detail-value">{{ number_format($item->shipping_fee, 2) }} {{ config('currency.default_symbol', 'EGP') }}</span>
            </div>
            @endif
            @php
              $itemSubtotal = $item->price * $item->quantity;
              $itemShipping = $item->shipping_fee ?? 0;
              $itemTotal = $itemSubtotal + $itemShipping;
            @endphp
            <div class="detail-row" style="border-top: 2px solid #10b8c4; padding-top: 8px; margin-top: 8px;">
              <span class="detail-label" style="font-weight: 700; font-size: 15px;">{{ __('Total') }}:</span>
              <span class="detail-value" style="font-weight: 700; font-size: 15px; color: #10b8c4;">{{ number_format($itemTotal, 2) }} {{ config('currency.default_symbol', 'EGP') }}</span>
            </div>
          </div>

          <div class="price-highlight"><i class="fas fa-tag"></i> {{ number_format($itemTotal, 2) }} {{ config('currency.default_symbol', 'EGP') }}</div>

          <div class="delivery-progress">
            <div class="progress-steps">
              <div class="progress-step completed"><div class="step-icon"><i class="fas fa-check"></i></div><div class="step-label">تم الطلب</div></div>
              <div class="progress-step completed"><div class="step-icon"><i class="fas fa-check"></i></div><div class="step-label">تم التأكيد</div></div>
              <div class="progress-step current"><div class="step-icon"><i class="fas fa-truck"></i></div><div class="step-label">قيد التوصيل</div></div>
              <div class="progress-step"><div class="step-icon"><i class="fas fa-home"></i></div><div class="step-label">تم التسليم</div></div>
            </div>
            <div style="text-align:center;font-size:12px;color:#64748b;margin-top:8px;">
              <i class="fas fa-clock"></i> متوقع التسليم: 18 يناير 2024
            </div>
          </div>

          <span class="status delivering"><i class="fas fa-truck"></i> قيد التوصيل</span>
        </div>
      </div>
      @endforeach
    @endforeach
  </div>
</div>
@endsection
