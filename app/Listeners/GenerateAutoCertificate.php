<?php

namespace App\Listeners;

use App\Events\StudentSuccessApproved;
use App\Services\CertificateGeneratorService;
use App\Mail\CertificateGeneratedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GenerateAutoCertificate implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StudentSuccessApproved $event): void
    {
        $studentSuccess = $event->studentSuccess;
        $product = $studentSuccess->product;

        // Debug logging to check why automatic mode might not be working
        Log::info('GenerateAutoCertificate listener triggered', [
            'student_success_id' => $studentSuccess->id,
            'product_id' => $product->id,
            'product_name' => $product->titre ?? 'N/A',
            'certif_id' => $product->certif_id,
            'certificate_generation_mode' => $product->certificate_generation_mode,
            'has_certif' => !empty($product->certif_id),
            'is_automatic' => $product->certificate_generation_mode === 'automatic'
        ]);

        // Check if certificate generation mode is automatic and product has certificate template
        if ($product->certif_id && $product->certificate_generation_mode === 'automatic') {
            try {
                $certificateService = new CertificateGeneratorService();

                // Check if certificate already exists
                if (!$certificateService->certificateExists($studentSuccess)) {
                    // Generate certificate automatically
                    $certificate = $certificateService->generateCertificate($studentSuccess);

                    Log::info('Certificate generated automatically', [
                        'student_success_id' => $studentSuccess->id,
                        'student_id' => $studentSuccess->student_id,
                        'product_id' => $studentSuccess->product_id,
                        'certificate_id' => $certificate->id,
                        'serial_number' => $certificate->serial_number
                    ]);

                    // Send email to student with certificate
                    try {
                        Mail::to($studentSuccess->student->email)
                            ->send(new CertificateGeneratedNotification($certificate, $studentSuccess));

                        Log::info('Certificate notification email sent', [
                            'student_email' => $studentSuccess->student->email,
                            'certificate_id' => $certificate->id
                        ]);
                    } catch (\Exception $emailError) {
                        Log::error('Failed to send certificate notification email', [
                            'error' => $emailError->getMessage(),
                            'student_email' => $studentSuccess->student->email
                        ]);
                    }
                } else {
                    Log::info('Certificate already exists, skipping automatic generation', [
                        'student_success_id' => $studentSuccess->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to generate certificate automatically', [
                    'error' => $e->getMessage(),
                    'student_success_id' => $studentSuccess->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }
}
