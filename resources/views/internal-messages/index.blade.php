@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-inbox"></i> Internal Messages</h2>
                <a href="{{ route('internal-messages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Compose New Message
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Recipients</th>
                                <th>Read Status</th>
                                <th>Attachments</th>
                                <th>Sent Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr>
                                    <td>
                                        <strong>{{ $message->subject }}</strong>
                                        @if($message->attachments)
                                            <i class="fas fa-paperclip text-muted ms-1"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $message->total_recipients }} students</span>
                                    </td>
                                    <td>
                                        @php
                                            $readCount = $message->total_recipients - $message->unread_count;
                                            $readPercentage = $message->total_recipients > 0
                                                ? round(($readCount / $message->total_recipients) * 100)
                                                : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: {{ $readPercentage }}%">
                                                    {{ $readPercentage }}%
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $readCount }}/{{ $message->total_recipients }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($message->attachments)
                                            <span class="badge bg-info">{{ count($message->attachments) }} files</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $message->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('internal-messages.show', $message->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4>No Messages Sent Yet</h4>
                    <p class="text-muted">Start communicating with your students by composing a new message.</p>
                    <a href="{{ route('internal-messages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Compose New Message
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
