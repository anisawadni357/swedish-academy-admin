<?php

namespace App\Services;

use App\Models\ResultatQuiz;
use App\Models\Product;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\Notification;
use App\Models\StudentSuccess;
use App\Models\HistoriqueQuiz;
use App\Mail\QuizPassed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ResultatQuizService
{
    public function index(Request $request): array
    {
        $query = ResultatQuiz::with(['student', 'product', 'quiz.type']);

        if ($request->filled('success')) {
            $query->where('success', $request->success);
        }

        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        if ($request->filled('quiz')) {
            $query->where('quiz_id', $request->quiz);
        }

        if ($request->filled('student')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->student . '%')
                  ->orWhere('last_name', 'like', '%' . $request->student . '%')
                  ->orWhere('email', 'like', '%' . $request->student . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $results = $query->orderBy('created_at', 'desc')->paginate(15);

        foreach ($results as $result) {
            $responses = HistoriqueQuiz::with(['question.reponses', 'response'])
                ->where('student_id', $result->student_id)
                ->where('quiz_id', $result->quiz_id)
                ->where('product_id', $result->product_id)
                ->whereNotNull('question_id')
                ->orderBy('attempt_number', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get();

            $result->responses = $responses;

            if ($responses->count() > 0) {
                $result->total_questions = $responses->count();
                $result->correct_answers = $responses->where('is_correct', true)->count();
            }
        }

        $products = Product::all();

        if ($request->filled('product')) {
            $quizzes = Quiz::whereHas('products', function ($q) use ($request) {
                $q->where('products.id', $request->product);
            })->get();
        } else {
            $quizzes = Quiz::all();
        }

        $stats = [
            'total'         => ResultatQuiz::count(),
            'successful'    => ResultatQuiz::where('success', true)->count(),
            'failed'        => ResultatQuiz::where('success', false)->count(),
            'average_score' => ResultatQuiz::avg('score') ?? 0,
        ];

        return compact('results', 'products', 'quizzes', 'stats');
    }

    public function getCreateData(): array
    {
        return [
            'students' => Student::all(),
            'products' => Product::all(),
            'quizzes'  => Quiz::all(),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'product_id' => 'required|exists:products,id',
            'quiz_id'    => 'required|exists:quizzes,id',
            'score'      => 'required|numeric|min:0|max:100',
            'success'    => 'boolean',
            'attempts'   => 'required|integer|min:1|max:10',
        ]);

        $hasAccess = DB::table('product_students')
            ->where('product_id', $request->product_id)
            ->where('student_id', $request->student_id)
            ->exists();

        if (!$hasAccess) {
            return back()->withErrors(['error' => 'Cet étudiant n\'a pas accès à ce cours.']);
        }

        $existingResult = ResultatQuiz::where('student_id', $request->student_id)
            ->where('product_id', $request->product_id)
            ->where('quiz_id', $request->quiz_id)
            ->first();

        if ($existingResult) {
            return back()->withErrors(['error' => 'Un résultat existe déjà pour cet étudiant et ce quiz.']);
        }

        $success = $request->has('success') ? $request->success : ($request->score >= 50);

        ResultatQuiz::create([
            'student_id' => $request->student_id,
            'product_id' => $request->product_id,
            'quiz_id'    => $request->quiz_id,
            'score'      => $request->score,
            'success'    => $success,
            'attempts'   => $request->attempts,
        ]);

        return redirect()->route('admin.resultat-quizzes.index')->with('success', 'Résultat créé avec succès.');
    }

    public function show(ResultatQuiz $resultatQuiz): array
    {
        $resultatQuiz->load(['student', 'product', 'quiz.type']);

        $responses = HistoriqueQuiz::with(['question.reponses', 'response'])
            ->where('student_id', $resultatQuiz->student_id)
            ->where('quiz_id', $resultatQuiz->quiz_id)
            ->where('product_id', $resultatQuiz->product_id)
            ->whereNotNull('question_id')
            ->orderBy('attempt_number', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        $totalQuestions  = $responses->count();
        $correctAnswers  = $responses->where('is_correct', true)->count();
        $incorrectAnswers = $totalQuestions - $correctAnswers;
        $scorePercentage  = $totalQuestions > 0
            ? round(($correctAnswers / $totalQuestions) * 100, 1)
            : $resultatQuiz->score;

        return compact('resultatQuiz', 'responses', 'totalQuestions', 'correctAnswers', 'incorrectAnswers', 'scorePercentage');
    }

    public function getEditData(ResultatQuiz $resultatQuiz): array
    {
        return [
            'resultatQuiz' => $resultatQuiz,
            'students'     => Student::all(),
            'products'     => Product::all(),
            'quizzes'      => Quiz::all(),
        ];
    }

    public function update(Request $request, ResultatQuiz $resultatQuiz)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'product_id' => 'required|exists:products,id',
            'quiz_id'    => 'required|exists:quizzes,id',
            'score'      => 'required|numeric|min:0|max:100',
            'success'    => 'boolean',
            'attempts'   => 'required|integer|min:1|max:10',
        ]);

        $hasAccess = DB::table('product_students')
            ->where('product_id', $request->product_id)
            ->where('student_id', $request->student_id)
            ->exists();

        if (!$hasAccess) {
            return back()->withErrors(['error' => 'Cet étudiant n\'a pas accès à ce cours.']);
        }

        $success         = $request->has('success') ? $request->success : ($request->score >= 50);
        $wasNotSuccessful = !$resultatQuiz->success;
        $willBeSuccessful = $success;

        $resultatQuiz->update([
            'student_id' => $request->student_id,
            'product_id' => $request->product_id,
            'quiz_id'    => $request->quiz_id,
            'score'      => $request->score,
            'success'    => $success,
            'attempts'   => $request->attempts,
        ]);

        $this->syncStudentExamAttempts(
            (int) $request->student_id,
            (int) $request->product_id,
            (int) $request->quiz_id,
            (int) $request->attempts
        );

        if ($wasNotSuccessful && $willBeSuccessful) {
            try {
                $resultatQuiz->load(['student', 'product', 'quiz.type']);
                Mail::to($resultatQuiz->student->email)->send(new QuizPassed($resultatQuiz));

                \App\Models\EmailLog::logSent(
                    $resultatQuiz->student->email,
                    'quiz_passed',
                    'Quiz Passed: ' . ($resultatQuiz->product->titre ?? 'N/A'),
                    $resultatQuiz->student->id,
                    ($resultatQuiz->student->first_name ?? '') . ' ' . ($resultatQuiz->student->last_name ?? ''),
                    'ResultatQuiz',
                    $resultatQuiz->id
                );

                return redirect()->route('admin.resultat-quizzes.index')
                    ->with('success', 'Result updated successfully and notification email sent to the student.');
            } catch (\Exception $e) {
                \App\Models\EmailLog::logFailed(
                    $resultatQuiz->student->email ?? 'unknown',
                    'quiz_passed',
                    'Quiz Passed: ' . ($resultatQuiz->product->titre ?? 'N/A'),
                    $e->getMessage(),
                    $resultatQuiz->student->id ?? null,
                    ($resultatQuiz->student->first_name ?? '') . ' ' . ($resultatQuiz->student->last_name ?? ''),
                    'ResultatQuiz',
                    $resultatQuiz->id
                );

                return redirect()->route('admin.resultat-quizzes.index')
                    ->with('success', 'Result updated successfully.')
                    ->with('warning', 'Error sending email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.resultat-quizzes.index')->with('success', 'Result updated successfully.');
    }

    public function destroy(ResultatQuiz $resultatQuiz)
    {
        try {
            $resultatQuiz->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
    }

    public function adminUpdateSuccess(Request $request, ResultatQuiz $resultatQuiz)
    {
        $request->validate(['success' => 'required|boolean']);

        try {
            $wasNotSuccessful = !$resultatQuiz->success;
            $willBeSuccessful = $request->success;

            $resultatQuiz->update(['success' => $request->success]);

            if ($wasNotSuccessful && $willBeSuccessful) {
                try {
                    $resultatQuiz->load(['student', 'product', 'quiz.type']);
                    Mail::to($resultatQuiz->student->email)->send(new QuizPassed($resultatQuiz));

                    \App\Models\EmailLog::logSent(
                        $resultatQuiz->student->email,
                        'quiz_passed',
                        'Quiz Passed: ' . ($resultatQuiz->product->titre ?? 'N/A'),
                        $resultatQuiz->student->id,
                        ($resultatQuiz->student->first_name ?? '') . ' ' . ($resultatQuiz->student->last_name ?? ''),
                        'ResultatQuiz',
                        $resultatQuiz->id
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Status updated successfully and notification email sent to the student.',
                    ]);
                } catch (\Exception $e) {
                    \App\Models\EmailLog::logFailed(
                        $resultatQuiz->student->email ?? 'unknown',
                        'quiz_passed',
                        'Quiz Passed: ' . ($resultatQuiz->product->titre ?? 'N/A'),
                        $e->getMessage(),
                        $resultatQuiz->student->id ?? null,
                        ($resultatQuiz->student->first_name ?? '') . ' ' . ($resultatQuiz->student->last_name ?? ''),
                        'ResultatQuiz',
                        $resultatQuiz->id
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Status updated successfully.',
                        'warning' => 'Error sending email: ' . $e->getMessage(),
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating status.']);
        }
    }

    public function resetAttempts(ResultatQuiz $resultatQuiz)
    {
        try {
            $resultatQuiz->update(['attempts' => 0]);

            $this->syncStudentExamAttempts(
                (int) $resultatQuiz->student_id,
                (int) $resultatQuiz->product_id,
                (int) $resultatQuiz->quiz_id,
                0
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la réinitialisation.']);
        }
    }

    public function markAsSuccess(ResultatQuiz $resultatQuiz)
    {
        try {
            DB::beginTransaction();

            $resultatQuiz->update([
                'score'    => 100,
                'success'  => true,
                'attempts' => $resultatQuiz->attempts + 1,
            ]);

            $existingSuccess = StudentSuccess::where('student_id', $resultatQuiz->student_id)
                ->where('product_id', $resultatQuiz->product_id)
                ->first();

            if (!$existingSuccess) {
                $studentSuccess = StudentSuccess::create([
                    'student_id'   => $resultatQuiz->student_id,
                    'product_id'   => $resultatQuiz->product_id,
                    'success'      => 1,
                    'admin_notes'  => 'Auto-approved: Quiz passed with 100% score',
                    'validated_at' => now(),
                ]);

                $student = Student::find($resultatQuiz->student_id);
                $product = Product::find($resultatQuiz->product_id);
                if ($student && $product) {
                    Notification::notifyAllAdmins(
                        Notification::TYPE_EVALUATION,
                        'Exam Passed - ' . $student->first_name . ' ' . $student->last_name,
                        $student->first_name . ' ' . $student->last_name . ' passed the exam for "' . $product->title . '"',
                        route('admin.student-successes.show', $studentSuccess->id),
                        ['student_success_id' => $studentSuccess->id, 'exam_type' => 'quiz'],
                        '✅',
                        'green',
                        true
                    );
                }
            } else {
                if ($existingSuccess->success == 0) {
                    $existingSuccess->update([
                        'success'      => 1,
                        'admin_notes'  => 'Auto-approved: Quiz passed with 100% score',
                        'validated_at' => now(),
                    ]);

                    $student = Student::find($resultatQuiz->student_id);
                    $product = Product::find($resultatQuiz->product_id);
                    if ($student && $product) {
                        Notification::notifyAllAdmins(
                            Notification::TYPE_EVALUATION,
                            'Exam Passed - ' . $student->first_name . ' ' . $student->last_name,
                            $student->first_name . ' ' . $student->last_name . ' passed the exam for "' . $product->title . '"',
                            route('admin.student-successes.show', $existingSuccess->id),
                            ['student_success_id' => $existingSuccess->id, 'exam_type' => 'quiz'],
                            '✅',
                            'green',
                            true
                        );
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quiz marked as 100% success and student success created/updated!',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function syncStudentExamAttempts(int $studentId, int $productId, int $quizId, int $attempts): void
    {
        $quiz = Quiz::find($quizId);
        if (!$quiz || (int) $quiz->type_id !== 1) {
            return;
        }

        $maxExamAttempts = Product::where('id', $productId)->value('max_exam_attempts') ?? 3;
        $isBlocked       = $attempts >= (int) $maxExamAttempts;

        $existingAttempt = DB::table('student_exam_attempts')
            ->where('student_id', $studentId)
            ->where('product_id', $productId)
            ->where('quiz_id', $quizId)
            ->first();

        $updateData = [
            'attempts'   => $attempts,
            'is_blocked' => $isBlocked,
            'updated_at' => now(),
        ];

        if ($isBlocked) {
            $updateData['blocked_at'] = $existingAttempt && $existingAttempt->is_blocked
                ? $existingAttempt->blocked_at
                : now();
        } else {
            $updateData['blocked_at']               = null;
            $updateData['renewal_email_sent_at']    = null;
        }

        if ($existingAttempt) {
            DB::table('student_exam_attempts')
                ->where('student_id', $studentId)
                ->where('product_id', $productId)
                ->where('quiz_id', $quizId)
                ->update($updateData);
            return;
        }

        DB::table('student_exam_attempts')->insert(array_merge($updateData, [
            'student_id' => $studentId,
            'product_id' => $productId,
            'quiz_id'    => $quizId,
            'created_at' => now(),
        ]));
    }
}
