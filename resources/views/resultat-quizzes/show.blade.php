@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Quiz Result Details</h1>
                    <p class="text-muted mb-0">Complete information about result #{{ $resultatQuiz->id }}</p>
                </div>
                <a href="{{ route('admin.resultat-quizzes.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i> Back to List
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $scorePercentage }}%</h4>
                                    <small>Score</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-chart-pie fs-2 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $correctAnswers }}</h4>
                                    <small>Correct Answers</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-check-circle fs-2 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $incorrectAnswers }}</h4>
                                    <small>Incorrect Answers</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-times-circle fs-2 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalQuestions }}</h4>
                                    <small>Total Questions</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-question-circle fs-2 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Details Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="border rounded p-4">
                        <!-- Result Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fa fa-clipboard-list text-primary me-2"></i>
                                    {{ $resultatQuiz->quiz->type->name ?? 'Quiz' }}:
                                    {{ $resultatQuiz->quiz->name_en ?? 'Quiz #' . $resultatQuiz->quiz_id }}
                                </h5>
                                <p class="text-muted mb-1">
                                    <i class="fa fa-book me-1"></i>
                                    {{ $resultatQuiz->product->titre ?? 'Course not found' }}
                                </p>
                                <small class="text-muted">
                                    <i class="fa fa-clock me-1"></i>
                                    Completed: {{ $resultatQuiz->created_at->format('d/m/Y H:i') }}
                                    @if($resultatQuiz->attempts > 1)
                                        | Attempt #{{ $resultatQuiz->attempts }}
                                    @endif
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $resultatQuiz->success ? 'success' : 'danger' }} fs-5 mb-2">
                                    {{ $scorePercentage }}%
                                </span>
                                <br>
                                <span class="badge bg-{{ $resultatQuiz->success ? 'success' : 'danger' }}">
                                    {{ $resultatQuiz->success ? 'PASSED' : 'FAILED' }}
                                </span>
                            </div>
                        </div>

                        <!-- Student Info -->
                        <div class="row mb-3 py-3 bg-light rounded">
                            <div class="col-md-4">
                                <small class="text-muted d-block">Student Name</small>
                                <strong>
                                    <i class="fa fa-user me-1"></i>
                                    {{ $resultatQuiz->student->full_name ?? $resultatQuiz->student->first_name . ' ' . $resultatQuiz->student->last_name }}
                                </strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Email</small>
                                <strong>
                                    <i class="fa fa-envelope me-1"></i>
                                    {{ $resultatQuiz->student->email }}
                                </strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Country</small>
                                <strong>
                                    <i class="fa fa-globe me-1"></i>
                                    {{ $resultatQuiz->student->country->name ?? 'Not specified' }}
                                </strong>
                            </div>
                        </div>

                        <!-- Results Summary -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <small class="text-muted">Total Questions:</small>
                                <strong class="ms-2">{{ $totalQuestions }}</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Correct Answers:</small>
                                <strong class="ms-2 text-success">{{ $correctAnswers }}</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Incorrect Answers:</small>
                                <strong class="ms-2 text-danger">{{ $incorrectAnswers }}</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Pass Threshold:</small>
                                <strong class="ms-2">50%</strong>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Score Progress</small>
                                <small class="text-muted">{{ $scorePercentage }}%</small>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $resultatQuiz->success ? 'bg-success' : 'bg-danger' }}"
                                     role="progressbar"
                                     style="width: {{ $scorePercentage }}%"
                                     aria-valuenow="{{ $scorePercentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ $scorePercentage }}%
                                </div>
                            </div>
                        </div>

                        <!-- Question Details Accordion -->
                        @if($responses && $responses->count() > 0)
                            <div class="accordion" id="accordionResponses">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingResponses">
                                        <button class="accordion-button" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseResponses"
                                                aria-expanded="true"
                                                aria-controls="collapseResponses">
                                            <i class="fa fa-eye me-2"></i>
                                            View Question Details ({{ $responses->count() }} Questions)
                                        </button>
                                    </h2>
                                    <div id="collapseResponses"
                                         class="accordion-collapse collapse show"
                                         aria-labelledby="headingResponses"
                                         data-bs-parent="#accordionResponses">
                                        <div class="accordion-body">
                                            @foreach($responses as $response)
                                                <div class="border rounded p-3 mb-3 {{ $response->is_correct ? 'border-success bg-success-light' : 'border-danger bg-danger-light' }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-1">
                                                            <span class="badge {{ $response->is_correct ? 'bg-success' : 'bg-danger' }} me-2">
                                                                Q{{ $loop->iteration }}
                                                            </span>
                                                            {!! $response->question->question ?? 'Question not found' !!}
                                                        </h6>
                                                        <span class="badge bg-{{ $response->is_correct ? 'success' : 'danger' }}">
                                                            {{ $response->is_correct ? 'Correct' : 'Incorrect' }}
                                                        </span>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Student's Answer:</small>
                                                            <div class="fw-bold {{ $response->is_correct ? 'text-success' : 'text-danger' }}">
                                                                <i class="fa {{ $response->is_correct ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                                {{ $response->response->reponse ?? 'Answer not found' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Correct Answer:</small>
                                                            <div class="fw-bold text-success">
                                                                <i class="fa fa-check-circle me-1"></i>
                                                                @if($response->question)
                                                                    @php
                                                                        $correctAnswer = $response->question->reponses()->where('is_correcte', true)->first();
                                                                    @endphp
                                                                    {{ $correctAnswer ? $correctAnswer->reponse : 'Correct answer not found' }}
                                                                @else
                                                                    Question not found
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fa fa-info-circle me-2"></i>
                                No detailed question responses available for this result.
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <a href="{{ route('admin.resultat-quizzes.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i> Back to List
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.resultat-quizzes.edit', $resultatQuiz) }}" class="btn btn-warning">
                                <i class="fa fa-edit me-2"></i> Edit
                            </a>
                            <form action="{{ route('admin.resultat-quizzes.destroy', $resultatQuiz) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this result?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash me-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-danger-light {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.card {
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #212529;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
    font-weight: 600;
}

.badge {
    font-weight: 500;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.opacity-75 {
    opacity: 0.75;
}
</style>
@endsection
