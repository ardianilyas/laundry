<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayananRequest;
use App\Models\Service;
use App\Service\LayananService;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function __construct(protected LayananService $layananService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = $this->layananService->getAllService();
        return inertia('Layanan/Index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Layanan/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LayananRequest $request)
    {
        $data = $request->validated();
        $this->layananService->createService($data);
        return redirect()->route('dashboard.layanan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $layanan)
    {
        $service = $layanan;
        return inertia('Layanan/Edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LayananRequest $request, Service $layanan)
    {
        $data = $request->validated();
        $this->layananService->updateService($layanan, $data);
        return redirect()->route('dashboard.layanan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $layanan)
    {
        $this->layananService->deleteService($layanan);
        return back();
    }
}
