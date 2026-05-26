@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pages</h1>
            <p class="text-gray-600 mt-1">Manage static pages like Privacy Policy, About Us, etc.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i> Create Page
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search by title or slug..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i> Search
            </button>
            <a href="{{ route('admin.pages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Reset
            </a>
        </form>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($pages->count())
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Slug</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pages as $page)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $page->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $page->slug }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($page->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $page->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-crud-actions
                                    :viewRoute="route('admin.pages.show', $page)"
                                    :editRoute="route('admin.pages.edit', $page)"
                                    :deleteRoute="route('admin.pages.destroy', $page)"
                                    deleteMessage="Are you sure you want to delete this page? This action cannot be undone."
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pages->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <i class="fas fa-file text-4xl text-gray-300 mb-4 block"></i>
                <p class="text-gray-600 text-lg">No pages found.</p>
                <a href="{{ route('admin.pages.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                    Create your first page <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
