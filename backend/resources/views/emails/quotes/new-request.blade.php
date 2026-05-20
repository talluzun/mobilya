<h1>Yeni teklif talebi</h1>

<p><strong>Teklif kodu:</strong> {{ $quote->ref_code }}</p>
<p><strong>Müşteri:</strong> {{ $quote->customer_full_name }}</p>
<p><strong>E-posta:</strong> {{ $quote->customer_email }}</p>
<p><strong>Telefon:</strong> {{ $quote->customer_phone }}</p>
<p><strong>Ürün:</strong> {{ $quote->product?->name }}</p>
<p><strong>Adet:</strong> {{ $quote->quantity }}</p>
<p><strong>Durum:</strong> {{ $quote->status_label }}</p>

@if($quote->note)
    <p><strong>Not:</strong> {{ $quote->note }}</p>
@endif
