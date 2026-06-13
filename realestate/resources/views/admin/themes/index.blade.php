@extends('admin.layout')

@section('title', 'Themes Manager')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Themes Manager</h1>
            <p class="mt-1 text-sm text-slate-600">Customize the frontend appearance of your real estate platform.</p>
        </div>
        <form action="{{ route('admin.themes.store') }}" method="POST" class="flex items-center gap-2">
            @csrf
            <input type="text" name="name" placeholder="New Theme Name" required
                   class="px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-slate-900 w-64">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus"></i>
                Create New Theme
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Themes Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($themes as $theme)
            <div class="bg-white rounded-xl border {{ $theme['isActive'] ? 'border-blue-500 ring-1 ring-blue-500 shadow-md' : 'border-slate-200 shadow-sm' }} overflow-hidden transition-all hover:shadow-md flex flex-col">
                {{-- Screenshot --}}
                <div class="h-48 bg-slate-100 relative border-b border-slate-100">
                    @if(!empty($theme['screenshot']))
                        <img src="{{ $theme['screenshot'] }}" alt="{{ $theme['name'] }} Screenshot" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                            <i class="fas fa-paint-roller text-4xl mb-2"></i>
                            <span class="text-sm font-medium">No Preview</span>
                        </div>
                    @endif
                    
                    @if($theme['isActive'])
                        <div class="absolute top-3 left-3 px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center">
                            <i class="fas fa-check-circle mr-1.5"></i> Active
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-slate-900">{{ $theme['name'] }}</h3>
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">v{{ $theme['version'] ?? '1.0' }}</span>
                    </div>
                    
                    <p class="text-sm text-slate-600 mb-4 line-clamp-2 flex-1">{{ $theme['description'] ?? 'No description provided.' }}</p>
                    
                    <div class="text-xs text-slate-500 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-edit"></i>
                        <span>By {{ $theme['author'] ?? 'Unknown' }}</span>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-auto flex justify-between items-center gap-2">
                        @if($theme['isActive'])
                            <button disabled class="w-full px-4 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-xl border border-blue-200 cursor-default">
                                Currently Active
                            </button>
                        @else
                            <form action="{{ route('admin.themes.activate') }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to activate the {{ $theme['name'] }} theme?')">
                                @csrf
                                <input type="hidden" name="theme" value="{{ $theme['folder'] }}">
                                <button type="submit" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                    Activate Theme
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.themes.edit', $theme['folder']) }}" class="px-4 py-2 bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 text-sm font-medium rounded-xl transition-colors shadow-sm" title="Edit Theme Files">
                            <i class="fas fa-code"></i>
                        </a>

                        @if($theme['folder'] !== 'default')
                            <form action="{{ route('admin.themes.destroy', $theme['folder']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this theme? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-xl border shadow-sm transition-colors {{ $theme['isActive'] ? 'bg-slate-50 text-slate-400 border-slate-200 cursor-not-allowed' : 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100' }}" title="Delete Theme" {{ $theme['isActive'] ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-xl border border-slate-200 border-dashed">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-slate-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-1">No Themes Found</h3>
                <p class="text-sm text-slate-500">There are currently no themes installed in the themes directory.</p>
            </div>
        @endforelse
    </div>
@endsection
