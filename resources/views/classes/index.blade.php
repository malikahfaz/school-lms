<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($classes as $classroom)
                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <h4 class="font-bold text-xl">{{ $classroom->title }}</h4>
                                <p class="text-sm text-gray-500">Instructor: {{ optional($classroom->teacher)->name ?? 'Unknown' }}</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <a href="{{ route('classes.show', $classroom) }}" class="text-blue-500 hover:underline">View Details</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $classes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
