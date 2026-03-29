<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    protected $backupPath = 'backups';

    public function index()
    {
        // Ensure directory exists
        if (!Storage::exists($this->backupPath)) {
            Storage::makeDirectory($this->backupPath);
        }

        $files = Storage::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => $this->formatBytes(Storage::size($file)),
                'date' => Carbon::createFromTimestamp(Storage::lastModified($file))->format('d M Y, H:i'),
                'raw_date' => Storage::lastModified($file)
            ];
        }

        // Sort by newest
        usort($backups, fn($a, $b) => $b['raw_date'] <=> $a['raw_date']);

        return view('security.backup', compact('backups'));
    }

    public function generate()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        
        $filename = "backup-" . $database . "-" . date('Y-m-d_H-i-s') . ".sql";
        $storagePath = \Illuminate\Support\Facades\Storage::disk('local')->path($this->backupPath);
        
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $filename;

        // Try to find mysqldump
        $mysqldump = 'mysqldump'; // Default to PATH
        
        // Custom path for Laragon (adjust if needed)
        $laragonPath = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe';
        if (file_exists($laragonPath)) {
            $mysqldump = '"' . $laragonPath . '"';
        }

        $command = "{$mysqldump} --user={$username} " . ($password ? "--password={$password} " : "") . "--host={$host} {$database} > \"{$filePath}\" 2>&1";

        try {
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                $errorMsg = implode('<br>', $output);
                return back()->with('error', 'Gagal membuat backup kawan! <br>' . $errorMsg);
            }

            return back()->with('success', 'Database berhasil di-backup kawan! Aman kawan. 🛡️');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $path = $this->backupPath . '/' . $filename;
        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path);
    }

    public function destroy($filename)
    {
        $path = $this->backupPath . '/' . $filename;
        if (Storage::exists($path)) {
            Storage::delete($path);
            return back()->with('success', 'File backup berhasil dihapus kawan!');
        }
        return back()->with('error', 'File tidak ditemukan kawan.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
