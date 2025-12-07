<?php

namespace App\Console\Commands;

use App\Models\DocumentVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOldDocumentVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:clean-old-versions {--days=30 : Number of days to keep versions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old document versions older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning document versions older than {$days} days...");
        
        // Get old versions
        $oldVersions = DocumentVersion::oldVersions($days)->get();
        
        if ($oldVersions->isEmpty()) {
            $this->info('No old versions found.');
            return 0;
        }
        
        $count = 0;
        $deletedFiles = 0;
        
        foreach ($oldVersions as $version) {
            // Delete file from storage
            if (Storage::disk('public')->exists($version->path_file)) {
                Storage::disk('public')->delete($version->path_file);
                $deletedFiles++;
            }
            
            // Soft delete the record
            $version->deleted_at = now();
            $version->save();
            
            $count++;
        }
        
        $this->info("✓ Soft deleted {$count} old versions");
        $this->info("✓ Deleted {$deletedFiles} files from storage");
        
        return 0;
    }
}
