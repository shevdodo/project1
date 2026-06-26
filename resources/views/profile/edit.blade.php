<x-layouts.dashboard title="My Profile">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">My Profile</h2>
            <p class="text-sm text-gray-500 mt-1 flex items-center space-x-2">
                Manage your account settings and preferences.
            </p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-layouts.dashboard>

