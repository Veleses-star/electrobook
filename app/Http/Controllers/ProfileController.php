<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\Purchase;
use App\Models\ShopItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $testResults = TestResult::where('user_id', $user->id)
                            ->whereHas('test')
                            ->with('test.subject')
                            ->orderBy('completed_at', 'desc')
                            ->take(10)
                            ->get();
        $purchases = Purchase::where('user_id', $user->id)
                            ->whereHas('item')  
                            ->with('item')
                            ->orderBy('purchased_at', 'desc')
                            ->get();
        $totalTests = $testResults->count();
        $averageScore = $testResults->avg('percentage') ?? 0;
        $totalPoints = $user->points;
        return view('profile.index', compact('user', 'testResults', 'purchases', 'totalTests', 'averageScore', 'totalPoints'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return back()->with('status', 'profile-updated');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);
        $user = Auth::user();
        if (!$user->can_upload_avatar) {
            return back()->with('error', 'У вас нет права загружать аватар.');
        }
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar_path = $path;
        $user->save();
        return back()->with('success', 'Аватар обновлён!');
    }

    public function toggleFrame(Request $request)
    {
        $user = Auth::user();
        $hasFrame = Purchase::where('user_id', $user->id)
                            ->whereHas('item', fn($q) => $q->where('type', 'frame'))
                            ->exists();
        if (!$hasFrame) {
            return back()->with('error', 'У вас нет купленной рамки.');
        }
        if ($user->frame_class) {
            $user->frame_class = null;
            $message = 'Рамка отключена.';
        } else {
            $user->frame_class = 'gold-frame';
            $message = 'Золотая рамка включена!';
        }
        $user->save();
        return back()->with('success', $message);
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}