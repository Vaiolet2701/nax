<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ban;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BanController extends Controller
{
    public function index()
    {
        $bannedUsers = Ban::with(['user', 'admin'])
            ->where('permanent', true)
            ->orWhere('expires_at', '>', now())
            ->latest()
            ->paginate(10);

        return view('admin.bans.index', compact('bannedUsers'));
    }

    public function create(User $user)
    {
        return view('admin.bans.create', compact('user'));
    }
    
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
            'type' => 'required|in:temporary,permanent',
            'days' => 'required_if:type,temporary|integer|min:1'
        ]);
    
        $banData = [
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'reason' => $validated['reason'],
            'permanent' => $validated['type'] === 'permanent',
        ];
    
        if ($validated['type'] === 'temporary') {
            $banData['expires_at'] = now()->addDays((int) $validated['days']);
        }

    
        $user->bans()->create($banData);
    
        return redirect()->route('admin.bans.index')
            ->with('success', 'Пользователь успешно заблокирован');
    }

    public function destroy(Ban $ban)
    {
        $ban->delete();
        return back()->with('success', 'Блокировка снята');
    }
}