<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Manage System</h3>
                    <div class="flex space-x-4">
                        <a href="{{ route('classes.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View All Classes
                        </a>
                        <!-- Add User Management Link Here -->
                         <button class="bg-gray-500 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed">
                            Manage Users (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
