<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Services\JitsiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    protected JitsiService $jitsiService;

    public function __construct(JitsiService $jitsiService)
    {
        $this->jitsiService = $jitsiService;
    }

    public function index()
    {
        // General list of classes
        $classes = Classroom::with('teacher')->orderBy('starts_at')->paginate(10);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        // $this->authorize('create', Classroom::class);
        return view('classes.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Classroom::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $teacher = Auth::user();
        $roomName = $this->jitsiService->generateRoomName($teacher, $request->title);
        $meetingUrl = $this->jitsiService->getMeetingUrl($roomName);

        $classroom = Classroom::create([
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'meeting_room_name' => $roomName,
            'meeting_url' => $meetingUrl,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        return redirect()->route('classes.show', $classroom)->with('success', 'Class created successfully.');
    }

    public function show(Classroom $class)
    {
        $class->load(['teacher', 'recordings']);
        return view('classes.show', ['classroom' => $class]);
    }

    public function live(Classroom $class)
    {
       
        // Check access: Teacher or Enrolled Student
        $user = Auth::user();
        // Simple check without Policy for brevity, but Policy is better.
        // Allowing loose check for now based on requirements.
        $canAccess = $user->id === $class->teacher_id || $class->students->contains($user->id) || $user->isAdmin();

        if (!$canAccess) {
            abort(403, 'You must be enrolled to join this live class.');
        }

        return view('classes.live', ['classroom' => $class, 'user' => $user]);
    }

    public function enroll(Classroom $class)
    {
        $user = Auth::user();
        if ($user->isStudent()) {
            if (!$class->students->contains($user->id)) {
                $class->students()->attach($user->id);
            }
        }
        return redirect()->route('classes.show', $class)->with('success', 'Enrolled successfully.');
    }
}
