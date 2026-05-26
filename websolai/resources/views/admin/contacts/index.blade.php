@extends('admin.layout')

@section('title', 'Contact Enquiries')
@section('heading', 'Contact Enquiries')
@section('subheading', $contacts->total() . ' total · ' . $unreadCount . ' unread')

@section('content')

{{-- Stats --}}
@php
    $total  = $contacts->total();
    $unread = $unreadCount;
    $read   = $total - $unread;
    $today  = \App\Models\Contact::whereDate('created_at', today())->count();
@endphp

<div class="row mb-4">
    @foreach([
        ['label' => 'Total Enquiries', 'value' => $total,  'icon' => 'fa-envelope',      'color' => 'info'],
        ['label' => 'Unread',          'value' => $unread, 'icon' => 'fa-bell',           'color' => 'danger'],
        ['label' => 'Read',            'value' => $read,   'icon' => 'fa-check-circle',   'color' => 'success'],
        ['label' => 'Today',           'value' => $today,  'icon' => 'fa-calendar-day',   'color' => 'warning'],
    ] as $stat)
    <div class="col-6 col-xl-3">
        <div class="small-box bg-{{ $stat['color'] }}">
            <div class="inner">
                <h3>{{ $stat['value'] }}</h3>
                <p>{{ $stat['label'] }}</p>
            </div>
            <div class="icon"><i class="fas {{ $stat['icon'] }}"></i></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Table Card --}}
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title font-weight-semibold"><i class="fas fa-list mr-2"></i>All Enquiries</h3>
        @if($unreadCount > 0)
        <span class="badge badge-danger ml-2">{{ $unreadCount }} new</span>
        @endif
    </div>
    <div class="card-body p-0">
        @if($contacts->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
            No enquiries yet. Contact form submissions will appear here.
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Service</th>
                        <th>Budget</th>
                        <th>Received</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr class="{{ !$contact->is_read ? 'table-active font-weight-bold' : '' }}">
                        <td class="text-muted" style="font-size:.8rem;">{{ $contact->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mr-2 font-weight-bold flex-shrink-0"
                                      style="width:32px;height:32px;background:linear-gradient(135deg,#4f46e5,#7c3aed);font-size:.8rem;">
                                    {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                                </span>
                                {{ $contact->full_name }}
                            </div>
                        </td>
                        <td>{{ $contact->email }}</td>
                        <td><span class="badge badge-light border">{{ $contact->service_label }}</span></td>
                        <td class="text-muted" style="font-size:.85rem;">{{ $contact->budget_label }}</td>
                        <td class="text-muted" style="font-size:.82rem;" title="{{ $contact->created_at->format('d M Y H:i') }}">
                            {{ $contact->created_at->diffForHumans() }}
                        </td>
                        <td>
                            @if($contact->is_read)
                                <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Read</span>
                            @else
                                <span class="badge badge-primary"><i class="fas fa-circle mr-1" style="font-size:.5rem;"></i>New</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact) }}"
                               class="btn btn-xs btn-outline-primary">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($contacts->hasPages())
        <div class="card-footer">
            {{ $contacts->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

@endsection
