<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Transfer Pencairan Dana</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px dashed #ccc;
            position: relative;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #1e3a8a;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .checkmark svg {
            width: 40px;
            height: 40px;
            fill: white;
        }
        
        .amount-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .date {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        
        .details {
            border-top: 1px dashed #ccc;
            padding-top: 20px;
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .detail-label {
            color: #666;
            font-weight: normal;
        }
        
        .detail-value {
            color: #333;
            font-weight: 500;
            text-align: right;
        }
        
        .section-divider {
            border-top: 1px dashed #ccc;
            margin: 20px 0;
        }
        
        .total-section {
            border-top: 2px solid #333;
            padding-top: 15px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
        }
        
        .route-section {
            margin-top: 30px;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
        }
        
        .route-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
        }
        
        .route-point {
            margin-bottom: 15px;
        }
        
        .route-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .route-location {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        
        .route-time {
            font-size: 11px;
            color: #999;
        }
        
        .route-address {
            font-size: 11px;
            color: #666;
        }
        
        .route-line {
            text-align: center;
            margin: 15px 0;
            font-size: 18px;
            color: #999;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
        }
        
        .close-button {
            background: #999;
            color: white;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Transaksi Saldo Berhasil</h1>
            
            <div class="checkmark">
                <svg viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
        </div>
        
        <div class="amount-section">
            <div class="date">{{ $generated_at->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
            <div class="amount">{{ number_format($withdrawal->amount, 2, ',', '.') }},-</div>
        </div>
        
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">ID Pesanan</span>
                <span class="detail-value">{{ $transaction_id }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">No. Transaksi</span>
                <span class="detail-value">{{ $invoice_number }}</span>
            </div>
            
            <div class="section-divider"></div>
            
            <div class="detail-row">
                <span class="detail-label">Metode Refund</span>
                <span class="detail-value">Transfer BRIVA</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Layanan Nebeng</span>
                <span class="detail-value">
                    @if($type === 'partner')
                        @if(isset($withdrawal->partner->vehicles) && $withdrawal->partner->vehicles->count() > 0)
                            {{ $withdrawal->partner->vehicles->first()->vehicle_type }}
                        @else
                            Motor
                        @endif
                    @else
                        Terminal
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Biaya Penumpang</span>
                <span class="detail-value">2 x {{ number_format($withdrawal->amount / 2, 2, ',', '.') }},-</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label"></span>
                <span class="detail-value">{{ number_format($withdrawal->amount, 2, ',', '.') }},-</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Biaya Admin</span>
                <span class="detail-value">0.000,00,-</span>
            </div>
            
            <div class="total-section">
                <div class="total-row">
                    <span>Total Refund</span>
                    <span>{{ number_format($withdrawal->amount, 2, ',', '.') }},-</span>
                </div>
            </div>
        </div>
        
        <div class="route-section">
            <div class="route-header">
                <span>Titik Jemput</span>
                <span>Tujuan</span>
            </div>
            
            <div class="route-point">
                <div class="route-label">Titik Jemput</div>
                <div class="route-location">Yogyakarta</div>
                <div class="route-time">09:30 WIB</div>
                <div class="route-address">Alun-alun Yogyakarta</div>
            </div>
            
            <div class="route-line">● ∙∙∙ ●</div>
            
            <div class="route-point">
                <div class="route-label">Tujuan</div>
                <div class="route-location">Purwokerto</div>
                <div class="route-time">09:30 WIB</div>
                <div class="route-address">Alun-alun Purwokerto</div>
            </div>
        </div>
        
        <div class="footer">
            <div class="close-button">CLOSE</div>
        </div>
    </div>
</body>
</html>