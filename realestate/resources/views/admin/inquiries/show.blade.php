@extends('admin.layout')

@section('title', 'Inquiry Details')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 mb-4 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Inquiries
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Inquiry Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Detail Card --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">{{ $inquiry->subject }}</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Name</label>
                        <p class="text-sm text-slate-900">{{ $inquiry->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Email</label>
                        <p class="text-sm text-slate-900">
                            <a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:text-blue-700">{{ $inquiry->email }}</a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Phone</label>
                        <p class="text-sm text-slate-900">{{ $inquiry->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Date</label>
                        <p class="text-sm text-slate-900">{{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Status</label>
                    @if($inquiry->status === 'new')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">New</span>
                    @elseif($inquiry->status === 'read')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Read</span>
                    @elseif($inquiry->status === 'replied')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Replied</span>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Message</label>
                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $inquiry->message }}</div>
                </div>
            </div>
        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-6">
            {{-- Update Status --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Update Status</h3>
                <form action="{{ route('admin.inquiries.updateStatus', $inquiry) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <select name="status"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $inquiry->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $inquiry->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Delete --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Danger Zone</h3>
                <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}"
                      onsubmit="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
