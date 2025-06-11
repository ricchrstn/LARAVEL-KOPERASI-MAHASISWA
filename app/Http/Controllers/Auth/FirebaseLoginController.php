<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FirebaseLoginController extends Controller
{
    private FirebaseAuth $firebase;

    public function __construct(FirebaseAuth $auth)
    {
        $this->firebase = $auth;
    }

    public function verify(Request $request)
    {
        $idToken = $request->input('token');
        $clientRole = $request->input('role') ?? 'mahasiswa';

        if (!$idToken) {
            return response()->json([
                'success' => false,
                'error'   => 'Token tidak ditemukan.'
            ], 422);
        }

        try {
            /* ─── Verifikasi ID-Token ─── */
            $verified   = $this->firebase->verifyIdToken($idToken);
            $firebaseUid = $verified->claims()->get('sub');

            /* ─── Ambil data user Firebase ─── */
            $firebaseUser = $this->firebase->getUser($firebaseUid);
            $email        = $firebaseUser->email;
            $displayName  = $firebaseUser->displayName ?? 'Pengguna Baru';
            $nimClaim     = $firebaseUser->customClaims['nim'] ?? null;

            /* ─── Sinkron user ke database Laravel ─── */
            $user = User::where('firebase_uid', $firebaseUid)
                        ->orWhere('email', $email)
                        ->first();

            if (!$user) {
                // buat user baru
                $user = User::create([
                    'name'         => $displayName,
                    'email'        => $email,
                    'firebase_uid' => $firebaseUid,
                    'role'         => $clientRole,
                    'nim'          => $nimClaim,
                    'password'     => bcrypt(uniqid()) // dummy
                ]);
            } else {
                // update info dasar bila berubah
                $user->update([
                    'name'  => $displayName,
                    'role'  => $user->role ?? $clientRole,
                    'nim'   => $user->nim ?? $nimClaim
                ]);
            }

            /* ─── Login ke Laravel ─── */
            Auth::login($user, true);

            /* ─── Redirect path ─── */
            $redirect = $user->role === 'admin'
                      ? '/admin/dashboard'
                      : '/dashboard';

            return response()->json([
                'success'     => true,
                'redirect_to' => $redirect
            ]);

        } catch (\Throwable $e) {
            Log::error('Firebase login error', ['message'=>$e->getMessage()]);
            return response()->json([
                'success' => false,
                'error'   => 'Token Firebase tidak valid: '.$e->getMessage()
            ], 401);
        }
    }
}
