<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;

class FirebaseAuthController extends Controller
{
    protected $auth;
    protected $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(base_path('path/to/firebase_credentials.json'))
            ->createAuth();
    }

    public function login(Request $request)
    {
        try {
            // Validate the request
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // Verify with Firebase
            $signInResult = $this->firebase->signInWithEmailAndPassword(
                $credentials['email'], 
                $credentials['password']
            );

            // Get the user
            $firebaseUser = $signInResult->data();

            // Find or create user in Laravel
            $user = User::firstOrCreate(
                ['email' => $firebaseUser['email']],
                [
                    'name' => $firebaseUser['displayName'] ?? explode('@', $firebaseUser['email'])[0],
                    'email_verified_at' => $firebaseUser['emailVerified'] ? now() : null,
                ]
            );

            // Get custom claims (role)
            $claims = $this->firebase->getUser($firebaseUser['localId'])->customClaims;
            $role = $claims['role'] ?? 'user';
            
            // Update user role if needed
            if ($user->role !== $role) {
                $user->role = $role;
                $user->save();
            }

            // Login user in Laravel
            Auth::login($user, $request->filled('remember'));

            // Return response based on role
            return response()->json([
                'status' => 'success',
                'redirect' => $role === 'admin' ? '/kopma/admin/dashboard' : '/kopma/dashboard'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/kopma/login');
    }
}