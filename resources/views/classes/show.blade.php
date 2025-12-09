<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $classroom->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ $classroom->title }}</h3>
                            <p class="text-gray-500 mb-4">Instructor: {{ optional($classroom->teacher)->name ?? 'Unknown' }}</p>
                            <p class="mb-4">{{ $classroom->description }}</p>
                            <div class="text-sm text-gray-400">
                                <p>Start: {{ $classroom->starts_at?->format('F j, Y, g:i a') ?? 'TBA' }}</p>
                                <p>End: {{ $classroom->ends_at?->format('F j, Y, g:i a') ?? 'TBA' }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('classes.live', ['class' => $classroom->id]) }}" class="bg-red-600 hover:bg-red-800 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center">
                                <span class="mr-2">🔴</span> Join Live Class
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recordings Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Recordings</h3>
                    @if($classroom->recordings->isEmpty())
                        <p class="text-gray-500">No recordings available for this class.</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($classroom->recordings as $recording)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">Recording from {{ $recording->recorded_at?->format('F j, Y, g:i a') ?? 'Unknown Date' }}</p>
                                        <p class="text-xs text-gray-400">Duration: {{ gmdate("H:i:s", $recording->duration) }}</p>
                                    </div>
                                    <a href="{{ $recording->file_url }}" target="_blank" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-2 px-4 rounded">
                                        Watch / Download
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
