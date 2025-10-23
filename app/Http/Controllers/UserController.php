<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;

class UserController extends Controller
{

    public function __construct(private UserService $userService)
    {
        
    }

    public function index() {
        $users = $this->userService->getAllUsers();
        return inertia('Users/Index', compact('users'));
    }

    public function create() {
        return inertia('Users/Create');
    }

    public function store(StoreUserRequest $request) 
    {
        $this->userService->storeUser($request->validated());
        
        return redirect(route('dashboard.users.index'));
    }
}
