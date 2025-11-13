<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembercardController extends Controller
{
    public function index()
    {
        $downloads = Download::orderByDesc('created_at')->get();
        return view('admin.membercards.index', compact('downloads'));
    }

    public function logDownload(Request $request)
    {
        $data = $request->validate([
            'model_url' => 'required|string',
            'name' => 'required|string',
            'id' => 'required|string', // this is the code
            'angkatan' => 'nullable|string',
            'instansi' => 'nullable|string',
            'brand' => 'nullable|string',
            'filename' => 'nullable|string',
        ]);

        // Find existing record ONLY
        $download = Download::where('code', $data['id'])->first();

        // ❌ If not found, DO NOT create a new record
        if (!$download) {
            return response()->json([
                'message' => 'Download record not found — please contact admin.',
                'status' => false
            ], 404);
        }


        if (!$download->has_downloaded) {
            $download->has_downloaded = true;
            $download->downloaded_at = now();
            $download->save();
        }

        return response()->json([
            'message' => 'Download status updated successfully.',
            'status' => true
        ]);
    }



    public function show($code)
    {
        $download = Download::where('code', $code)->firstOrFail();
        return view('admin.membercards.show', compact('download'));
    }

    
    public function edit($code)
    {
        $download = Download::where('code', $code)->firstOrFail();

        $glbFiles = collect(Storage::disk('public')->files('models'))
            ->filter(fn($file) => str_ends_with($file, '.glb'))
            ->map(fn($file) => basename($file));

        return view('admin.membercards.edit', compact('download', 'glbFiles'));
    }

    public function update(Request $request, $code)
    {
        $download = Download::where('code', $code)->firstOrFail();

        // Validasi input utama
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'angkatan' => 'nullable|string|max:10',
            'instansi' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model_url' => 'nullable|string|max:255',
        ]);

        // ✅ Validasi dan proses upload .glb jika ada file diupload
        if ($request->hasFile('model_upload')) {
            $request->validate([
                'model_upload' => 'file|mimetypes:model/gltf-binary,application/octet-stream',
            ]);

            $file = $request->file('model_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('models', $filename, 'public');

            // Ganti model_url dengan path baru
            $validated['model_url'] = 'storage/models/' . $filename;
        }

        // ✅ Update code jika brand berubah
        if (!empty($validated['brand']) && $validated['brand'] !== $download->brand) {
            $prefix = (new \App\Models\User)->getBrandPrefix($validated['brand']);
            $angkaBelakang = substr($download->code, 2);
            $validated['code'] = $prefix . $angkaBelakang;
        } else {
            $validated['code'] = $download->code;
        }

        $download->update($validated);

        return redirect()->route('admin.membercards.show', $validated['code'])
            ->with('success', 'Data membercard berhasil diperbarui.');
    }



    public function destroy($code)
    {
        Download::where('code', $code)->delete();
        return redirect()->route('admin.membercards.index')
            ->with('success', 'Membercard deleted successfully.');
    }
}
