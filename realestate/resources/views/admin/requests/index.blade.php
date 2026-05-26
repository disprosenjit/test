@extends('admin.layout')

@section('title', 'Special Requests')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Special Requests</h1>
        <p class="mt-1 text-sm text-slate-600">View and manage all special property requests.</p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type Preference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $request)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $request->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 capitalize">{{ $request->property_type_preference }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($request->location_preference, 25) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->budget_range }}</td>
                            <td class="px-6 py-4">
                                @if($request->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">Pending</span>
                                @elseif($request->status === 'contacted')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Contacted</span>
                                @elseif($request->status === 'closed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Closed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.requests.show', $request) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('admin.requests.destroy', $request) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-clipboard text-slate-300 text-3xl mb-3"></i>
                                    <p class="text-sm text-slate-500">No special requests found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
@endsection
