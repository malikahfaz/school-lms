<x-app-layout>
    <div class="h-[calc(100vh-65px)] bg-black relative">
        {{-- Jitsi container --}}
        <div id="jitsi-meet-api-container" class="w-full h-full"></div>

        {{-- Recording controls --}}
        <div class="absolute top-4 right-4 z-50 flex gap-2">
            <button
                id="startRecordingBtn"
                class="">
                Start Recording
            </button>
            <button
                id="stopRecordingBtn"
                class=""
                disabled>
                Stop Recording
            </button>
        </div>
    </div>

    <!-- Load Jitsi External API -->
    <script src="{{ rtrim(config('services.jitsi.base_url', env('JITSI_BASE_URL', 'https://meet.jit.si')), '/') }}/external_api.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ===== JITSI INIT =====
            const domain = "{{ parse_url(config('services.jitsi.base_url', env('JITSI_BASE_URL', 'https://meet.jit.si')), PHP_URL_HOST) }}";
            const options = {
                roomName: "{{ $classroom->meeting_room_name }}",
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#jitsi-meet-api-container'),
                userInfo: {
                    email: "{{ $user->email }}",
                    displayName: "{{ $user->name }}"
                },
                configOverwrite: {
                    startWithAudioMuted: true,
                    startWithVideoMuted: true
                },
                interfaceConfigOverwrite: {
                    SHOW_JITSI_WATERMARK: false
                }
            };
            const api = new JitsiMeetExternalAPI(domain, options);

            // ===== CUSTOM RECORDING (SCREEN + MIC) =====
            let mediaRecorder = null;
            let recordedChunks = [];
            let displayStream = null;
            let micStream = null;
            let recordingStartedAt = null; // timestamp (ms)

            const startBtn = document.getElementById('startRecordingBtn');
            const stopBtn  = document.getElementById('stopRecordingBtn');

            startBtn.addEventListener('click', startCustomRecording);
            stopBtn.addEventListener('click', stopCustomRecording);

            async function startCustomRecording() {
                try {
                    // 1) Ask for screen/tab (where Jitsi is running)
                    displayStream = await navigator.mediaDevices.getDisplayMedia({
                        video: true,
                        audio: true
                    });

                    // 2) Ask for mic
                    micStream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });

                    // 3) Combine tracks
                    const combinedStream = new MediaStream([
                        ...displayStream.getVideoTracks(),
                        ...micStream.getAudioTracks()
                    ]);

                    recordedChunks = [];
                    mediaRecorder = new MediaRecorder(combinedStream, {
                        mimeType: 'video/webm'
                    });

                    mediaRecorder.ondataavailable = (event) => {
                        if (event.data && event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = handleRecordingStop;

                    recordingStartedAt = Date.now();
                    mediaRecorder.start();

                    startBtn.disabled = true;
                    stopBtn.disabled  = false;

                    console.log('Recording started');
                } catch (err) {
                    console.error('Error starting recording:', err);
                    alert('Unable to start recording. Please allow screen & mic permissions.');
                }
            }

            function stopCustomRecording() {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                }

                if (displayStream) {
                    displayStream.getTracks().forEach(track => track.stop());
                    displayStream = null;
                }
                if (micStream) {
                    micStream.getTracks().forEach(track => track.stop());
                    micStream = null;
                }

                startBtn.disabled = false;
                stopBtn.disabled  = true;

                console.log('Recording stopped');
            }

            function handleRecordingStop() {
                const blob = new Blob(recordedChunks, { type: 'video/webm' });
                const recordingEndedAt = Date.now();
                const durationSeconds = Math.round((recordingEndedAt - recordingStartedAt) / 1000);

                uploadRecordingToServer(blob, durationSeconds, recordingStartedAt);
            }

            function uploadRecordingToServer(blob, durationSeconds, startedAtMs) {
                const formData = new FormData();
                formData.append('recording', blob, `class-{{ $classroom->id }}-${Date.now()}.webm`);
                formData.append('class_id', "{{ $classroom->id }}");
                formData.append('duration', durationSeconds);
                formData.append('recorded_at', new Date(startedAtMs).toISOString());

                fetch("{{ route('recordings.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Upload failed');
                    return response.json();
                })
                .then(data => {
                    console.log('Recording uploaded:', data);
                    alert('Recording saved successfully.');
                })
                .catch(error => {
                    console.error('Error uploading recording:', error);
                    alert('Error uploading recording. Please try again.');
                });
            }
        });
    </script>
</x-app-layout>
