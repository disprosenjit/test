@extends('admin.layout')

@section('title', 'Properties')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Properties</h1>
            <p class="mt-1 text-sm text-slate-600">Manage all property listings.</p>
        </div>
        <div class="flex items-center gap-8">
            <form action="{{ route('admin.properties.index') }}" method="GET" class="relative flex items-center">
                <i class="fas fa-search absolute left-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, type, city..." 
                       class="pl-11 pr-8 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-slate-900 w-64">
                @if(request('search'))
                    <a href="{{ route('admin.properties.index') }}" class="absolute right-3 text-slate-400 hover:text-slate-600 flex items-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.properties.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                <i class="fas fa-plus"></i>
                Add Property
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-24">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Property Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($properties as $property)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                @php $mainImage = $property->images->sortBy('order')->first(); @endphp
                                @if($mainImage)
                                    <img src="{{ asset($mainImage->image_path) }}" alt="{{ $property->title }}" class="w-16 h-16 object-cover rounded-lg border border-slate-200">
                                @else
                                    <div class="w-16 h-16 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="font-medium text-slate-900 text-sm">{{ Str::limit($property->title, 40) }}</div>
                                    <div class="text-xs text-slate-500 flex items-center gap-2">
                                        <span><i class="fas fa-map-marker-alt mr-1 text-slate-400"></i>{{ Str::limit($property->location, 30) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($property->is_featured)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100">Featured</span>
                                        @endif
                                        @if($property->is_available)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-green-50 text-green-700 border border-green-100">Available</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-50 text-red-700 border border-red-100">Unavailable</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 capitalize">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fas fa-tag mr-1.5 text-slate-400"></i>
                                    {{ $property->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">${{ number_format($property->price, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.properties.edit', $property) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.properties.destroy', $property) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this property?')">
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-building text-slate-300 text-3xl mb-3"></i>
                                    <p class="text-sm text-slate-500 mb-4">No properties found.</p>
                                    <a href="{{ route('admin.properties.create') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus"></i>
                                        Add Your First Property
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($properties->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
@endsection
