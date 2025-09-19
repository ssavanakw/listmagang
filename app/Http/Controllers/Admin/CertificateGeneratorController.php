<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class CertificateGeneratorController extends Controller
{
    // Show the certificate form
    public function showForm()
    {
        return view('certificates.generator_form');
    }

    // Generate the certificate preview based on input data
    public function generatePreview(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'division' => 'required|string',
            'company' => 'required|string',
            'background_image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'city' => 'required|string',
            'logo1' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'logo2' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'role1' => 'required|string',
            'signature_image1' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'role2' => 'required|string',
            'signature_image2' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'name_signatory1' => 'required|string',
            'name_signatory2' => 'required|string',
        ]);

        // Store the images in the 'public' directory
        $background = $request->file('background_image')->store('backgrounds', 'public');
        $logo1 = $request->file('logo1')->store('logos', 'public');
        $logo2 = $request->file('logo2') ? $request->file('logo2')->store('logos', 'public') : null;
        $signature_image1 = $request->file('signature_image1')->store('signatures', 'public');
        $signature_image2 = $request->file('signature_image2')->store('signatures', 'public');

        // Calculate the duration between start and end dates
        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        $durationText = $this->formatDurationId($startDate, $endDate);

        // Return the preview view with the validated data and calculated duration
        return view('certificates.generator_preview', [
            'name' => $validatedData['name'],
            'division' => $validatedData['division'],
            'company' => $validatedData['company'],
            'background_image' => $background,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'city' => $validatedData['city'],
            'logo1' => $logo1,
            'logo2' => $logo2,
            'role1' => $validatedData['role1'],
            'signature_image1' => $signature_image1,
            'role2' => $validatedData['role2'],
            'signature_image2' => $signature_image2,
            'name_signatory1' => $validatedData['name_signatory1'],
            'name_signatory2' => $validatedData['name_signatory2'],
            'duration_text' => $durationText,
        ]);
    }

    // Generate the PDF for the certificate
    public function generatePDF(Request $request)
    {
        $data = $request->all();

        // Calculate the duration: months + days (match with preview)
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $data['duration_text'] = $this->formatDurationId($startDate, $endDate);

        // Render the same view for the PDF
        $html = view('certificates.generator_preview', $data)->render();

        // Generate PDF using Browsershot
        $pdfContent = Browsershot::html($html)
            ->waitUntilNetworkIdle()
            ->setOption('no-sandbox', true)
            ->pdf();

        // Store the generated PDF in the 'certificates' directory
        $filePath = 'certificates/' . uniqid() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);

        // Return the PDF as a download response
        return response()->download(storage_path('app/public/' . $filePath));
    }

    // Helper function to format the duration (in months and days)
    private function formatDurationId(Carbon $startDate, Carbon $endDate): string
    {
        $interval = $startDate->diff($endDate);
        $months = $interval->y * 12 + $interval->m;
        $days = $interval->d;

        $parts = [];
        if ($months > 0) $parts[] = $months . ' bulan';
        if ($days > 0) $parts[] = $days . ' hari';

        return $parts ? implode(' ', $parts) : '0 hari';
    }
}
