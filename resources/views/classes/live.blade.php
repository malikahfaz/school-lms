<x-app-layout>
    <div class="h-[calc(100vh-65px)] bg-black">
        <div id="jitsi-meet-api-container" class="w-full h-full"></div>
    </div>

    <!-- Load Jitsi External API -->
    <script src="{{ rtrim(config('services.jitsi.base_url', env('JITSI_BASE_URL', 'https://meet.jit.si')), '/') }}/external_api.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
        });
    </script>
</x-app-layout>
