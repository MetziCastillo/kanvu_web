<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardDataController extends Controller
{
    public function informationUsers()
    {
        abort_unless((int) Auth::user()->role?->id === 1, 403);
        $usersInformation = User::query()
            ->select('id', 'status_id', 'id_rol')
            ->get();


        return Inertia::render(
            'dashboard/admin_role/Dashboard',
            [
                'usersInformation' => $usersInformation
            ]
        );
    }
}
