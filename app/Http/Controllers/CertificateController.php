<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;


class CertificateController extends Controller
{
    public function downloadPdf(Certificate $certificate)
    {
        $filename = Str::slug($certificate->serial_number.'_'.$certificate->name, '_').'.pdf';

        $tmpPath  = storage_path('app/tmp/'.$filename);
        if (!is_dir(dirname($tmpPath))) {
            mkdir(dirname($tmpPath), 0775, true);
        }

        $brandMap = [
            'MJ'  => 'magangjogja',
            'AK'  => 'areakerja',
            'RW'  => 'republikweb',
            'TS'  => 'titipsini',
            'AP'  => 'ambilpaket',
            'BK'  => 'bikinkepo',
            'BC'  => 'bimbelcerdas.com',
            'LK'  => 'latihankerja.com',
            'LJT' => 'lowkerjateng.com',
            'LJG' => 'lowkerjogja.com',
            'PJ'  => 'pijatjogja.com',
            'SB'  => 'sayabantu.com',
            'TV'  => 'titikvisual',
            'TN'  => 'tuantanah',
            'TL'  => 'tukanglas.org',
            'AKI' => 'adakamar.id',
            'SI'  => 'seven Inc',
        ];

        $brandText = $brandMap[$certificate->brand] ?? $certificate->brand;
        $brandSlug = Str::slug($brandText, '-');
        $nameSlug  = Str::slug($certificate->name, '-');
        $filename  = "{$brandSlug}-{$nameSlug}.pdf";
        $pdfTitle  = "{$brandText}-{$certificate->name}";

        $html = View::make('certificates.pdf', [
            'certificate' => $certificate,
            'pdfTitle'    => $pdfTitle,
        ])->render();

        $tmpPath = storage_path('app/tmp/'.$filename);
        if (!is_dir(dirname($tmpPath))) mkdir(dirname($tmpPath), 0775, true);

        Browsershot::html($html)
            ->emulateMedia('print')
            ->format('A4')
            ->landscape()
            ->margins(0, 0, 0, 0)
            ->timeout(180) // detik
            ->setOption('args', [
                '--no-sandbox',
                '--disable-setuid-sandbox',
            ])
            ->savePdf($tmpPath);

        return response()->download($tmpPath, $filename, [
        'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }


    public function index()
    {
        $certificates = Certificate::orderByDesc('id')->get();
        return view('certificates.index', compact('certificates'));
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
            // serial_number auto-generate
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

        // Pastikan file ada
        foreach ([$backgroundPath, $logo1Path, $sig1Path, $logo2Path, $sig2Path] as $checkPath) {
            if ($checkPath && !Storage::exists("public/{$checkPath}")) {
                return back()->withErrors(['file_missing' => "File tidak ditemukan: {$checkPath}"])->withInput();
            }
        }

        // Tanggal
        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);

        // Map bulan ke romawi berdasarkan END DATE
        $roman = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        $monthRoman = $roman[$endDate->month];
        $year       = $endDate->year;

        // Codes
        $companyCode  = $this->companyCode($data['company']); // contoh: "SEVEN"
        $brandCode    = strtoupper($data['brand']);
        $divisionCode = strtoupper($data['division']);

        // Running number 3 digit per bulan-tahun (reset tiap bulan)
        $last = Certificate::whereYear('created_at', $endDate->year)
                ->whereMonth('created_at', $endDate->month)
                ->orderByDesc('id')
                ->first();

        $seq = 1;
        if ($last && preg_match('/^(\d{3})\/SERT\//', $last->serial_number, $m)) {
            $seq = (int)$m[1] + 1;
        }
        $seqStr = str_pad($seq, 3, '0', STR_PAD_LEFT);

        // >>> Serial format BARU: NNN/SERT/DIV/COMP.BRAND/ROMAWI/TAHUN
        $serial = "{$seqStr}/SERT/{$divisionCode}/{$companyCode}.{$brandCode}/{$monthRoman}/{$year}";

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
        $certificate = Certificate::findOrFail($id);
        return view('certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        // Ambil file dari storage (hanya nama file) + filter prefix
        $backgroundFiles = collect(Storage::files('public/images/backgrounds'))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => Str::startsWith($f, 'bg_'))
            ->values();

        $logoFiles = collect(Storage::files('public/images/logos'))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => Str::startsWith($f, 'logo_'))
            ->values();

        $signatureFiles = collect(Storage::files('public/images/signature'))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => Str::startsWith($f, 'ttd_'))
            ->values();

        // Pastikan nilai yang tersimpan tetap ada di opsi (kalau file-nya sudah pindah/hilang dari folder)
        $savedBg   = basename($certificate->background_image ?? '');
        $savedL1   = basename($certificate->logo1 ?? '');
        $savedL2   = basename($certificate->logo2 ?? '');
        $savedTtd1 = basename($certificate->signature_image1 ?? '');
        $savedTtd2 = basename($certificate->signature_image2 ?? '');

        if ($savedBg && !$backgroundFiles->contains($savedBg))   $backgroundFiles->prepend($savedBg);
        if ($savedL1 && !$logoFiles->contains($savedL1))         $logoFiles->prepend($savedL1);
        if ($savedL2 && $savedL2 !== '' && !$logoFiles->contains($savedL2)) $logoFiles->prepend($savedL2);
        if ($savedTtd1 && !$signatureFiles->contains($savedTtd1)) $signatureFiles->prepend($savedTtd1);
        if ($savedTtd2 && $savedTtd2 !== '' && !$signatureFiles->contains($savedTtd2)) $signatureFiles->prepend($savedTtd2);

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

        return view('certificates.edit', compact(
            'certificate', 'backgroundFiles', 'logoFiles', 'signatureFiles', 'divisions', 'brands'
        ));
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

    /**
     * Upload helper umum: validasi mime/size, auto-rename dengan prefix, simpan ke disk public
     */
    private function uploadCommon(Request $request, string $prefix, string $subdir)
    {
        $request->validate([
            'file' => ['required','file','mimes:png,jpg,jpeg,webp','max:2048'], // max 2MB
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        // Ambil nama dasar tanpa ekstensi, lalu slug pakai underscore
        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($base, '_');
        // Buang prefix jika user sudah menamai dengan bg_/logo_/ttd_
        $slug = preg_replace('/^(bg_|logo_|ttd_)/i', '', $slug);

        $timestamp = now()->format('Ymd_His');
        $filename  = $prefix.$slug.'_'.$timestamp.'.'.$ext;

        $dir = "public/images/{$subdir}"; // contoh: public/images/backgrounds
        // Pastikan folder ada (umumnya sudah ada pada disk public). Jika belum, buat.
        if (!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
        }

        $file->storeAs($dir, $filename);

        return back()->with('success', "File diunggah: {$filename}");
    }

    /** Upload Background → simpan ke storage/app/public/images/backgrounds, prefix bg_ */
    public function uploadBackground(Request $request)
    {
        return $this->uploadCommon($request, 'bg_', 'backgrounds');
    }

    /** Upload Logo → simpan ke storage/app/public/images/logos, prefix logo_ */
    public function uploadLogo(Request $request)
    {
        return $this->uploadCommon($request, 'logo_', 'logos');
    }

    /** Upload Tanda Tangan → simpan ke storage/app/public/images/signature, prefix ttd_ */
    public function uploadSignature(Request $request)
    {
        return $this->uploadCommon($request, 'ttd_', 'signature');
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
