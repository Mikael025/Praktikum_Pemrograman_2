-- Document Management Enhancement - Manual SQL
-- Run these queries if php artisan migrate fails

-- 1. Create document_versions table
CREATE TABLE IF NOT EXISTS `document_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_type` varchar(255) NOT NULL COMMENT 'penelitian or pengabdian',
  `document_id` bigint unsigned NOT NULL COMMENT 'ID of parent document',
  `version_number` int NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `change_notes` text,
  `uploaded_by` bigint unsigned NOT NULL,
  `uploaded_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Soft delete for old versions',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_versions_document_type_document_id_index` (`document_type`,`document_id`),
  KEY `document_versions_version_number_index` (`version_number`),
  KEY `document_versions_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `document_versions_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add verification columns to penelitian_documents
ALTER TABLE `penelitian_documents`
  ADD COLUMN `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending' AFTER `category`,
  ADD COLUMN `verified_by` bigint unsigned NULL AFTER `verification_status`,
  ADD COLUMN `verified_at` timestamp NULL AFTER `verified_by`,
  ADD COLUMN `rejection_reason` text NULL AFTER `verified_at`,
  ADD COLUMN `version` int NOT NULL DEFAULT 1 AFTER `rejection_reason`,
  ADD COLUMN `parent_document_id` bigint unsigned NULL AFTER `version`,
  ADD CONSTRAINT `penelitian_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- 3. Add verification columns to pengabdian_documents
ALTER TABLE `pengabdian_documents`
  ADD COLUMN `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending' AFTER `category`,
  ADD COLUMN `verified_by` bigint unsigned NULL AFTER `verification_status`,
  ADD COLUMN `verified_at` timestamp NULL AFTER `verified_by`,
  ADD COLUMN `rejection_reason` text NULL AFTER `verified_at`,
  ADD COLUMN `version` int NOT NULL DEFAULT 1 AFTER `rejection_reason`,
  ADD COLUMN `parent_document_id` bigint unsigned NULL AFTER `version`,
  ADD CONSTRAINT `pengabdian_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Verify tables created
SHOW TABLES LIKE '%document%';

-- Check columns added
DESCRIBE penelitian_documents;
DESCRIBE pengabdian_documents;
DESCRIBE document_versions;
