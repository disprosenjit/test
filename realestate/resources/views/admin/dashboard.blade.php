@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-600">Welcome back! Here is an overview of your real estate platform.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Properties --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Properties</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalProperties }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $availableProperties }} available</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Inquiries --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-green-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Inquiries</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $contactInquiries }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $newContactInquiries }} new</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Special Requests --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-yellow-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Special Requests</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $specialRequests }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $pendingRequests }} pending</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Investments --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-purple-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Investments</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $investmentInquiries }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $newInvestmentInquiries }} new</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Inquiries --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Recent Inquiries</h2>
                <a href="{{ route('admin.inquiries.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentInquiries as $inquiry)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 text-sm text-slate-900">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="hover:text-blue-600">{{ $inquiry->name }}</a>
                                </td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $inquiry->email }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ Str::limit($inquiry->subject, 30) }}</td>
                                <td class="px-6 py-3 text-sm text-slate-500">{{ $inquiry->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">
                                    <i class="fas fa-inbox text-slate-300 text-2xl mb-2"></i>
                                    <p>No inquiries yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Requests --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Recent Requests</h2>
                <a href="{{ route('admin.requests.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Budget</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentRequests as $request)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 text-sm text-slate-900">
                                    <a href="{{ route('admin.requests.show', $request) }}" class="hover:text-blue-600">{{ $request->name }}</a>
                                </td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $request->property_type_preference }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $request->budget_range }}</td>
                                <td class="px-6 py-3 text-sm text-slate-500">{{ $request->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">
                                    <i class="fas fa-inbox text-slate-300 text-2xl mb-2"></i>
                                    <p>No requests yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
