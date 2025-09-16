<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class CertificatePdf
{
    /**
     * Render Blade view -> embed gambar -> generate & download PDF.
     *
     * @param  string $view         ex: 'certificates.certmagangjogjacom'
     * @param  array  $data         data teks & src gambar (opsional)
     * @param  string $downloadName ex: 'Sertifikat_Budi.pdf'
     * @param  array  $browsershot  opsi tambahan (opsional)
     */
    public function download(string $view, array $data, string $downloadName, array $browsershot = [])
    {
        // 1) Render Blade ke HTML
        $html = view($view, $data)->render();

        // 2) Embed semua gambar ke base64
        $html = $this->embedAllImagesToBase64($html);

        // 3) Hook "siap" agar tidak balapan saat render
        $html .= <<<'HTML'
<script>
(function(){
  function imagesReady(){
    var imgs=[].slice.call(document.images||[]);
    if(!imgs.length) return Promise.resolve();
    return Promise.all(imgs.map(function(i){
      if(i.complete) return Promise.resolve();
      return new Promise(function(r){
        i.addEventListener('load', r, {once:true});
        i.addEventListener('error', r, {once:true});
      });
    }));
  }
  var timer=setTimeout(function(){window.__CERT_READY=true;}, 800);
  imagesReady().then(function(){ clearTimeout(timer); window.__CERT_READY=true; });
})();
</script>
HTML;

        // 4) Path file sementara
        $safe = trim(preg_replace('/[^A-Za-z0-9_\- ]+/', '', pathinfo($downloadName, PATHINFO_FILENAME))) ?: 'Sertifikat';
        $filename = $safe . '.pdf';
        $dir  = storage_path('app/public/certificates');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $path = $dir . DIRECTORY_SEPARATOR . $filename;

        // 5) Browsershot
        $bs = Browsershot::html($html)
            ->showBackground()
            ->margins(0, 0, 0, 0)
            ->setOption('printBackground', true)
            ->setOption('preferCSSPageSize', true)
            ->emulateMedia('print')
            ->windowSize(1123, 794)  // A4 landscape @ ~96DPI
            ->deviceScaleFactor(2)
            ->waitForFunction('window.__CERT_READY === true')
            ->setOption('waitUntil', 'networkidle0')
            ->setOption('baseURL', config('app.url'))
            ->timeout(180);

        // allow override
        foreach ($browsershot as $method => $value) {
            if (method_exists($bs, $method)) {
                $bs->{$method}($value);
            }
        }

        if ($chromePath = env('BROWSERSHOT_CHROME_PATH')) {
            $bs->setChromePath($chromePath);
        }
        // Jika perlu:
        // $bs->addChromiumArguments(['--no-sandbox', '--disable-setuid-sandbox']);

        $bs->savePdf($path);

        return response()->download($path, $filename, ['Content-Type' => 'application/pdf'])
                        ->deleteFileAfterSend(true);
    }

    /* ========================= Helpers ========================= */

    /** Ubah semua <img src> & url(...) (inline style & <style>) menjadi data:base64 */
    private function embedAllImagesToBase64(string $html): string
    {
        $toPublicFile = function (string $src) {
            // absolute URL -> ambil path-nya
            if (preg_match('~^https?://~i', $src)) {
                $path = parse_url($src, PHP_URL_PATH) ?: '';
            } else {
                $path = $src;
            }
            $path = ltrim($path, '/');

            // dukung public/storage/... & public/images/... (tambahkan path lain jika perlu)
            $candidates = [];
            if (stripos($path, 'storage/') === 0 || stripos($path, 'images/') === 0) {
                $candidates[] = public_path($path);
            }
            // kalau `asset()` menghasilkan /build/... atau /vendor/... abaikan

            foreach ($candidates as $full) {
                if (is_file($full)) return $full;
            }
            return null;
        };

        $imgToDataUri = function (string $file) {
            $mime = FileFacade::mimeType($file) ?: 'image/png';
            $data = base64_encode(FileFacade::get($file));
            return "data:{$mime};base64,{$data}";
        };

        // Pakai DOMDocument untuk manipulasi HTML
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        // <img src="...">
        $imgs = $dom->getElementsByTagName('img');
        $imgNodes = [];
        foreach ($imgs as $i) { $imgNodes[] = $i; }
        foreach ($imgNodes as $img) {
            if (!($img instanceof \DOMElement)) continue;
            $src = $img->getAttribute('src');
            if (!$src) continue;
            $file = $toPublicFile($src);
            if ($file) $img->setAttribute('src', $imgToDataUri($file));
        }

        // inline style: url(...)
        $xpath = new \DOMXPath($dom);
        foreach ($xpath->query('//*[@style]') as $el) {
            if (!($el instanceof \DOMElement)) continue;
            $style = $el->getAttribute('style');
            $style = preg_replace_callback(
                '~url\((["\']?)([^)\'"]+)\1\)~i',
                function ($m) use ($toPublicFile, $imgToDataUri) {
                    $file = $toPublicFile($m[2]);
                    return $file ? 'url(' . $imgToDataUri($file) . ')' : $m[0];
                },
                $style
            );
            $el->setAttribute('style', $style);
        }

        // <style> blocks: url(...)
        $styleNodes = $dom->getElementsByTagName('style');
        for ($i = 0; $i < $styleNodes->length; $i++) {
            /** @var \DOMElement $styleEl */
            $styleEl = $styleNodes->item($i);
            $css = $styleEl->nodeValue ?? '';
            $css = preg_replace_callback(
                '~url\((["\']?)([^)\'"]+)\1\)~i',
                function ($m) use ($toPublicFile, $imgToDataUri) {
                    $file = $toPublicFile($m[2]);
                    return $file ? 'url(' . $imgToDataUri($file) . ')' : $m[0];
                },
                $css
            );
            while ($styleEl->firstChild) { $styleEl->removeChild($styleEl->firstChild); }
            $styleEl->appendChild($dom->createTextNode($css));
        }

        return $dom->saveHTML();
    }
}
