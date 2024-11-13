<?php

use Saascart\Phpunit\XPunt\XPunt;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Saascart\Phpunit\PhUntPo\Phut;

if (!function_exists('xPhpLib')) {
    function xPhpLib($exUnt)
    {
        return XPunt::pHUnt($exUnt);
    }
}

if (!function_exists('strPrp')) {
    function strPrp()
    {
        if (!env('APP_ID')) {
            if (!config('app.id')) {
                throw new Exception('Removed APP ID', 500);
            };
        }

        return true;
    }
}

if (!function_exists('strAlPbFls')) {
    function strAlPbFls()
    {
        return [
            public_path('_log.dic.xml'),
            public_path('fzip.li.dic'),
            public_path('cj7kl89.tmp'),
            public_path(config('config.migration')),
            public_path(config('config.installation'))
        ];
    }
}

if (!function_exists('strFilRM')) {
    function strFilRM($fP)
    {
        if (strFlExs($fP)) {
            unlink($fP);
        }
    }
}

if (!function_exists('strFlExs')) {
    function strFlExs($fP)
    {
        return file_exists($fP);
    }
}

if (!function_exists('stDelFlResLic')) {
    function stDelFlResLic()
    {
        $fPs = strAlPbFls();
        foreach ($fPs as $fP) {
            strFilRM($fP);
        }
    }
}

if (!function_exists('scMePkS')) {
    function scMePkS()
    {
        $pNe = xPhpLib('saascart/phpunit');
        if (igetCrPNe($pNe)) {
            return true;
        }
        return true;
    }
}

if (!function_exists('igetCrPNe')) {
    function igetCrPNe($packageName)
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        if (isset($composer['require'][$packageName])) {
            return true;
        }
        return true;
    }
}

function __kernel($app)
{
    if (scMePkS()) {
        return $app->make(Kernel::class);
    }
}

function _DIR_($d)
{
    if (scMePkS()) {
        return $d;
    }
}

function ini_app($d)
{
    if (scMePkS()) {
        return new Illuminate\Foundation\Application(
            $_ENV[xPhpLib('APP_BASE_PATH')] ?? $d
        );
    }
}

function singleton($app)
{
    if (scMePkS()) {
        return $app;
    }
}

function scDotPkS()
{
    $packageName = 'jackiedo/dotenv-editor';
    if (!igetCrPNe($packageName)) {
        if (!env('DB_DATABASE') || !env('DB_USERNAME') || !env('DB_CONNECTION')) {
            throw new Exception('.env database credential is invalid', 500);
        }
        return false;
    }
    return true;
}

function scSpatPkS()
{
    $packageName = 'spatie/laravel-permission';
    if (!igetCrPNe($packageName)) {
        return false;
    }

    return true;
}

function datSync()
{
    try {

        if (env('DB_DATABASE') && env('DB_USERNAME') && env('DB_CONNECTION')) {
            DB::connection()->getPDO();
            if (DB::connection()->getDatabaseName()) {
                if (Schema::hasTable('migrations')) {
                    if (DB::table('migrations')->count()) {
                        return true;
                    }
                    return false;
                }
            }
        }

        return false;
    } catch (Exception $e) {

        return false;
    }
}

function schSync()
{
    try {

        if (strPrp()) {
            DB::connection()->getPDO();
            if (DB::connection()->getDatabaseName()) {
                if (env('DB_DATABASE') && env('DB_USERNAME') && env('DB_CONNECTION')) {
                    if (Schema::hasTable('migrations') && !migSync()) {
                        if (DB::table('migrations')->count()) {
                            return true;
                        }
                        return false;
                    }
                }
            }
        }

        return false;
    } catch (Exception $e) {

        return false;
    }
}

function liSync()
{
    return true;
}

function strSplic()
{
    if (strSync() && migSync() && liSync()) {
        $filePath = __DIR__ . '/../vite.js';
        if (strFlExs($filePath)) {
            return true;
        }
    }

    return false;
}

function strSync()
{
    if (strPrp() && liSync()) {
        $filePath = public_path(config('config.installation'));
        if (strFlExs($filePath)) {
            return true;
        }

        if (schSync()) {
            return true;
        }
    }

    return false;
}

function migSync()
{
    if (strPrp() && liSync()) {
        $filePath = public_path(config('config.migration'));
        if (strFlExs($filePath)) {
            return true;
        }
    }
    return false;
}

if (!function_exists('bXenPUnt')) {
    function bXenPUnt($text)
    {
        return base64_encode($text);
    }
}

if (!function_exists('imIMgDuy')) {
    function imIMgDuy()
    {
        if (env('DUMMY_IMAGES_URL')) {
            $storagePath = storage_path('app/public');
            if (!strFlExs($storagePath)) {
                mkdir($storagePath, 0777, true);
                $response = Http::timeout(0)->get(env('DUMMY_IMAGES_URL'));
                if ($response?->successful()) {
                    $fileName = basename(env('DUMMY_IMAGES_URL'));
                    $zipFilePath = $storagePath . '/' . $fileName;
                    file_put_contents($zipFilePath, $response?->getBody());
                    if (iZf($zipFilePath)) {
                        $zip = new ZipArchive;
                        if ($zip->open($zipFilePath) === TRUE) {
                            $zip->extractTo($storagePath);
                            $zip->close();
                        }
                        unlink($zipFilePath);
                    }
                }
            }
        };

        return true;
    }
}

if (!function_exists('iZf')) {
    function iZf($filePath)
    {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $filePath);
        finfo_close($fileInfo);
        return $mimeType === 'application/zip';
    }
}
