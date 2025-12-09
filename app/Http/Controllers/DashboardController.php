<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('dashboard.admin');
        } elseif ($user->isTeacher()) {
            $classes = $user->teacherClasses()->orderBy('starts_at', 'desc')->get();
            return view('dashboard.teacher', compact('classes'));
        } else {
            // Student
            $availableClasses = \App\Models\Classroom::with('teacher')->orderBy('starts_at', 'asc')->get();
            $enrolledClasses = $user->enrolledClasses()->with('teacher')->orderBy('starts_at', 'asc')->get();
            return view('dashboard.student', compact('availableClasses', 'enrolledClasses'));
        }
    }
}
