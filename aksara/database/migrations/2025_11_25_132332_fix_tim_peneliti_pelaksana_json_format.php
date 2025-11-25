<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix penelitian tim_peneliti JSON array format to plain text
        $penelitianRecords = DB::table('penelitian')->get();
        foreach ($penelitianRecords as $record) {
            $timPeneliti = $record->tim_peneliti;
            
            // Check if it's JSON array format
            if (str_starts_with($timPeneliti, '[') && str_ends_with($timPeneliti, ']')) {
                $decoded = json_decode($timPeneliti, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    // Convert array to plain text (first element)
                    $plainText = $decoded[0];
                    DB::table('penelitian')
                        ->where('id', $record->id)
                        ->update(['tim_peneliti' => $plainText]);
                }
            }
        }
        
        // Fix pengabdian tim_pelaksana JSON array format to plain text
        $pengabdianRecords = DB::table('pengabdian')->get();
        foreach ($pengabdianRecords as $record) {
            $timPelaksana = $record->tim_pelaksana;
            
            // Check if it's JSON array format
            if (str_starts_with($timPelaksana, '[') && str_ends_with($timPelaksana, ']')) {
                $decoded = json_decode($timPelaksana, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    // Convert array to plain text (first element)
                    $plainText = $decoded[0];
                    DB::table('pengabdian')
                        ->where('id', $record->id)
                        ->update(['tim_pelaksana' => $plainText]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
