@extends('admin.layout')

@section('title', 'Theme Editor: ' . ucfirst($theme))

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Theme Editor: {{ ucfirst($theme) }}</h1>
            <p class="mt-1 text-sm text-slate-600">Edit the Blade templates for this theme directly.</p>
        </div>
        <a href="{{ route('admin.themes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Back to Themes
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2 text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Select File --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4 flex items-center">
                        <i class="fas fa-file-code text-blue-500 mr-2"></i> Edit Existing File
                    </h3>
                    <form action="{{ route('admin.themes.edit', $theme) }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <select name="file" class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select a File --</option>
                            @foreach($files as $file)
                                <option value="{{ $file }}" {{ $selectedFile === $file ? 'selected' : '' }}>
                                    {{ $file }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="sm:w-auto w-full px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm h-10">
                            Load File
                        </button>
                    </form>
                </div>

                @if($selectedFile)
                    <div class="mt-4 pt-4 border-t border-slate-100 flex justify-end">
                        <form action="{{ route('admin.themes.files.destroy', $theme) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $selectedFile }}? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="filename" value="{{ $selectedFile }}">
                            <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors border border-red-200">
                                <i class="fas fa-trash mr-2"></i> Delete This File
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Create New File --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4 flex items-center">
                        <i class="fas fa-plus-circle text-emerald-500 mr-2"></i> Create New File
                    </h3>
                    <form action="{{ route('admin.themes.files.store', $theme) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="flex-1">
                            <input type="text" name="filename" placeholder="e.g. layouts/custom.blade.php" required
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 h-10">
                            <p class="text-xs text-slate-500 mt-1">.blade.php will be appended if omitted.</p>
                        </div>
                        <div class="sm:self-start">
                            <button type="submit" class="sm:w-auto w-full px-6 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors shadow-sm h-10">
                                Create File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Editor Area --}}
        <div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full min-h-[600px]">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center text-sm font-medium text-slate-700">
                        <i class="fas fa-terminal mr-2 text-slate-400"></i>
                        {{ $selectedFile ?? 'No file selected' }}
                    </div>
                </div>

                @if($selectedFile)
                    <form action="{{ route('admin.themes.files.update', $theme) }}" method="POST" class="flex flex-col flex-1 h-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="filename" value="{{ $selectedFile }}">
                        
                        <textarea name="content" spellcheck="false"
                                  class="w-full flex-1 p-5 font-mono text-sm text-slate-800 bg-white border-0 focus:ring-0 resize-none outline-none leading-relaxed whitespace-pre"
                                  style="min-height: 500px; tab-size: 4;"
                        >{{ old('content', $fileContent) }}</textarea>

                        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 p-10 text-center min-h-[500px]">
                        <i class="fas fa-file-code text-5xl mb-4 text-slate-300"></i>
                        <h3 class="text-lg font-medium text-slate-900 mb-1">No File Selected</h3>
                        <p class="text-sm">Select a file from above to start editing, or create a new one.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
