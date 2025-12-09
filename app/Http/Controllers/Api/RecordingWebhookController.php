<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecordingWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Security Check
        $signature = $request->header('X-Jitsi-Signature');
        if ($signature !== config('services.jitsi.webhook_secret', env('JITSI_WEBHOOK_SECRET'))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->all();
        Log::info('Jitsi Recording Callback', $data);

        $roomName = $data['room_name'] ?? null;
        $fileUrl = $data['file_url'] ?? null;

        if (!$roomName || !$fileUrl) {
            return response()->json(['error' => 'Invalid Payload'], 400);
        }

        $classroom = Classroom::where('meeting_room_name', $roomName)->first();

        if (!$classroom) {
            Log::warning("Recording received for unknown room: {$roomName}");
            return response()->json(['status' => 'ignored', 'reason' => 'Room not found'], 200);
        }

        Recording::create([
            'class_id' => $classroom->id,
            'file_url' => $fileUrl,
            'duration' => $data['duration'] ?? 0,
            'size_bytes' => $data['size_bytes'] ?? 0,
            'recorded_at' => now(), // or parse $data['recorded_at']
        ]);

        return response()->json(['status' => 'ok']);
    }
}
