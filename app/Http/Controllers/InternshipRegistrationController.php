<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration;
use Illuminate\Http\Request;

class InternshipRegistrationController extends Controller
{
    // Menampilkan tabel data
    public function index()
    {
        $registrations = InternshipRegistration::all();
        return view('pages.internship.table', compact('registrations'));
    }

    // Menampilkan form input
    public function create()
    {
        return view('pages.internship.form-page');
    }

    // Menyimpan data dari form
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'born_date' => 'required|string|max:255',
            'student_id' => 'required|string|max:50',
            'email' => 'required|string|max:255',
            'gender' => 'required|string|max:50',
            'phone_number' => 'required|string|max:20',
            'institution_name' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'current_city' => 'required|string|max:255',
            'internship_reason' => 'required|string',
            'internship_type' => 'required|string|max:50',
            'internship_arrangement' => 'required|string|max:50',
            'current_status' => 'required|string|max:50',
            'english_book_ability' => 'required|string|max:50',
            'supervisor_contact' => 'nullable|string|max:255',
            'internship_interest' => 'required|string|max:255',
            'internship_interest_other' => 'nullable|string|max:255',
            'design_software' => 'nullable|string|max:255',
            'video_software' => 'nullable|string|max:255',
            'programming_languages' => 'nullable|string|max:255',
            'digital_marketing_type' => 'nullable|string|max:255',
            'digital_marketing_type_other' => 'nullable|string|max:255',
            'laptop_equipment' => 'nullable|string|max:50',
            'owned_tools' => 'nullable|string|max:255',
            'owned_tools_other' => 'nullable|string|max:255',
            'start_date' => 'nullable|string|max:50',
            'end_date' => 'nullable|string|max:50',
            'internship_info_sources' => 'nullable|string|max:255',
            'internship_info_other' => 'nullable|string|max:255',
            'current_activities' => 'nullable|string|max:255',
            'boarding_info' => 'nullable|string|max:50',
            'family_status' => 'required|string|max:50',
            'parent_wa_contact' => 'nullable|string|max:30',
            'social_media_instagram' => 'nullable|string|max:255',
        ]);

        InternshipRegistration::create($validated);

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}
