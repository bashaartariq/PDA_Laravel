<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GeneratePdfjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){  
        Log::info($this->data);
        $doctorId = $this->data['DoctorId'];
        $pdfFileName = 'case_report_doctor_' . $doctorId . '.pdf';
        $pdf = FacadePdf::loadView('pdf.case_report', $this->data);
        $pdf->save(storage_path('app/public/' . $pdfFileName));
    }}