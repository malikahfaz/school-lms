<?php

// app/Http/Controllers/RecordingController.php
namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecordingController extends Controller
{
    // app/Http/Controllers/RecordingController.php

public function store(Request $request)
{
    $request->validate([
        'recording'   => 'required|file|mimetypes:video/webm,video/mp4|max:204800', // 200MB
        'class_id'    => 'required',
        'duration'    => 'nullable|integer',
        'recorded_at' => 'nullable|date',
    ]);

    $file = $request->file('recording');

    // ✅ store locally on "public" disk instead of s3
    $path = $file->store('recordings', 'public');

    // ✅ make URL for file_url column
    $fileUrl = Storage::disk('public')->url($path);

    $recording = Recording::create([
        'class_id'    => $request->class_id,
        'file_url'    => $fileUrl,
        'duration'    => $request->duration,
        'size_bytes'  => $file->getSize(),
        'recorded_at' => $request->recorded_at ?? now(),
    ]);

    return response()->json([
        'status'    => 'ok',
        'recording' => $recording,
    ]);
}

}
