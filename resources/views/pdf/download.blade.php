<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white p-10 text-gray-800">
    <div class="mt-6">
        <h2 class="text-3xl text-center font-semibold">Laporan Transaksi {{ $month }}</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">Order Number</th>
                    <th class="border p-2">Berat (kg)</th>
                    <th class="border p-2">Total Harga</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="border p-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $order->order_number }}</td>
                    <td class="border p-2">{{ $order->quantity }}</td>
                    <td class="border p-2">Rp {{ number_format($order->orderDetail->amount, 0, ',', '.') }}</td>
                    <td class="border p-2">{{ $order->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="mt-6 text-right font-bold">Total Pendapatan (lunas): Rp. {{ number_format($totalAmount, 2) }}</p>
</body>
</html>
