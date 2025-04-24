<?php

namespace App\Http\Controllers;

use App\Models\ScanResult;
use App\Services\UrlScannerService;
use Illuminate\Http\Request;

class UrlScannerController extends Controller
{
    protected $scannerService;

    public function __construct(UrlScannerService $scannerService)
    {
        $this->scannerService = $scannerService;
    }

    public function index()
    {
        $scanResults = ScanResult::latest()->take(10)->get();
        return view('scanner.index', compact('scanResults'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'email_subject' => 'nullable|string|max:255',
            'email_sender' => 'nullable|email|max:255',
        ]);

        $scanResult = $this->scannerService->scanUrl($request->url);
        
        $result = ScanResult::create([
            'url' => $request->url,
            'email_subject' => $request->email_subject,
            'email_sender' => $request->email_sender,
            'is_malicious' => $scanResult['is_malicious'],
            'threat_details' => $scanResult['threat_details'],
        ]);

        return response()->json([
            'success' => true,
            'result' => $result
        ]);
    }

    public function history()
    {
        $scanResults = ScanResult::latest()->paginate(20);
        return view('scanner.history', compact('scanResults'));
    }
} 