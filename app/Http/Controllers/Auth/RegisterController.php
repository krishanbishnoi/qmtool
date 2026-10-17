<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     */
    protected string $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Register company
        $company               = new Company;
        $company->company_name = $request->company_name;
        $company->contact_phone = $request->contact_phone ?? null;
        $company->contact_email = $request->contact_email ?? null;
        $company->save();

        $user = $this->create($request->all(), $company->id);

        event(new Registered($user));

        $role = Role::where('name', 'admin')->first();
        $user->attachRole($role);

        return response()->json([
            'status'  => 200,
            'message' => 'Welcome to QM Tool, Please check your email for activate your account, Thanks.',
        ], 200);
    }

    /**
     * Validate registration data.
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
            'mobile'       => ['required', 'digits:10'],
            'company_name' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance.
     */
    protected function create(array $data, int $company_id): User
    {
        return User::create([
            'company_id' => $company_id,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'mobile'     => $data['mobile'],
            'password'   => Hash::make($data['password']),
        ]);
    }
}
