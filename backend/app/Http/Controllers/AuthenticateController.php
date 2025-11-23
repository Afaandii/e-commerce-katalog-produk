<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SupabaseStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthenticateController extends Controller
{
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

    /**
     * Update current user data
     */
    public function updateUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login first.',
            ], 401);
        }

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

            // Jika ada name baru
            if ($request->filled('name')) {
                $updateData['name'] = $request->name;
            }

            // Jika ada email baru
            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            // Jika ada password baru
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Jika ada file baru
            if ($request->hasFile('profile_image')) {
                $supabase = app(SupabaseStorage::class, ['bucket' => env('SUPABASE_BUCKET_USERS')]);

                // Hapus gambar lama jika ada
                if ($user->profile_image) {
                    $oldPath = str_replace(env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET_USERS') . '/', '', $user->profile_image);
                    try {
                        $supabase->delete($oldPath);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old user profile image: ' . $e->getMessage());
                    }
                }

                // Upload gambar baru
                $file = $request->file('profile_image');
                $fileName = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = "users/{$fileName}";

                $newUrl = $supabase->upload($filePath, $file);
                $updateData['profile_image'] = $newUrl;
            }

            // Update database
            if (!empty($updateData)) {
                $user->update($updateData);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    'user' => $user,
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
}