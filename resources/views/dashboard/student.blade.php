<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Enrolled Classes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">My Enrolled Classes</h3>
                    @if($enrolledClasses->isEmpty())
                        <p class="text-gray-500">You are not enrolled in any classes yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($enrolledClasses as $class)
                                <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                    <h4 class="font-bold text-lg">{{ $class->title }}</h4>
                                    <p class="text-sm text-gray-300">Instructor: {{ optional($class->teacher)->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-400">{{ $class->starts_at?->format('F j, Y, g:i a') }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('classes.live', $class) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            Join Live Class
                                        </a>
                                        <a href="{{ route('classes.show', $class) }}" class="ml-2 text-blue-400 hover:underline text-sm">
                                            View Recordings/Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Classes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Available Classes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        Class
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        Teacher
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        Schedule
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableClasses as $class)
                                    @if(!$enrolledClasses->contains($class->id))
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                            <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ $class->title }}</p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                            <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ $class->teacher->name }}</p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                            <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                                {{ $class->starts_at?->format('M j, g:i a') }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                            <form action="{{ route('classes.enroll', $class) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-blue-500 hover:text-blue-800">
                                                    Enroll
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
