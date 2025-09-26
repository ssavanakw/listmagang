<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CertificateController extends Controller
{
    public function index()
    {
        $certificate = Certificate::orderByDesc('id')->first();
        return view('certificates.index', compact('certificate'));
    }

    public function create()
    {
        // List file dari storage (hanya nama file)
        $backgroundFiles = collect(Storage::files('public/images/backgrounds'))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => str_starts_with($f, 'bg_'))
            ->values();

        $logoFiles = collect(Storage::files('public/images/logos'))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => str_starts_with($f, 'logo_'))
            ->values();

        $signatureFiles = collect(Storage::files('public/images/signature'))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => str_starts_with($f, 'ttd_'))
            ->values();

        // Divisi (kode => label)
        $divisions = [
            'ADM'  => 'Administrasi',
            'UIUX' => 'UI/UX Designer',
            'PROG' => 'Programmer (Front end / Back end)',
            'HR'   => 'Human Resource',
            'SMM'  => 'Social Media Specialist',
            'PV'   => 'Photographer / Videographer',
            'CW'   => 'Content Writer',
            'MS'   => 'Marketing & Sales',
            'CD'   => 'Content Creative (Desain Grafis)',
            'DM'   => 'Digital Marketing',
            'PR'   => 'Marcom/Public Relations',
            'TC'   => 'Tik Tok Creator',
            'CP'   => 'Content Planner',
            'PM'   => 'Project Manager',
            'LAS'  => 'Las',
            'ANIM' => 'Animasi',
        ];

        // Brand (kode => label)
        $brands = [
            'MJ'  => 'Magangjogja',
            'AK'  => 'Areakerja',
            'RW'  => 'Republikweb',
            'TS'  => 'Titipsini',
            'AP'  => 'Ambilpaket',
            'BK'  => 'Bikinkepo',
            'BC'  => 'Bimbelcerdas.com',
            'LK'  => 'Latihankerja.com',
            'LJT' => 'Lowkerjateng.com',
            'LJG' => 'Lowkerjogja.com',
            'PJ'  => 'Pijatjogja.com',
            'SB'  => 'Sayabantu.com',
            'TV'  => 'Titikvisual',
            'TN'  => 'Tuantanah',
            'TL'  => 'Tukanglas.org',
            'AKI' => 'Adakamar.id',
            'SI'  => 'Seven Inc',
        ];

        return view('certificates.create', compact(
            'backgroundFiles', 'logoFiles', 'signatureFiles', 'divisions', 'brands'
        ));
    }



    public function store(Request $request)
    {
        // List kode yang valid
        $validDivisions = ['ADM','UIUX','PROG','HR','SMM','PV','CW','MS','CD','DM','PR','TC','CP','PM','LAS','ANIM'];
        $validBrands    = ['MJ','AK','RW','TS','AP','BK','BC','LK','LJT','LJG','PJ','SB','TV','TN','TL','AKI','SI'];

        // Validasi
        $request->validate([
            'name'             => ['required','string','max:255'],
            'division'         => ['required', Rule::in($validDivisions)],
            'company'          => ['required','string','max:255'],
            // dropdown file (bukan upload), wajib prefix tertentu
            'background_image' => ['required','string','starts_with:bg_'],
            'logo1'            => ['required','string','starts_with:logo_'],
            'logo2'            => ['nullable','string','starts_with:logo_'],
            'signature_image1' => ['required','string','starts_with:ttd_'],
            'signature_image2' => ['nullable','string','starts_with:ttd_'],

            'start_date'       => ['required','date','before_or_equal:end_date'],
            'end_date'         => ['required','date','after_or_equal:start_date'],

            'city'             => ['required','string','max:255'],
            'brand'            => ['required', Rule::in($validBrands)],
            // serial_number jangan divalidasi dari request, karena auto-generate
            'name_signatory1'  => ['required','string','max:255'],
            'name_signatory2'  => ['nullable','string','max:255'],
            'role1'            => ['required','string','max:255'],
            'role2'            => ['nullable','string','max:255'],
        ]);

        $data = $request->all();

        // Normalisasi path (disimpan relatif dari disk 'public')
        $backgroundPath = "images/backgrounds/{$data['background_image']}";
        $logo1Path      = "images/logos/{$data['logo1']}";
        $logo2Path      = $data['logo2'] ? "images/logos/{$data['logo2']}" : null;
        $sig1Path       = "images/signature/{$data['signature_image1']}";
        $sig2Path       = $data['signature_image2'] ? "images/signature/{$data['signature_image2']}" : null;

        // Opsional: pastikan file benar-benar ada di storage
        foreach ([
            $backgroundPath, $logo1Path, $sig1Path,
            $logo2Path, $sig2Path
        ] as $checkPath) {
            if ($checkPath && !Storage::exists("public/{$checkPath}")) {
                return back()
                    ->withErrors(['file_missing' => "File tidak ditemukan: {$checkPath}"])
                    ->withInput();
            }
        }

        // Tanggal
        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);

        // Map bulan ke romawi berdasarkan END DATE (bulan sertifikat dibuat)
        $roman = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        $monthRoman = $roman[$endDate->month];
        $year       = $endDate->year;

        // Company code: ambil kata pertama, buang PT/CV/co/ltd/inc/tbk/persero, uppercase alnum
        $companyCode = $this->companyCode($data['company']); // contoh: "SEVEN"

        // Brand sudah berupa kode dari dropdown (MJ, AK, dst.)
        $brandCode = strtoupper($data['brand']);

        // Ambil running number 3 digit per bulan-tahun (reset tiap bulan)
        $last = Certificate::whereYear('created_at', $endDate->year)
                ->whereMonth('created_at', $endDate->month)
                ->orderByDesc('id')
                ->first();

        $seq = 1;
        if ($last && preg_match('/^(\d{3})\/SERT\//', $last->serial_number, $m)) {
            $seq = (int)$m[1] + 1;
        }
        $seqStr = str_pad($seq, 3, '0', STR_PAD_LEFT);

        // Serial format: NNN/SERT/COMP.BRAND/ROMAWI/TAHUN
        $serial = "{$seqStr}/SERT/{$companyCode}.{$brandCode}/{$monthRoman}/{$year}";

        // Simpan
        Certificate::create([
            'name'              => $data['name'],
            'division'          => $data['division'],  // simpan kode divisi
            'company'           => $data['company'],
            'background_image'  => $backgroundPath,
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'city'              => $data['city'],
            'brand'             => $brandCode,         // simpan kode brand
            'serial_number'     => $serial,
            'logo1'             => $logo1Path,
            'logo2'             => $logo2Path,
            'signature_image1'  => $sig1Path,
            'signature_image2'  => $sig2Path,
            'name_signatory1'   => $data['name_signatory1'],
            'name_signatory2'   => $data['name_signatory2'],
            'role1'             => $data['role1'],
            'role2'             => $data['role2'],
        ]);

        return redirect()->route('certificate.index')->with('success', 'Sertifikat berhasil dibuat');
    }

    /**
     * Normalisasi kode company dari nama perusahaan.
     * - Buang PT/CV/CO/LTD/INC/TBK/PERSERO
     * - Ambil kata pertama, uppercase, alnum only
     */
    private function companyCode(string $company): string
    {
        $t = strtoupper($company);
        $t = preg_replace('/\b(PT|CV|CO\.?|LTD\.?|INC\.?|TBK|PERSERO)\b\.?/i', '', $t);
        $t = trim($t);
        $first = preg_split('/\s+/', $t)[0] ?? $t;
        $first = preg_replace('/[^A-Z0-9]/', '', $first);
        return $first ?: 'COMP';
    }



    public function show($id)
    {
        // Menampilkan detail sertifikat
        $certificate = Certificate::findOrFail($id);
        return view('certificates.show', compact('certificate'));
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit sertifikat
        $certificate = Certificate::findOrFail($id);
        return view('certificates.edit', compact('certificate'));
    }

    private function getBrandCode($brand)
    {
        $brandCodes = [
            'Magangjogja'               => 'MJ',
            'Areakerja'                 => 'AK',
            'Republikweb'               => 'RW',
            'Titipsini'                 => 'TS',
            'Ambilpaket'                => 'AP',
            'Bikinkepo'                 => 'BK',
            'Bimbelcerdas.com'          => 'BC',
            'Latihankerja.com'          => 'LK',
            'Lowkerjateng.com'          => 'LJT',
            'Lowkerjogja.com'           => 'LJG',
            'Pijatjogja.com'            => 'PJ',
            'Sayabantu.com'             => 'SB',
            'Titikvisual'               => 'TV',
            'Tuantanah'                 => 'TN',
            'Tukanglas.org'             => 'TL',
            'Adakamar.id'               => 'AKI',
            'Seven Inc'                 => 'SI',
        ];

        return $brandCodes[$brand] ?? 'UNKNOWN';
    }


    public function update(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'name'              => 'required|string|max:255',
            'division'          => 'required|string|max:255',
            'company'           => 'required|string|max:255',
            'background_image'  => 'nullable|image|max:2048',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date',
            'city'              => 'required|string|max:255',
            'brand'             => 'required|string|max:255',
            'serial_number'     => 'required|string|max:255|unique:certificates,serial_number,' . $id,
            'logo1'             => 'nullable|image|max:2048',
            'logo2'             => 'nullable|image|max:2048',
            'signature_image1'  => 'nullable|image|max:2048',
            'signature_image2'  => 'nullable|image|max:2048',
            'name_signatory1'   => 'required|string|max:255',
            'name_signatory2'   => 'nullable|string|max:255',
            'role1'             => 'required|string|max:255',
            'role2'             => 'nullable|string|max:255',
        ]);

        // Ambil data sertifikat
        $certificate = Certificate::findOrFail($id);

        // Hapus file lama jika ada dan upload yang baru
        if ($request->hasFile('background_image')) {
            Storage::delete($certificate->background_image);
            $certificate->background_image = $request->file('background_image')->store('backgrounds');
        }
        if ($request->hasFile('logo1')) {
            Storage::delete($certificate->logo1);
            $certificate->logo1 = $request->file('logo1')->store('logos');
        }
        if ($request->hasFile('logo2')) {
            Storage::delete($certificate->logo2);
            $certificate->logo2 = $request->file('logo2')->store('logos');
        }
        if ($request->hasFile('signature_image1')) {
            Storage::delete($certificate->signature_image1);
            $certificate->signature_image1 = $request->file('signature_image1')->store('signatures');
        }
        if ($request->hasFile('signature_image2')) {
            Storage::delete($certificate->signature_image2);
            $certificate->signature_image2 = $request->file('signature_image2')->store('signatures');
        }

        // Update data sertifikat
        $certificate->update([
            'name'              => $request->name,
            'division'          => $request->division,
            'company'           => $request->company,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'city'              => $request->city,
            'brand'             => $request->brand,
            'serial_number'     => $request->serial_number,
            'name_signatory1'   => $request->name_signatory1,
            'name_signatory2'   => $request->name_signatory2,
            'role1'             => $request->role1,
            'role2'             => $request->role2,
        ]);

        return redirect()->route('certificate.index')->with('success', 'Sertifikat berhasil diupdate');
    }

    public function destroy($id)
    {
        // Hapus sertifikat
        $certificate = Certificate::findOrFail($id);
        Storage::delete($certificate->background_image);
        Storage::delete($certificate->logo1);
        Storage::delete($certificate->logo2);
        Storage::delete($certificate->signature_image1);
        Storage::delete($certificate->signature_image2);

        $certificate->delete();

        return redirect()->route('certificate.index')->with('success', 'Sertifikat berhasil dihapus');
    }
}
