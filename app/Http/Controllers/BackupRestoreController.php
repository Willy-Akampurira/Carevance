<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupRestoreController extends Controller
{
    /**
     * Create a database backup using mysqldump
     */
    public function backup(): StreamedResponse
    {
        $database = env('DB_DATABASE');   // carevance
        $user     = env('DB_USERNAME');   // root
        $pass     = env('DB_PASSWORD');   // blank if no password
        $host     = env('DB_HOST');       // 127.0.0.1

        $filename = 'backup_' . $database . '_' . now()->format('Y-m-d_H-i-s') . '.sql';

        // Build mysqldump command with correct path
        $command = sprintf(
            '"%s" -u%s %s -h%s %s',
            env('MYSQLDUMP_PATH', 'C:\\xampp\\mysql\\bin\\mysqldump.exe'),
            $user,
            $pass ? '-p' . $pass : '',   // IMPORTANT: no space after -p
            $host,
            $database
        );

        return response()->streamDownload(function () use ($command) {
            $process = popen($command, 'r');
            if (!$process) {
                echo "-- Backup failed: unable to run mysqldump\n";
                return;
            }
            while (!feof($process)) {
                echo fread($process, 4096);
            }
            pclose($process);
        }, $filename);
    }

    /**
     * Restore the database from an uploaded .sql file
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt',
        ]);

        $path = $request->file('backup_file')->getRealPath();

        $database = env('DB_DATABASE');
        $user     = env('DB_USERNAME');
        $pass     = env('DB_PASSWORD');
        $host     = env('DB_HOST');

        $command = sprintf(
            '"%s" -u%s %s -h%s %s < "%s"',
            env('MYSQL_PATH', 'C:\\xampp\\mysql\\bin\\mysql.exe'),
            $user,
            $pass ? '-p' . $pass : '',
            $host,
            $database,
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Restore failed. Please check the backup file and credentials.');
        }

        return back()->with('success', 'Database restored successfully.');
    }
}
