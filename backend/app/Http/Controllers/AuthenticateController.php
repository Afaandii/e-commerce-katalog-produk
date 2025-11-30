<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Services\SupabaseStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Socialite;

class AuthenticateController extends Controller
{
    public function getAllUser()
    {
        $users = User::with(['role'])->get();

        return response()->json([
            'status' => 'Ok',
            'message' => 'Get all data user successfully!',
            'datas' => $users
        ], 200);
    }

    public function getUserRoleById(string $id)
    {
        $roles = Roles::all();
        $roleById = User::findOrFail($id);

        return response()->json([
            'status' => 'Ok',
            'message' => 'Get data role user by id successfully!',
            'datas' => [
                'roles' => $roles,
                'roleById' => $roleById
            ]
        ]);
    }


    public function getUser(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login first.',
            ], 401);
        }

        $user = $request->user();

        return response()->json([
            'status' => 'Ok',
            'message' => 'Get current user data successfully!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_image' => $user->profile_image ?? null,
                'role_id' => $user->role_id,
            ],
        ], 200);
    }

    public function updateRoleUser(Request $request, string $id)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User Role updated successfully',
            'data' => $validated,
        ], 201);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ], 201);
    }

    /**
     * Update current user data
     */
    public function updateUser(Request $request)
    {
        $user = Auth::user();
        if (!$user || !isset($user['id'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login first.',
            ], 401);
        }

        $userModel = User::find($user['id']);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'profile_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [];
            if ($request->filled('name')) {
                $updateData['name'] = $request->name;
            }
            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            if ($request->hasFile('profile_image')) {
                $supabase = app(SupabaseStorage::class, ['bucket' => env('SUPABASE_BUCKET_USERS')]);

                // Hapus gambar lama jika ada
                if ($userModel->profile_image) {
                    $oldPath = str_replace(env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET_USERS') . '/', '', $userModel->profile_image);
                    try {
                        $supabase->delete($oldPath);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old user profile image: ' . $e->getMessage());
                    }
                }

                // Upload gambar baru
                $file = $request->file('profile_image');
                $fileName = 'user_' . $userModel->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = "users/{$fileName}";

                $newUrl = $supabase->upload($filePath, $file);
                $updateData['profile_image'] = $newUrl;
            }

            if (!empty($updateData)) {
                $userModel->update($updateData);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    'user' => $userModel,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * REGISTER
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'role_id'      => 'required|exists:roles,id',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'profile_image' => 'nullable|string',
        ]);

        // Upload image
        // $profileImagePath = null;
        // if ($request->hasFile('profile_image')) {
        //     $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        // }

        $user = User::create([
            'role_id'      => $validated['role_id'],
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'profile_image' => $validated['profile_image'] ?? null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User registered successfully',
            'data'    => [
                'user' => $user,
            ],
        ], 201);
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid email or password',
                'data'    => null,
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout successfully',
        ], 200);
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Kalau user belum ada → buat baru
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'profile_image' => $googleUser->getAvatar(),
                    'role_id' => 2,
                    'password' => Hash::make(uniqid()),
                ]);
            }

            // Buat token Sanctum
            $token = $user->createToken('google_token')->plainTextToken;

            // Redirect ke frontend (React)
            return redirect("http://localhost:5173/google/callback?token=$token");
        } catch (\Exception $e) {
            return redirect("http://localhost:5173/login?error=google_auth_failed");
        }
    }

    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function facebookCallback()
    {
        try {
            $fbUser = Socialite::driver('facebook')->user();

            // Cek user berdasarkan email
            $user = User::where('email', $fbUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $fbUser->getName(),
                    'email' => $fbUser->getEmail(),
                    'profile_image' => $fbUser->getAvatar(),
                    'role_id' => 2,
                    'password' => Hash::make(uniqid()),
                ]);
            }

            // Buat token login
            $token = $user->createToken('facebook_token')->plainTextToken;

            // Redirect ke React + kirim token
            return redirect("http://localhost:5173/facebook/callback?token=$token");
        } catch (\Exception $e) {
            return redirect("http://localhost:5173/login?error=facebook_auth_failed");
        }
    }
}
