

-- ==================Table: bimbingans================== 

INSERT INTO `bimbingans` (`bimbingan_id`, `dosen_nip`, `ta_id`, `urutan`, `verfied_by`) VALUES ('1', '1234567', '1', '1', '1');
INSERT INTO `bimbingans` (`bimbingan_id`, `dosen_nip`, `ta_id`, `urutan`, `verfied_by`) VALUES ('2', '7654321', '1', '2', '1');


-- ==================Table: dokumen_syarat_ta================== 

INSERT INTO `dokumen_syarat_ta` (`dokumen_id`, `dokumen_syarat`, `dokumen_file`, `created_by`, `verified_by`) VALUES ('1', 'Surat Keterangan PKL', '', '', '');
INSERT INTO `dokumen_syarat_ta` (`dokumen_id`, `dokumen_syarat`, `dokumen_file`, `created_by`, `verified_by`) VALUES ('2', 'Surat Keterangan KKL', '', '', '');


-- ==================Table: dosen================== 

INSERT INTO `dosen` (`dosen_nip`, `dosen_nama`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('1234567', 'Neli Fauziyah', 'admin', '2024-04-04 22:17:53', 'admin', '2024-04-04 22:17:53');
INSERT INTO `dosen` (`dosen_nip`, `dosen_nama`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('7654321', 'Sofia Alfin', 'admin', '2024-04-04 22:18:14', 'admin', '2024-04-04 22:18:14');


-- ==================Table: mahasiswa================== 

INSERT INTO `mahasiswa` (`mhs_nim`, `nama_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('987654', 'Aditya Satria', 'admin', '2024-04-04 22:18:52', 'admin', '2024-04-04 22:18:52');


-- ==================Table: menus================== 

INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('1', 'Menu Manajemen', '#', '', '', '0', '1');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('2', 'Dashboard', 'home', 'fas fa-home', '', '1', '1');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('3', 'Manajemen Pengguna', '#', 'fas fa-users-cog', '', '1', '2');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('4', 'Kelola Pengguna', 'manage-user', '', '', '3', '1');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('5', 'Kelola Role', 'manage-role', '', '', '3', '2');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('6', 'Kelola Menu', 'manage-menu', '', '', '3', '3');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('7', 'Backup Server', '#', '', '', '0', '2');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('8', 'Backup Database', 'dbbackup', 'fas fa-database', '', '7', '1');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('9', 'Manajemen Tugas Akhir', '#', 'fas fa-book-open', '', '0', '1');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('10', 'Bimbingan Tugas Akhir', 'bimbingan', 'fas fa-folder', '', '9', '1');
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `id_html`, `parent_id`, `urutan`) VALUES ('11', 'Sidang Tugas Akhir', 'ta', 'fas fa-user-graduate', '', '9', '1');


-- ==================Table: migrations================== 

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '2019_08_19_000000_create_failed_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2024_01_01_234158_create_menus_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2024_02_02_053619_create_permission_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2024_02_03_232722_create_role_has_menus_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2024_02_03_235312_add_menu_id_on_permission', '1');


-- ==================Table: model_has_roles================== 

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES ('1', 'App\\Models\\User', '1');


-- ==================Table: permissions================== 

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('1', 'create_user', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '4');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('2', 'read_user', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '4');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('3', 'update_user', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '4');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('4', 'delete_user', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '4');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('5', 'create_role', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '5');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('6', 'read_role', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '5');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('7', 'update_role', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '5');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('8', 'delete_role', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '5');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('9', 'create_menu', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '6');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('10', 'read_menu', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '6');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('11', 'update_menu', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '6');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('12', 'delete_menu', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '6');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('13', 'backup_database', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44', '8');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('14', 'create_bimbingan', 'web', '2024-04-08 07:32:51', '2024-04-08 07:32:51', '10');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('15', 'read_bimbingan', 'web', '2024-04-08 07:33:10', '2024-04-08 07:33:10', '10');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('16', 'update_bimbingan', 'web', '2024-04-08 09:35:51', '2024-04-08 09:35:51', '10');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('17', 'delete_bimbingan', 'web', '2024-04-08 09:36:26', '2024-04-08 09:36:26', '10');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('18', 'read_sidang', 'web', '2024-04-08 13:38:07', '2024-04-08 13:38:07', '11');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('19', 'create_sidang', 'web', '2024-04-08 13:38:26', '2024-04-08 13:38:26', '11');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('20', 'update_sidang', 'web', '2024-04-08 13:38:41', '2024-04-08 13:38:41', '11');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `menu_id`) VALUES ('21', 'delete_sidang', 'web', '2024-04-08 13:38:56', '2024-04-08 13:38:56', '11');


-- ==================Table: roles================== 

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('1', 'superadmin', 'web', '2024-04-04 13:44:44', '2024-04-04 13:44:44');
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('2', 'dosen', 'web', '2024-04-08 10:34:11', '2024-04-08 10:34:11');


-- ==================Table: role_has_menus================== 

INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('59', '1', '2');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('60', '2', '2');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('61', '9', '2');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('62', '10', '2');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('63', '11', '2');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('75', '1', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('76', '2', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('77', '3', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('78', '4', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('79', '5', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('80', '6', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('81', '7', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('82', '8', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('83', '9', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('84', '10', '1');
INSERT INTO `role_has_menus` (`id`, `menu_id`, `role_id`) VALUES ('85', '11', '1');


-- ==================Table: role_has_permissions================== 

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('1', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('2', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('3', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('4', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('5', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('6', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('7', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('8', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('9', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('10', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('11', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('12', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('13', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('14', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('14', '2');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('15', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('15', '2');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('16', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('16', '2');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('17', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('17', '2');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('18', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('19', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('20', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('21', '1');


-- ==================Table: tas================== 

INSERT INTO `tas` (`ta_id`, `mhs_nim`, `ta_judul`, `draft_ta`, `created_by`, `created_at`, `verfied_by`) VALUES ('1', '33422010', 'Meningkatkan Minat Anak Terhadap Konsumsi Protein', '', 'Admin', '2024-04-09 11:09:52', '1');
INSERT INTO `tas` (`ta_id`, `mhs_nim`, `ta_judul`, `draft_ta`, `created_by`, `created_at`, `verfied_by`) VALUES ('2', '33422011', 'Refleksi Penggunaan IOT dalam Kegiatan Belajar Men', '', 'Admin', '2024-04-09 11:12:17', '2');
INSERT INTO `tas` (`ta_id`, `mhs_nim`, `ta_judul`, `draft_ta`, `created_by`, `created_at`, `verfied_by`) VALUES ('3', '33422012', 'Penggunaan Video Game Untuk Meningkatkan Minat Bel', '', 'Admin', '2024-04-09 11:12:17', '3');


-- ==================Table: unsur_nilai_penguji================== 

INSERT INTO `unsur_nilai_penguji` (`nilai_id`, `unsur_nilai`, `bobot`) VALUES ('1', 'Isi dan Bobot Naskah', '0.15');


-- ==================Table: users================== 

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Super Admin', 'superadmin@gmail.com', '2024-04-04 13:44:44', '$2y$10$JFrM4u/dBtpxzD19zdVXRuyGBWB6KSd94diTKUeiRqJG1vTSmevsG', '0L36mzfnkv', '2024-04-04 13:44:44', '2024-04-04 13:44:44');
