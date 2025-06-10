<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseInvitation;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = auth()->user()->teacherInvitations()
                            ->with(['course', 'inviter'])
                            ->latest()
                            ->paginate(10);

        return view('teacher.invitations.index', compact('invitations'));
    }

    public function show(CourseInvitation $invitation)
    {
        if ($invitation->teacher_id !== auth()->id()) {
            abort(403);
        }

        return view('teacher.invitations.show', compact('invitation'));
    }

    public function accept(CourseInvitation $invitation)
    {
        // ... логика принятия приглашения
    }

    public function reject(CourseInvitation $invitation)
    {
        // ... логика отклонения приглашения
    }
}
