<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\JitsiService;
use Tests\TestCase;

class JitsiServiceTest extends TestCase
{
    public function test_generates_correct_room_name()
    {
        $service = new JitsiService();
        $user = new User(['name' => 'John Doe']);
        $title = 'Math Class';

        $roomName = $service->generateRoomName($user, $title);

        $this->assertStringContainsString('john-doe', $roomName);
        $this->assertStringContainsString('math-class', $roomName);
        // It should have a random suffix, so at least 3 parts separated by -
        $this->assertGreaterThanOrEqual(3, count(explode('-', $roomName)));
    }
}
