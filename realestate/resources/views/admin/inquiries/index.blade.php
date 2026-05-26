@extends('admin.layout')

@section('title', 'Contact Inquiries')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Contact Inquiries</h1>
        <p class="mt-1 text-sm text-slate-600">View and manage all contact form submissions.</p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $inquiry->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $inquiry->email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($inquiry->subject, 35) }}</td>
                            <td class="px-6 py-4">
                                @if($inquiry->status === 'new')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">New</span>
                                @elseif($inquiry->status === 'read')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Read</span>
                                @elseif($inquiry->status === 'replied')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Replied</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $inquiry->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-envelope text-slate-300 text-3xl mb-3"></i>
                                    <p class="text-sm text-slate-500">No inquiries found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
@endsection
