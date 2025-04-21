<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {       
        $filters = $request->only(['name', 'address']);

        // Note: no filter for sorting, only using 'latest' update/create

        $users = User::latest()->Filter($filters)->get();

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'address' => 'required|max:100',
            'image' => 'required|image'
        ]);

        // dd($validated);

        $path = $request->file('image')->store('images', 'public');

        $user = User::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'image' => $path,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // note: bug in here if using form-data on my machine
        // x-www-form-urlencoded works but can't upload image
        $validated = $request->validate([
            'name' => 'required|max:50',
            'address' => 'required|max:100',
            'image' => 'nullable|image'
        ]);

    
        $user = User::findOrFail($id);
    
        $user->name = $validated['name'];
        $user->address = $validated['address'];
    
        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $imagePath = $request->file('image')->store('users', 'public');
            $user->image = $imagePath;
        }
        
        $user->save();
    
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }
        
        // AfterNote: 
        // Maybe instead of delete just use unlink storage especially if backup needed
        // For this case I think it's fine like this or like this it's fine actually

        $user->delete();
    
        return response()->json(['message' => 'User deleted']);
    }
}
