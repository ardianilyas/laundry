<?php

namespace App\Services;

use App\Models\Service;

class LayananService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllService() {
        return Service::latest()->get();
    }

    public function createService(array $data) {
        return Service::create($data);
    }

    public function updateService(Service $service, array $data) {
        return $service->update($data);
    }

    public function deleteService(Service $service) {
        return $service->delete();
    }
}
