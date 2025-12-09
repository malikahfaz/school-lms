<?php

// app/Http/Controllers/RecordingController.php
namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecordingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'recording'   => 'required|file|mimetypes:video/webm,video/mp4|max:204800', // 200MB
            'class_id'    => 'required|exists:classrooms,id',
            'duration'    => 'nullable|integer',
            'recorded_at' => 'nullable|date',
        ]);

        $file = $request->file('recording');

        // store on S3 (or any disk you configured)
        $path = $file->store('recordings', 's3');

        // full URL for file_url column
        $fileUrl = Storage::disk('s3')->url($path);

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
