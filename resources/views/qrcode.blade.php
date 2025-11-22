
@extends('test.ap')

@section('content')

<div class="container">
    <h1>QR Code Generator</h1>
    
    <div class="qr-code-container">
        {!! $qrCode !!}
    </div>
    
    <p class="mt-3">Scan this QR code to visit our website</p>
    
    <!-- Optional download button -->
    <a href="{{ route('qrcode.download') }}" class="btn btn-primary mt-3">
        Download QR Code
    </a>
</div>
<style>
    .qr-code-container {
        padding: 20px;
        background: white;
        display: inline-block;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

@endsection