<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes - {{ $product->product_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: white;
        }

        .hide {
            display: none !important;
        }
        
        .barcode-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .barcode-label {
            border: 2px dashed #ccc;
            padding: 15px;
            text-align: center;
            background: white;
            border-radius: 8px;
            page-break-inside: avoid;
        }
        
        .product-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
            word-wrap: break-word;
        }
        
        .barcode-image {
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .barcode-number {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        
        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 8px;
        }
        
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .print-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            width: 100%;
            justify-content: center;
        }
        
        .print-btn:hover {
            background: #1d4ed8;
        }

        .one-btn {
            background: #059669 !important;
        }

        .one-btn:hover {
            background: #047857 !important;
        }
        
        .close-btn {
            background: #ef4444;
        }
        
        .close-btn:hover {
            background: #dc2626;
        }
        
        @media print {
            .print-controls {
                display: none;
            }
            
            .barcode-container {
                padding: 10px;
            }
            
            .barcode-label {
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
            
            @page {
                margin: 10mm;
            }
        }
        
        @media (max-width: 768px) {
            .barcode-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button onclick="window.print()" class="print-btn">
            Print Barcodes ({{ $quantity }})
        </button>

        <button onclick="printOne()" class="print-btn one-btn">
            Print 1 Barcode
        </button>

        <button onclick="window.close()" class="print-btn close-btn">
            Close
        </button>
    </div>

    <!-- Barcodes Grid -->
    <div class="barcode-container">
        @for($i = 0; $i < $quantity; $i++)
            <div class="barcode-label">
                <div class="product-name">{{ $product->product_name }}</div>
                <div class="barcode-image">
                    {!! $barcodeSvg !!}
                </div>
                <div class="barcode-number">{{ $product->barcode }}</div>
            <!--    <div class="product-price">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div> -->
            </div>
        @endfor
    </div>

    <script>
        function printOne() {
            const labels = document.querySelectorAll('.barcode-label');

            // sembunyikan semua kecuali index ke 0
            labels.forEach((label, index) => {
                if (index !== 0) {
                    label.classList.add('hide');
                }
            });

            // lakukan print
            window.print();

            // setelah print, balikin normal
            setTimeout(() => {
                labels.forEach(label => label.classList.remove('hide'));
            }, 500);
        }
    </script>
</body>
</html>
