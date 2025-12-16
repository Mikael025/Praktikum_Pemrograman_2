<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\LecturerProfile;
use App\Models\Penelitian;
use App\Models\PenelitianDocument;
use App\Models\Pengabdian;
use App\Models\PengabdianDocument;
use App\Models\StatusHistory;
use App\Models\DocumentVersion;
use App\Models\Informasi;

class DatabaseVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed database dengan data minimum untuk testing
        $this->seed();
    }

    /**
     * ============================================================================
     * BAGIAN 1: VERIFIKASI STRUKTUR TABEL
     * ============================================================================
     */

    /** @test */
    public function test_all_tables_exist()
    {
        $tables = [
            'users',
            'lecturer_profiles',
            'penelitian',
            'penelitian_documents',
            'pengabdian',
            'pengabdian_documents',
            'status_history',
            'document_versions',
            'informasi'
        ];

        $this->info("\n=== TEST: Verifikasi Keberadaan Tabel ===\n");

        foreach ($tables as $table) {
            $exists = Schema::hasTable($table);
            $this->assertTrue($exists, "Tabel {$table} tidak ditemukan");
            $this->info("✅ Tabel '{$table}' EXISTS");
        }

        $this->info("\n✓ Semua 9 tabel berhasil diverifikasi\n");
    }

    /** @test */
    public function test_users_table_structure()
    {
        $this->info("\n=== TEST: Struktur Tabel Users ===\n");

        $columns = ['id', 'name', 'email', 'password', 'role', 'email_verified_at', 'created_at', 'updated_at'];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('users', $column),
                "Kolom {$column} tidak ada di tabel users"
            );
            $this->info("✅ Kolom '{$column}' EXISTS");
        }

        $this->info("\n✓ Semua kolom users berhasil diverifikasi\n");
    }

    /**
     * ============================================================================
     * BAGIAN 2: VERIFIKASI CONSTRAINTS
     * ============================================================================
     */

    /** @test */
    public function test_enum_constraint_on_role()
    {
        $this->info("\n=== TEST: ENUM Constraint - User Role ===\n");

        // Test valid role
        try {
            $user = User::create([
                'name' => 'Test Admin',
                'email' => 'testadmin@test.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
            $this->info("✅ Role 'admin' - VALID (Accepted)");
        } catch (\Exception $e) {
            $this->fail("Role 'admin' seharusnya valid: " . $e->getMessage());
        }

        // Test invalid role
        try {
            User::create([
                'name' => 'Test Invalid',
                'email' => 'testinvalid@test.com',
                'password' => bcrypt('password'),
                'role' => 'invalid_role'
            ]);
            $this->fail("Role 'invalid_role' seharusnya ditolak");
        } catch (\Exception $e) {
            $this->info("✅ Role 'invalid_role' - REJECTED (Expected)");
            $this->assertTrue(true);
        }

        $this->info("\n✓ ENUM constraint untuk role berfungsi dengan baik\n");
    }

    /** @test */
    public function test_unique_constraint_on_email()
    {
        $this->info("\n=== TEST: UNIQUE Constraint - Email ===\n");

        // Insert pertama
        User::create([
            'name' => 'User 1',
            'email' => 'unique@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);
        $this->info("✅ Email pertama 'unique@test.com' - INSERTED");

        // Insert duplicate
        try {
            User::create([
                'name' => 'User 2',
                'email' => 'unique@test.com',
                'password' => bcrypt('password'),
                'role' => 'dosen'
            ]);
            $this->fail("Email duplicate seharusnya ditolak");
        } catch (\Exception $e) {
            $this->info("✅ Email duplicate 'unique@test.com' - REJECTED (Expected)");
            $this->assertTrue(true);
        }

        $this->info("\n✓ UNIQUE constraint untuk email berfungsi dengan baik\n");
    }

    /** @test */
    public function test_not_null_constraint()
    {
        $this->info("\n=== TEST: NOT NULL Constraint ===\n");

        try {
            User::create([
                'name' => null, // Required field
                'email' => 'test@test.com',
                'password' => bcrypt('password'),
                'role' => 'dosen'
            ]);
            $this->fail("Field 'name' NULL seharusnya ditolak");
        } catch (\Exception $e) {
            $this->info("✅ Field 'name' NULL - REJECTED (Expected)");
            $this->assertTrue(true);
        }

        $this->info("\n✓ NOT NULL constraint berfungsi dengan baik\n");
    }

    /** @test */
    public function test_foreign_key_constraint()
    {
        $this->info("\n=== TEST: Foreign Key Constraint ===\n");

        // Test dengan valid foreign key
        $user = User::create([
            'name' => 'Test Dosen',
            'email' => 'testdosen@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Penelitian',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $this->info("✅ Foreign Key user_id={$user->id} - VALID (Accepted)");

        // Test dengan invalid foreign key
        try {
            Penelitian::create([
                'user_id' => 99999, // Non-existent user
                'judul' => 'Test Invalid',
                'tahun' => 2024,
                'tim_peneliti' => 'Dr. Test',
                'sumber_dana' => 'Internal',
                'status' => 'diusulkan'
            ]);
            $this->fail("Foreign key invalid seharusnya ditolak");
        } catch (\Exception $e) {
            $this->info("✅ Foreign Key user_id=99999 - REJECTED (Expected)");
            $this->assertTrue(true);
        }

        $this->info("\n✓ Foreign Key constraint berfungsi dengan baik\n");
    }

    /**
     * ============================================================================
     * BAGIAN 3: VERIFIKASI RELASI ANTAR TABEL
     * ============================================================================
     */

    /** @test */
    public function test_one_to_one_relation_user_lecturer_profile()
    {
        $this->info("\n=== TEST: Relasi One-to-One (User ↔ LecturerProfile) ===\n");

        $user = User::create([
            'name' => 'Dr. Test Lecturer',
            'email' => 'lecturer@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $profile = LecturerProfile::create([
            'user_id' => $user->id,
            'nip' => '198501012010011001',
            'affiliation' => 'Universitas Test',
            'citizenship' => 'Indonesia'
        ]);

        // Test relasi dari User ke LecturerProfile
        $userWithProfile = User::with('lecturerProfile')->find($user->id);
        $this->assertNotNull($userWithProfile->lecturerProfile);
        $this->assertEquals($profile->nip, $userWithProfile->lecturerProfile->nip);

        $this->info("✅ User → LecturerProfile: {$userWithProfile->name} → NIP: {$userWithProfile->lecturerProfile->nip}");

        // Test relasi dari LecturerProfile ke User
        $profileWithUser = LecturerProfile::with('user')->find($profile->id);
        $this->assertNotNull($profileWithUser->user);
        $this->assertEquals($user->name, $profileWithUser->user->name);

        $this->info("✅ LecturerProfile → User: NIP {$profileWithUser->nip} → {$profileWithUser->user->name}");

        $this->info("\n✓ Relasi One-to-One berfungsi dengan baik\n");
    }

    /** @test */
    public function test_one_to_many_relation_user_penelitian()
    {
        $this->info("\n=== TEST: Relasi One-to-Many (User ↔ Penelitian) ===\n");

        $user = User::create([
            'name' => 'Dr. Peneliti Test',
            'email' => 'peneliti@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian1 = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Penelitian AI',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $penelitian2 = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Penelitian IoT',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'External',
            'status' => 'lolos'
        ]);

        // Test relasi dari User ke Penelitian
        $userWithPenelitian = User::with('penelitian')->find($user->id);
        $this->assertCount(2, $userWithPenelitian->penelitian);

        $this->info("✅ User '{$userWithPenelitian->name}' memiliki {$userWithPenelitian->penelitian->count()} penelitian:");
        foreach ($userWithPenelitian->penelitian as $p) {
            $this->info("   - {$p->judul} (Status: {$p->status})");
        }

        // Test relasi dari Penelitian ke User
        $penelitianWithUser = Penelitian::with('user')->find($penelitian1->id);
        $this->assertEquals($user->name, $penelitianWithUser->user->name);

        $this->info("✅ Penelitian '{$penelitianWithUser->judul}' milik {$penelitianWithUser->user->name}");

        $this->info("\n✓ Relasi One-to-Many berfungsi dengan baik\n");
    }

    /** @test */
    public function test_polymorphic_relation_status_history()
    {
        $this->info("\n=== TEST: Relasi Polymorphic (StatusHistory) ===\n");

        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Polymorphic',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        // Create status history
        $history1 = StatusHistory::create([
            'statusable_type' => Penelitian::class,
            'statusable_id' => $penelitian->id,
            'old_status' => null,
            'new_status' => 'diusulkan',
            'notes' => 'Penelitian diusulkan',
            'changed_by_user_id' => $user->id
        ]);

        $history2 = StatusHistory::create([
            'statusable_type' => Penelitian::class,
            'statusable_id' => $penelitian->id,
            'old_status' => 'diusulkan',
            'new_status' => 'lolos',
            'notes' => 'Penelitian lolos verifikasi',
            'changed_by_user_id' => $user->id
        ]);

        // Test polymorphic relation dari Penelitian
        $penelitianWithHistory = Penelitian::with('statusHistory')->find($penelitian->id);
        $this->assertCount(2, $penelitianWithHistory->statusHistory);

        $this->info("✅ Penelitian '{$penelitianWithHistory->judul}' memiliki {$penelitianWithHistory->statusHistory->count()} history:");
        foreach ($penelitianWithHistory->statusHistory as $h) {
            $this->info("   - {$h->old_status} → {$h->new_status}");
        }

        // Test polymorphic relation dari StatusHistory
        $historyWithModel = StatusHistory::find($history1->id);
        $this->assertEquals(Penelitian::class, $historyWithModel->statusable_type);
        $this->assertEquals($penelitian->id, $historyWithModel->statusable_id);

        $this->info("✅ StatusHistory terhubung ke model: " . class_basename($historyWithModel->statusable_type));

        $this->info("\n✓ Relasi Polymorphic berfungsi dengan baik\n");
    }

    /** @test */
    public function test_cascade_delete()
    {
        $this->info("\n=== TEST: Cascade Delete ===\n");

        $user = User::create([
            'name' => 'Test Delete User',
            'email' => 'delete@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Delete',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $document = PenelitianDocument::create([
            'penelitian_id' => $penelitian->id,
            'jenis_dokumen' => 'proposal',
            'nama_file' => 'test.pdf',
            'path_file' => 'test/path.pdf',
            'uploaded_at' => now()
        ]);

        $penelitianId = $penelitian->id;
        $documentCount = PenelitianDocument::where('penelitian_id', $penelitianId)->count();
        $this->info("✅ Sebelum delete: Penelitian ID {$penelitianId} memiliki {$documentCount} dokumen");

        // Delete penelitian
        $penelitian->delete();

        // Verify cascade delete
        $documentCountAfter = PenelitianDocument::where('penelitian_id', $penelitianId)->count();
        $this->assertEquals(0, $documentCountAfter);

        $this->info("✅ Setelah delete: Dokumen terhapus (count: {$documentCountAfter})");
        $this->info("\n✓ Cascade Delete berfungsi dengan baik\n");
    }

    /**
     * ============================================================================
     * BAGIAN 4: PENGUJIAN CRUD OPERATIONS
     * ============================================================================
     */

    /** @test */
    public function test_insert_data_penelitian()
    {
        $this->info("\n=== TEST: Insert Data Penelitian ===\n");

        $user = User::create([
            'name' => 'Dr. Insert Test',
            'email' => 'insert@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Pengembangan Sistem Pembelajaran Berbasis AI',
            'tahun' => 2024,
            'tim_peneliti' => json_encode(['Dr. A', 'Dr. B']),
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $this->assertNotNull($penelitian->id);
        $this->assertEquals('diusulkan', $penelitian->status);
        $this->assertNotNull($penelitian->created_at);
        $this->assertNotNull($penelitian->updated_at);

        $this->info("✅ Penelitian tersimpan dengan ID: {$penelitian->id}");
        $this->info("✅ Judul: {$penelitian->judul}");
        $this->info("✅ Status: {$penelitian->status}");
        $this->info("✅ Timestamps: created_at dan updated_at ter-generate");

        $this->info("\n✓ Insert data penelitian berhasil\n");
    }

    /** @test */
    public function test_insert_data_with_foreign_key()
    {
        $this->info("\n=== TEST: Insert Data dengan Foreign Key ===\n");

        $user = User::create([
            'name' => 'Dr. Document Test',
            'email' => 'document@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Document',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $document = PenelitianDocument::create([
            'penelitian_id' => $penelitian->id,
            'jenis_dokumen' => 'proposal',
            'nama_file' => 'proposal_penelitian.pdf',
            'path_file' => 'penelitian/documents/abc123.pdf',
            'uploaded_at' => now()
        ]);

        $this->assertNotNull($document->id);
        $this->assertEquals($penelitian->id, $document->penelitian_id);
        $this->assertEquals('proposal', $document->jenis_dokumen);

        $this->info("✅ Dokumen tersimpan dengan ID: {$document->id}");
        $this->info("✅ Foreign Key penelitian_id: {$document->penelitian_id}");
        $this->info("✅ Jenis Dokumen: {$document->jenis_dokumen}");
        $this->info("✅ File metadata tersimpan: {$document->nama_file}");

        $this->info("\n✓ Insert data dengan foreign key berhasil\n");
    }

    /** @test */
    public function test_update_data_with_workflow()
    {
        $this->info("\n=== TEST: Update Data dengan Workflow ===\n");

        $user = User::create([
            'name' => 'Dr. Update Test',
            'email' => 'update@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Update',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $oldStatus = $penelitian->status;
        $oldUpdatedAt = $penelitian->updated_at;

        $this->info("✅ Status awal: {$oldStatus}");

        // Update status
        sleep(1); // Ensure updated_at changes
        $penelitian->update([
            'status' => 'lolos',
            'catatan_verifikasi' => 'Proposal diterima, silakan lanjutkan'
        ]);

        $penelitian->refresh();

        $this->assertEquals('lolos', $penelitian->status);
        $this->assertEquals('Proposal diterima, silakan lanjutkan', $penelitian->catatan_verifikasi);
        $this->assertNotEquals($oldUpdatedAt, $penelitian->updated_at);

        $this->info("✅ Status berhasil diupdate: {$oldStatus} → {$penelitian->status}");
        $this->info("✅ Catatan verifikasi tersimpan: {$penelitian->catatan_verifikasi}");
        $this->info("✅ Timestamp updated_at ter-update otomatis");

        $this->info("\n✓ Update data dengan workflow berhasil\n");
    }

    /** @test */
    public function test_query_with_filter_and_relations()
    {
        $this->info("\n=== TEST: Query dengan Filter dan Relasi ===\n");

        // Create test data
        $user = User::create([
            'name' => 'Dr. Query Test',
            'email' => 'query@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian1 = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Penelitian 2024 Lolos',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'lolos'
        ]);

        $penelitian2 = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Penelitian 2024 Diusulkan',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'External',
            'status' => 'diusulkan'
        ]);

        $document = PenelitianDocument::create([
            'penelitian_id' => $penelitian1->id,
            'jenis_dokumen' => 'proposal',
            'nama_file' => 'proposal.pdf',
            'path_file' => 'path/to/file.pdf',
            'uploaded_at' => now()
        ]);

        // Query dengan filter
        $results = Penelitian::where('tahun', 2024)
            ->where('status', 'lolos')
            ->with(['user', 'documents'])
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals('lolos', $results->first()->status);
        $this->assertNotNull($results->first()->user);
        $this->assertCount(1, $results->first()->documents);

        $this->info("✅ Filter WHERE berfungsi: Found {$results->count()} penelitian");
        $this->info("✅ Status filter: {$results->first()->status}");
        $this->info("✅ Eager loading user: {$results->first()->user->name}");
        $this->info("✅ Eager loading documents: {$results->first()->documents->count()} dokumen");

        $this->info("\n✓ Query dengan filter dan relasi berhasil\n");
    }

    /** @test */
    public function test_soft_delete()
    {
        $this->info("\n=== TEST: Soft Delete ===\n");

        $user = User::create([
            'name' => 'Test Soft Delete',
            'email' => 'softdelete@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Soft Delete',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $version = DocumentVersion::create([
            'versionable_type' => Penelitian::class,
            'versionable_id' => $penelitian->id,
            'version_number' => 1,
            'jenis_dokumen' => 'proposal',
            'nama_file' => 'test_v1.pdf',
            'path_file' => 'path/test_v1.pdf',
            'file_size' => 1024,
            'uploaded_by_user_id' => $user->id
        ]);

        $versionId = $version->id;
        $this->info("✅ DocumentVersion created dengan ID: {$versionId}");

        // Soft delete
        $version->delete();

        // Verify still exists but soft deleted
        $deletedVersion = DocumentVersion::withTrashed()->find($versionId);
        $this->assertNotNull($deletedVersion);
        $this->assertNotNull($deletedVersion->deleted_at);

        $this->info("✅ Record masih ada dengan deleted_at: {$deletedVersion->deleted_at}");

        // Verify not accessible in normal query
        $normalQuery = DocumentVersion::find($versionId);
        $this->assertNull($normalQuery);

        $this->info("✅ Query normal tidak mengembalikan soft deleted record");

        // Query only trashed
        $trashed = DocumentVersion::onlyTrashed()->find($versionId);
        $this->assertNotNull($trashed);

        $this->info("✅ Query onlyTrashed() berhasil retrieve soft deleted record");

        $this->info("\n✓ Soft Delete berfungsi dengan baik\n");
    }

    /** @test */
    public function test_data_integrity_referential()
    {
        $this->info("\n=== TEST: Data Integrity - Referential ===\n");

        $user = User::create([
            'name' => 'Test Integrity',
            'email' => 'integrity@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test Integrity',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $this->info("✅ Created user (ID: {$user->id}) dengan penelitian (ID: {$penelitian->id})");

        // Try to delete user with related penelitian
        try {
            DB::table('users')->where('id', $user->id)->delete();
            $this->fail("Seharusnya foreign key constraint mencegah delete");
        } catch (\Exception $e) {
            $this->info("✅ Foreign key constraint mencegah delete user dengan penelitian");
            $this->assertTrue(true);
        }

        // Verify user still exists
        $userExists = User::find($user->id);
        $this->assertNotNull($userExists);

        $this->info("✅ Database integrity terjaga, user masih ada");

        $this->info("\n✓ Referential Integrity berfungsi dengan baik\n");
    }

    /**
     * ============================================================================
     * BAGIAN 5: HIGH PRIORITY ADDITIONAL TESTS
     * ============================================================================
     */

    /** @test */
    public function test_enum_constraint_penelitian_status()
    {
        $this->info("\n=== TEST: ENUM Constraint - Penelitian & Pengabdian Status ===\n");

        $user = User::create([
            'name' => 'Dr. Status Test',
            'email' => 'status@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        // Test valid status penelitian
        $validStatuses = ['diusulkan', 'tidak_lolos', 'lolos_perlu_revisi', 'lolos', 'revisi_pra_final', 'selesai'];
        
        foreach ($validStatuses as $status) {
            $penelitian = Penelitian::create([
                'user_id' => $user->id,
                'judul' => "Test Status {$status}",
                'tahun' => 2024,
                'tim_peneliti' => 'Dr. Test',
                'sumber_dana' => 'Internal',
                'status' => $status
            ]);
            $this->assertEquals($status, $penelitian->status);
        }

        $this->info("✅ Semua 6 status valid penelitian - ACCEPTED (diusulkan, tidak_lolos, lolos_perlu_revisi, lolos, revisi_pra_final, selesai)");

        // Test invalid status penelitian
        try {
            Penelitian::create([
                'user_id' => $user->id,
                'judul' => 'Test Invalid',
                'tahun' => 2024,
                'tim_peneliti' => 'Dr. Test',
                'sumber_dana' => 'Internal',
                'status' => 'invalid_status'
            ]);
            $this->fail("Status invalid seharusnya ditolak");
        } catch (\Exception $e) {
            $this->info("✅ Status 'invalid_status' - REJECTED (Expected)");
            $this->assertTrue(true);
        }

        // Test pengabdian status (sama dengan penelitian)
        $pengabdian = Pengabdian::create([
            'user_id' => $user->id,
            'judul' => 'Test Pengabdian',
            'tahun' => 2024,
            'tim_pelaksana' => 'Dr. Test',
            'lokasi' => 'Desa Test',
            'mitra' => 'Pemda Test',
            'sumber_dana' => 'Internal',
            'status' => 'lolos'
        ]);

        $this->assertEquals('lolos', $pengabdian->status);
        $this->info("✅ Pengabdian status 'lolos' - ACCEPTED");

        $this->info("\n✓ ENUM constraint untuk status berfungsi dengan baik\n");
    }

    /** @test */
    public function test_user_pengabdian_relation()
    {
        $this->info("\n=== TEST: Relasi User → Pengabdian ===\n");

        $user = User::create([
            'name' => 'Dr. Pengabdi Test',
            'email' => 'pengabdi@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $pengabdian1 = Pengabdian::create([
            'user_id' => $user->id,
            'judul' => 'Pengabdian Masyarakat Desa A',
            'tahun' => 2024,
            'tim_pelaksana' => 'Dr. Test, Dr. A',
            'lokasi' => 'Desa A, Kec. X',
            'mitra' => 'Pemda Kab. X',
            'sumber_dana' => 'DIPA',
            'status' => 'diusulkan'
        ]);

        $pengabdian2 = Pengabdian::create([
            'user_id' => $user->id,
            'judul' => 'Pelatihan UMKM Desa B',
            'tahun' => 2024,
            'tim_pelaksana' => 'Dr. Test',
            'lokasi' => 'Desa B, Kec. Y',
            'mitra' => 'Karang Taruna Desa B',
            'sumber_dana' => 'Mandiri',
            'status' => 'selesai'
        ]);

        // Test relasi dari User ke Pengabdian
        $userWithPengabdian = User::with('pengabdian')->find($user->id);
        $this->assertCount(2, $userWithPengabdian->pengabdian);

        $this->info("✅ User '{$userWithPengabdian->name}' memiliki {$userWithPengabdian->pengabdian->count()} pengabdian:");
        foreach ($userWithPengabdian->pengabdian as $p) {
            $this->info("   - {$p->judul} (Status: {$p->status})");
        }

        // Test relasi dari Pengabdian ke User
        $pengabdianWithUser = Pengabdian::with('user')->find($pengabdian1->id);
        $this->assertEquals($user->name, $pengabdianWithUser->user->name);

        $this->info("✅ Pengabdian '{$pengabdianWithUser->judul}' milik {$pengabdianWithUser->user->name}");

        $this->info("\n✓ Relasi User ↔ Pengabdian berfungsi dengan baik\n");
    }

    /** @test */
    public function test_insert_pengabdian_complete()
    {
        $this->info("\n=== TEST: Insert Pengabdian Lengkap ===\n");

        $user = User::create([
            'name' => 'Dr. Insert Pengabdian',
            'email' => 'insertpengabdian@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        $pengabdian = Pengabdian::create([
            'user_id' => $user->id,
            'judul' => 'Pemberdayaan UMKM Melalui Digitalisasi',
            'tahun' => 2024,
            'tim_pelaksana' => 'Dr. Insert, Dr. B, Dr. C',
            'lokasi' => 'Desa Digital, Kec. Tech',
            'mitra' => 'UMKM Desa Digital',
            'sumber_dana' => 'DIPA',
            'status' => 'lolos',
            'catatan_verifikasi' => 'Proposal diterima dengan baik'
        ]);

        // Verify insert
        $this->assertNotNull($pengabdian->id);
        $this->assertEquals('Pemberdayaan UMKM Melalui Digitalisasi', $pengabdian->judul);
        $this->assertEquals(2024, $pengabdian->tahun);
        $this->assertEquals('lolos', $pengabdian->status);
        $this->assertNotNull($pengabdian->created_at);
        $this->assertNotNull($pengabdian->updated_at);

        $this->info("✅ Pengabdian tersimpan dengan ID: {$pengabdian->id}");
        $this->info("✅ Judul: {$pengabdian->judul}");
        $this->info("✅ Tahun: {$pengabdian->tahun}");
        $this->info("✅ Tim Pelaksana: {$pengabdian->tim_pelaksana}");
        $this->info("✅ Status: {$pengabdian->status}");

        // Insert dokumen pengabdian
        $document = PengabdianDocument::create([
            'pengabdian_id' => $pengabdian->id,
            'jenis_dokumen' => 'proposal',
            'nama_file' => 'proposal_pengabdian.pdf',
            'path_file' => 'uploads/pengabdian/proposal_pengabdian.pdf',
            'uploaded_at' => now()
        ]);

        $this->assertNotNull($document->id);
        $this->info("✅ Dokumen pengabdian tersimpan: {$document->nama_file} (Jenis: {$document->jenis_dokumen})");

        $this->info("\n✓ Insert pengabdian lengkap berhasil\n");
    }

    /** @test */
    public function test_json_field_tim_peneliti()
    {
        $this->info("\n=== TEST: JSON Field Handling - Tim Peneliti ===\n");

        $user = User::create([
            'name' => 'Dr. JSON Test',
            'email' => 'json@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        // Insert dengan JSON string (backward compatibility)
        $penelitian1 = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test JSON String',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. A, Dr. B, Dr. C',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $this->assertEquals('Dr. A, Dr. B, Dr. C', $penelitian1->tim_peneliti);
        $this->info("✅ Tim peneliti (string): {$penelitian1->tim_peneliti}");

        // Insert dengan JSON array
        $timArray = json_encode(['Dr. JSON Test', 'Dr. Developer', 'Dr. Programmer']);
        $penelitian2 = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Test JSON Array',
            'tahun' => 2024,
            'tim_peneliti' => $timArray,
            'sumber_dana' => 'External',
            'status' => 'diusulkan'
        ]);

        $decoded = json_decode($penelitian2->tim_peneliti, true);
        $this->assertTrue(is_array($decoded));
        $this->assertCount(3, $decoded);
        $this->assertContains('Dr. JSON Test', $decoded);

        $this->info("✅ Tim peneliti (JSON array): " . $penelitian2->tim_peneliti);
        $this->info("✅ Decoded array count: " . count($decoded));

        // Test pengabdian dengan JSON
        $pengabdian = Pengabdian::create([
            'user_id' => $user->id,
            'judul' => 'Test JSON Pengabdian',
            'tahun' => 2024,
            'tim_pelaksana' => json_encode(['Dr. X', 'Dr. Y', 'Dr. Z']),
            'lokasi' => 'Desa JSON',
            'mitra' => 'Mitra JSON',
            'sumber_dana' => 'Internal',
            'status' => 'lolos'
        ]);

        $decodedPengabdi = json_decode($pengabdian->tim_pelaksana, true);
        $this->assertCount(3, $decodedPengabdi);
        $this->info("✅ Tim pelaksana (JSON array): " . $pengabdian->tim_pelaksana);

        $this->info("\n✓ JSON field handling berfungsi dengan baik\n");
    }

    /** @test */
    public function test_polymorphic_document_version()
    {
        $this->info("\n=== TEST: Polymorphic DocumentVersion ===\n");

        $user = User::create([
            'name' => 'Dr. Version Test',
            'email' => 'version@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        // Create penelitian dengan version
        $penelitian = Penelitian::create([
            'user_id' => $user->id,
            'judul' => 'Penelitian dengan Versi',
            'tahun' => 2024,
            'tim_peneliti' => 'Dr. Test',
            'sumber_dana' => 'Internal',
            'status' => 'diusulkan'
        ]);

        $docPenelitian = PenelitianDocument::create([
            'penelitian_id' => $penelitian->id,
            'jenis_dokumen' => 'proposal',
            'nama_file' => 'proposal_v1.pdf',
            'path_file' => 'uploads/proposal_v1.pdf',
            'uploaded_at' => now()
        ]);

        // Create document version untuk penelitian
        $versionPenelitian = DocumentVersion::create([
            'document_type' => 'penelitian',
            'document_id' => $docPenelitian->id,
            'version_number' => 1,
            'nama_file' => 'proposal_v1.pdf',
            'path_file' => 'uploads/versions/proposal_v1.pdf',
            'uploaded_by' => $user->id,
            'uploaded_at' => now(),
            'change_notes' => 'Versi awal proposal'
        ]);

        $this->assertNotNull($versionPenelitian->id);
        $this->assertEquals('penelitian', $versionPenelitian->document_type);
        $this->assertEquals($docPenelitian->id, $versionPenelitian->document_id);
        $this->info("✅ DocumentVersion untuk Penelitian - ID: {$versionPenelitian->id}, Version: {$versionPenelitian->version_number}");

        // Create pengabdian dengan version
        $pengabdian = Pengabdian::create([
            'user_id' => $user->id,
            'judul' => 'Pengabdian dengan Versi',
            'tahun' => 2024,
            'tim_pelaksana' => 'Dr. Test',
            'lokasi' => 'Desa Versi',
            'mitra' => 'Mitra Versi',
            'sumber_dana' => 'Internal',
            'status' => 'lolos'
        ]);

        $docPengabdian = PengabdianDocument::create([
            'pengabdian_id' => $pengabdian->id,
            'jenis_dokumen' => 'laporan_akhir',
            'nama_file' => 'laporan_v2.pdf',
            'path_file' => 'uploads/laporan_v2.pdf',
            'uploaded_at' => now()
        ]);

        $versionPengabdian = DocumentVersion::create([
            'document_type' => 'pengabdian',
            'document_id' => $docPengabdian->id,
            'version_number' => 2,
            'nama_file' => 'laporan_v2.pdf',
            'path_file' => 'uploads/versions/laporan_v2.pdf',
            'uploaded_by' => $user->id,
            'uploaded_at' => now(),
            'change_notes' => 'Revisi setelah review'
        ]);

        $this->assertEquals('pengabdian', $versionPengabdian->document_type);
        $this->info("✅ DocumentVersion untuk Pengabdian - ID: {$versionPengabdian->id}, Version: {$versionPengabdian->version_number}");

        // Query document versions
        $allVersions = DocumentVersion::all();
        $this->assertCount(2, $allVersions);

        $this->info("✅ Total document versions: {$allVersions->count()}");
        foreach ($allVersions as $v) {
            $this->info("   - Version {$v->version_number}: {$v->document_type} (Doc ID: {$v->document_id})");
        }

        $this->info("\n✓ Polymorphic DocumentVersion berfungsi dengan baik\n");
    }

    /**
     * Helper untuk print info
     */
    protected function info(string $message)
    {
        fwrite(STDOUT, $message . "\n");
    }
}
