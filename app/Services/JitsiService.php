<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class JitsiService
{
    protected string $baseUrl;

    public function __construct()
    {
        // Remove trailing slash if present
        $this->baseUrl = rtrim(config('services.jitsi.base_url', env('JITSI_BASE_URL')), '/');
    }

    /**
     * Generate a unique, hard-to-guess room name.
     */
    public function generateRoomName(User $teacher, string $title): string
    {
        // Format: [InstructorSlug]-[TitleSlug]-[RandomString]
        // e.g. john-doe-math-101-a1b2c3d4
        $teacherSlug = Str::slug($teacher->name);
        $titleSlug = Str::slug($title);
        $random = Str::random(8);

        return "{$teacherSlug}-{$titleSlug}-{$random}";
    }

    /**
     * Get the full meeting URL.
     */
    public function getMeetingUrl(string $roomName): string
    {
        return "{$this->baseUrl}/{$roomName}";
    }
}
