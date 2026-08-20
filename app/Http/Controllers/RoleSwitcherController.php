<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleSwitcherController extends Controller
{
    public function switchRole(Request $request)
    {
        $role = $request->input('role', 'hr_admin');

        if (!in_array($role, ['hr_admin', 'manager', 'employee'])) {
            $role = 'hr_admin';
        }

        session(['demo_role' => $role]);

        // Auto-login corresponding test user if exists
        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
        }

        $roleLabels = [
            'hr_admin' => 'HR Administrator',
            'manager' => 'Department Manager',
            'employee' => 'Employee Self-Service'
        ];

        return redirect()->back()->with('success', 'Switched to ' . $roleLabels[$role] . ' View!');
    }
}
