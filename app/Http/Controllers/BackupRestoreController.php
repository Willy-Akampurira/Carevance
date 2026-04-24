<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupRestoreController extends Controller
{
    /**
     * Create a database backup and stream it as a download
     */
    public function backup(): StreamedResponse
    {
        $database = env('DB_DATABASE');
        $user     = env('DB_USERNAME');
        $pass     = env('DB_PASSWORD');
        $host     = env('DB_HOST');

        $filename = 'backup_' . $database . '_' . now()->format('Y-m-d_H-i-s') . '.sql';

        // Build mysqldump command
        $command = sprintf(
            '"%s" --user=%s %s --host=%s %s',
            env('MYSQLDUMP_PATH', 'C:/xampp/mysql/bin/mysqldump.exe'),
            $user,
            $pass ? '--password=' . $pass : '',
            $host,
            $database
        );

        return response()->streamDownload(function () use ($command) {
            $process = popen($command, 'r');
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
            '"%s" --user=%s %s --host=%s %s < "%s"',
            env('MYSQL_PATH', 'C:/xampp/mysql/bin/mysql.exe'),
            $user,
            $pass ? '--password=' . $pass : '',
            $host,
            $database,
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Restore failed. Please check the backup file.');
        }

        return back()->with('success', 'Database restored successfully.');
    }
}
