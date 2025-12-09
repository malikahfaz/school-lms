<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4 px-6">
                <a href="{{ route('classes.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Create New Class
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Your Classes</h3>
                    @if($classes->isEmpty())
                        <p class="text-gray-500">You haven't created any classes yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($classes as $class)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="font-bold text-xl">{{ $class->title }}</h4>
                                    <p class="text-sm text-gray-500">{{ $class->starts_at?->format('F j, Y, g:i a') }}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('classes.show', $class) }}" class="text-blue-500 hover:underline">View Details</a>
                                        <a href="{{ route('classes.live', $class) }}" class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded">
                                            Start Live
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
