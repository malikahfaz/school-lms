<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_class()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($teacher)->post(route('classes.store'), [
            'title' => 'Math 101',
            'description' => 'Introduction to Calculus',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHour(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('classes', ['title' => 'Math 101']);
    }

    public function test_student_cannot_create_class()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->post(route('classes.store'), [
            'title' => 'Hacking 101',
        ]);

        $response->assertForbidden();
    }

    public function test_enrolled_student_can_access_live_class()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        
        $class = Classroom::create([
            'teacher_id' => $teacher->id,
            'title' => 'Physics Live',
            'meeting_room_name' => 'phys-123',
            'meeting_url' => 'http://example.com/phys-123',
        ]);

        // Enroll
        $class->students()->attach($student->id);

        $response = $this->actingAs($student)->get(route('classes.live', $class));
        $response->assertStatus(200);
    }

    public function test_unenrolled_student_cannot_access_live_class()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        $class = Classroom::create([
            'teacher_id' => $teacher->id,
            'title' => 'Physics Private',
            'meeting_room_name' => 'phys-456',
            'meeting_url' => 'http://example.com/phys-456',
        ]);

        $response = $this->actingAs($student)->get(route('classes.live', $class));
        $response->assertStatus(403);
    }
}
