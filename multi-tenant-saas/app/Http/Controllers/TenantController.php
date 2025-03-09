<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant;

class TenantController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.tenant-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'unique:domains,domain'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tenant = Tenant::create([
            'id' => Str::random(16),
            'company' => $request->company,
        ]);

        $tenant->domains()->create([
            'domain' => $request->domain . '.' . config('tenancy.central_domains')[0],
        ]);

        // Create the admin user for the tenant
        $tenant->run(function () use ($request) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        });

        return redirect()->route('tenant.registered')
            ->with('success', 'Your account has been created successfully!');
    }

    public function showSelectForm()
    {
        $domains = Domain::all();
        return view('auth.tenant-select', compact('domains'));
    }

    public function select(Request $request)
    {
        $request->validate([
            'domain' => ['required', 'string', 'exists:domains,domain'],
        ]);

        return redirect()->to('http://' . $request->domain);
    }
}
