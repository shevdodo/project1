<![CDATA[<x-layouts.dashboard title="Superuser Dashboard">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Administrative Console</h2>
            <p class="text-sm text-gray-500 mt-1">
                Manage the entire platform. Only visible to superusers.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <button class="px-5 py-2.5 bg-rose-600 text-white text-sm font-medium rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-600/25">
                <span class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>New User</span>
                </span>
            </button>
            <button class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                Export Data
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-emerald-600 font-medium mt-1">↑ 12 this month</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-lg shadow-sky-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Standard Users</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['standard_users'] }}</p>
                    <p class="text-xs text-gray-400 font-medium mt-1">{{ $stats['total_users'] > 0 ? round(($stats['standard_users'] / $stats['total_users']) * 100) : 0 }}% of all users</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Superusers</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['superusers'] }}</p>
                    <p class="text-xs text-amber-600 font-medium mt-1">Admin-level accounts</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">New This Week</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">8</p>
                    <p class="text-xs text-emerald-600 font-medium mt-1">↑ 15% vs last week</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- System Health + Quick Alerts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-5">System Health</h3>
            <div class="space-y-5">
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">CPU Load</span>
                        <span class="text-gray-800 font-bold">32%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: 32%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">Memory Usage</span>
                        <span class="text-gray-800 font-bold">1.8 GB / 8 GB</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-500 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">Disk Usage</span>
                        <span class="text-gray-800 font-bold">64.2 GB / 256 GB</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: 64%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">Database</span>
                        <span class="flex items-center space-x-1.5 text-emerald-600 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Online</span>
                        </span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Recent Signups</h3>
                <span class="text-xs text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">Updated live</span>
            </div>
            <div class="space-y-1">
                @foreach($users->take(6) as $user)
                <div class="flex items-center space-x-4 py-2.5 border-b border-gray-50 last:border-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider rounded-full {{ $user->role === 'superuser' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $user->role }}
                    </span>
                    <span class="text-xs text-gray-400 shrink-0 hidden sm:inline-block">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- All Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">All Registered Users</h3>
            <div class="flex items-center space-x-3">
                <div class="relative hidden sm:block">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search users..." class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 transition w-44">
                </div>
                <span class="text-sm text-gray-400">{{ count($users) }} total</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3.5">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            </label>
                        </th>
                        <th class="px-6 py-3.5">User</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">Role</th>
                        <th class="px-6 py-3.5">Joined</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $user->role === 'superuser' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-brand-600 p-1.5 rounded-lg hover:bg-gray-100 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button class="text-gray-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
            <span>Showing {{ count($users) }} user(s)</span>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">&larr; Prev</button>
                <button class="px-3 py-1.5 border border-gray-200 rounded-lg bg-brand-600 text-white hover:bg-brand-700 transition">1</button>
                <button class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">2</button>
                <button class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">3</button>
                <span class="text-gray-300">...</span>
                <button class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next &rarr;</button>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
]]>