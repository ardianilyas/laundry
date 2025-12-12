<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
// use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use function Spatie\LaravelPdf\Support\pdf;

class PdfController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        
    }

    public function download(Request $request) {
        $month = $request->input('month', now()->format('Y-m'));
        $orders = $this->orderService->getOrdersByMonth($month);
        $totalAmount = $this->orderService->getTotalAmount($month);

        $data = [
            'orders' => $orders,
            'totalAmount' => $totalAmount,
            'month' => $month,
        ];
    
        $html = view('pdf.download', $data)->render();
    
        $fileName = 'laporan_' . $month . '_' . time() . '.pdf';

        try {
            $pdfContent = Browsershot::html($html)
                ->setNodeBinary(trim(shell_exec('which node')))
                ->setNpmBinary(trim(shell_exec('which npm')))
                ->margins(10, 10, 10, 10)
                ->format('A4')
                ->pdf();
            
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Length' => strlen($pdfContent),
            ]);
        } catch (\Exception $e) {
            Log::error('Browsershot or download error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF generation or download failed: ' . $e->getMessage()], 500);
        }
    }
}
