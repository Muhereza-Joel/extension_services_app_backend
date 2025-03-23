<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        $validatedData = $request->validated();

        // Extract only the date part
        $validatedData['date_of_birth'] = date('Y-m-d', strtotime($validatedData['date_of_birth']));

        // Check if a profile exists for the user_id
        $profile = Profile::where('user_id', $validatedData['user_id'])->first();

        if ($profile) {
            // Update existing profile
            $profile->update($validatedData);
            $message = "Profile updated successfully";
        } else {
            // Create new profile
            Profile::create($validatedData);
            $message = "Profile created successfully";
        }

        return response()->json([
            "message" => $message
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$id) {
            return response()->json([
                "message" => "User ID is required"
            ], 400);
        }

        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json([
                "message" => "Profile not found"
            ], 404);
        }

        return response()->json($profile, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
