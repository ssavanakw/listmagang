<?php

// app/Http/Controllers/MembercardController.php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;

class MembercardController extends Controller
{

    public function index()
    {
        // Fetch all downloads data
        $downloads = Download::all(); // You can filter or paginate as needed

        return view('admin.membercards.index', compact('downloads'));
    }
    public function downloadMembercard(Request $request)
    {
        // Assuming you already have user info and download file path logic
        $user = auth()->user();  // Or get user from request

        // Save download record
        $download = Download::create([
            'name' => $user->name,  // User's name
            'user_id' => $user->id,  // User's ID
            'angkatan' => $user->angkatan,  // Angkatan
            'instansi' => $user->instansi,  // Instansi
            'brand' => 'magangjogja.com',  // Brand or other info
            'has_downloaded' => true,  // Status: download completed
            'downloaded_at' => now(),  // Timestamp of download
        ]);

        // You can return the download file as well:
        return response()->download(storage_path('path_to_file/membercard.png'));
    }

    // app/Http/Controllers/MembercardController.php
    public function logDownload(Request $request)
    {
        // Validate and log the download information
        $data = $request->validate([
            'model_url' => 'required|string',
            'name' => 'required|string',
            'id' => 'required|string',
            'angkatan' => 'nullable|string',
            'instansi' => 'nullable|string',
            'brand' => 'nullable|string',
        ]);

        // Store download log in the database
        Download::create([
            'name' => $data['name'],
            'user_id' => $data['id'],
            'angkatan' => $data['angkatan'],
            'instansi' => $data['instansi'],
            'brand' => $data['brand'],
            'has_downloaded' => true,
            'downloaded_at' => now(),
        ]);

        return response()->json(['message' => 'Download logged successfully']);
    }

    public function show($id)
    {
        // Fetch the membercard by its ID or code (replace with your actual logic)
        $download = Download::findOrFail($id);

        // Pass the download data to the view
        return view('admin.membercards.show', compact('download'));
    }

    public function store(Request $request)
    {
        // Validate the request (file is required)
        $request->validate([
            'membercard' => 'required|file|mimes:png,jpg,jpeg|max:2048',  // You can set the max file size as per your requirement
        ]);

        // Store the file in public/downloads folder
        $filePath = $request->file('membercard')->store('public/downloads');

        // Get the file name
        $filename = basename($filePath);

        // Save the file details in the database (assuming a 'downloads' table)
        $download = new Download();
        $download->filename = $filename;
        $download->user_id = auth()->id();  // If the user is logged in
        $download->name = auth()->user()->name;
        $download->save();

        // Redirect or return the download page
        return redirect()->route('admin.membercard.details', $download->id)
                         ->with('success', 'File uploaded successfully!');
    }

    public function destroy($id)
    {
        // Find the download by its ID
        $download = Download::findOrFail($id);

        // Delete the download record
        $download->delete();

        // Redirect back with a success message
        return redirect()->route('admin.membercards.index')->with('success', 'Membercard deleted successfully.');
    }

}
