<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(['email' => 'admin@school.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Teacher
        $teacher = User::updateOrCreate(['email' => 'teacher@school.com'], [
            'name' => 'Mr. Smith',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        // Student
        $student = User::updateOrCreate(['email' => 'student@school.com'], [
            'name' => 'John Student',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Another Student
        $student2 = User::updateOrCreate(['email' => 'jane@school.com'], [
            'name' => 'Jane Student',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Create Active Class (Ongoing)
        $activeClass = Classroom::create([
            'teacher_id' => $teacher->id,
            'title' => 'Live Physics Session',
            'description' => 'Happening Right Now! Join immediately.',
            'meeting_room_name' => 'physics-live-'.Str::random(5),
            'meeting_url' => 'https://meet.jit.si/physics-live-'.Str::random(5),
            'starts_at' => now()->subMinutes(30), // Started 30 mins ago
            'ends_at' => now()->addMinutes(30),   // Ends in 30 mins
        ]);

        // Create Future Class
        $futureClass = Classroom::create([
            'teacher_id' => $teacher->id,
            'title' => 'Advanced Mathematics',
            'description' => 'Calculus and Linear Algebra. Scheduled for tomorrow.',
            'meeting_room_name' => 'math-preview-'.Str::random(5),
            'meeting_url' => 'https://meet.jit.si/math-preview-'.Str::random(5),
            'starts_at' => now()->addDay()->setHour(10)->setMinute(0),
            'ends_at' => now()->addDay()->setHour(11)->setMinute(30),
        ]);

        // Enroll Students
        $activeClass->students()->sync([$student->id, $student2->id]);
        $futureClass->students()->sync([$student->id]);
    }
}
