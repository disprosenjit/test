@extends('admin.layout')

@section('title', $contact->full_name)
@section('heading', $contact->full_name)
@section('subheading', 'Received ' . $contact->created_at->format('d M Y \a\t H:i'))

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Back to Enquiries
    </a>
</div>

<div class="row">

    {{-- Left: Message + Project Details --}}
    <div class="col-lg-8">

        {{-- Message --}}
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title"><i class="fas fa-comment-alt mr-2"></i>Message</h3>
                @if($contact->is_read)
                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Read</span>
                @else
                    <span class="badge badge-primary"><i class="fas fa-circle mr-1" style="font-size:.5rem;vertical-align:middle;"></i>New</span>
                @endif
            </div>
            <div class="card-body">
                <div class="p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;white-space:pre-wrap;line-height:1.7;font-size:.92rem;">{{ $contact->message }}</div>
            </div>
        </div>

        {{-- Project Details --}}
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i>Project Details</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width:30%;">Service</th>
                            <td><span class="badge badge-info">{{ $contact->service_label }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Budget</th>
                            <td>{{ $contact->budget_label }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Received</th>
                            <td>{{ $contact->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Time Ago</th>
                            <td>{{ $contact->created_at->diffForHumans() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Right: Contact Info + Actions --}}
    <div class="col-lg-4">

        {{-- Contact Info --}}
        <div class="card card-outline card-success shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user mr-2"></i>Contact Information</h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white font-weight-bold mr-3 flex-shrink-0"
                          style="width:48px;height:48px;background:linear-gradient(135deg,#4f46e5,#7c3aed);font-size:1.2rem;">
                        {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                    </span>
                    <div>
                        <div class="font-weight-bold text-dark">{{ $contact->full_name }}</div>
                        <small class="text-muted">Enquiry #{{ $contact->id }}</small>
                    </div>
                </div>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted pl-0" style="width:30%;"><i class="fas fa-envelope mr-1"></i></td>
                        <td><a href="mailto:{{ $contact->email }}" class="text-primary">{{ $contact->email }}</a></td>
                    </tr>
                    @if($contact->phone)
                    <tr>
                        <td class="text-muted pl-0"><i class="fas fa-phone mr-1"></i></td>
                        <td><a href="tel:{{ $contact->phone }}" class="text-primary">{{ $contact->phone }}</a></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs mr-2"></i>Actions</h3>
            </div>
            <div class="card-body d-grid gap-2">

                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->service_label }} Enquiry — WebsolAI"
                   class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-reply mr-2"></i> Reply via Email
                </a>

                <form action="{{ route('admin.contacts.read', $contact) }}" method="POST" class="mb-2">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-secondary btn-block">
                        @if($contact->is_read)
                            <i class="fas fa-envelope mr-2"></i> Mark as Unread
                        @else
                            <i class="fas fa-envelope-open mr-2"></i> Mark as Read
                        @endif
                    </button>
                </form>

                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                      onsubmit="return confirm('Permanently delete this enquiry? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-block">
                        <i class="fas fa-trash-alt mr-2"></i> Delete Enquiry
                    </button>
                </form>
            </div>
        </div>

        {{-- Prev / Next Navigation --}}
        @php
            $prev = \App\Models\Contact::where('id', '<', $contact->id)->orderBy('id', 'desc')->first();
            $next = \App\Models\Contact::where('id', '>', $contact->id)->orderBy('id', 'asc')->first();
        @endphp
        @if($prev || $next)
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chevron-left mr-1"></i><i class="fas fa-chevron-right mr-2"></i>Navigate</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        @if($prev)
                        <a href="{{ route('admin.contacts.show', $prev) }}" class="btn btn-outline-secondary btn-block btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Previous
                        </a>
                        @endif
                    </div>
                    <div class="col">
                        @if($next)
                        <a href="{{ route('admin.contacts.show', $next) }}" class="btn btn-outline-secondary btn-block btn-sm">
                            Next <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
