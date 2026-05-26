@extends('admin.layout')

@section('title', 'Investment Inquiries')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Investment Inquiries</h1>
        <p class="mt-1 text-sm text-slate-600">View and manage all investment inquiry submissions.</p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contact Person</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $inquiry->company_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $inquiry->contact_person }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 capitalize">{{ $inquiry->investment_type }}</td>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">${{ number_format($inquiry->investment_amount) }}</td>
                            <td class="px-6 py-4">
                                @if($inquiry->status === 'new')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">New</span>
                                @elseif($inquiry->status === 'reviewed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">Reviewed</span>
                                @elseif($inquiry->status === 'in-discussion')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">In Discussion</span>
                                @elseif($inquiry->status === 'closed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Closed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $inquiry->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.investments.show', $inquiry) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('admin.investments.destroy', $inquiry) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this investment inquiry?')">
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
                                    <i class="fas fa-chart-line text-slate-300 text-3xl mb-3"></i>
                                    <p class="text-sm text-slate-500">No investment inquiries found.</p>
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
