<?php include 'views/layout/header.php'; ?>
<div class="layout">
    <?php include 'views/layout/sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div>
                <h2>Katalog Buku Pustaka Warna Induk</h2>
                <p style="color: var(--text-muted); font-size: 14px;">
                    Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? 'Pengguna') ?></strong> 
                    (<?= $_SESSION['user']['role'] ?? 'Anggota' ?>)
                </p>
            </div>
            
            <!-- Tombol Tambah Hanya untuk Admin/Petugas -->
            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'Admin' || $_SESSION['user']['role'] === 'Petugas')): ?>
                <button class="btn-primary btn-add" onclick="openModalTambah()">+ Tambah Buku Baru</button>
            <?php endif; ?>
        </div>

        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>ID Buku</th>
                        <th>Cover</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bukuList)): ?>
                        <?php foreach ($bukuList as $buku): ?>
                        <tr>
                            <td><strong>#<?= $buku['id_buku'] ?></strong></td>
                            <td>
                                <img src="asset/uploads/<?= htmlspecialchars($buku['cover']) ?>" class="img-cover" alt="Cover" onerror="this.src='asset/uploads/cover1.jpg'">
                            </td>
                            <td><strong><?= htmlspecialchars($buku['judul_buku']) ?></strong></td>
                            <td><?= htmlspecialchars($buku['pengarang']) ?></td>
                            <td><?= htmlspecialchars($buku['nama_kategori']) ?></td>
                            <td><?= $buku['tahun'] ?></td>
                            <td>
                                <span class="badge <?= $buku['status'] == 'Tersedia' ? 'badge-success' : 'badge-warning' ?>">
                                    <?= $buku['status'] ?>
                                </span>
                            </td>
                            <td>
                                <!-- Aksi Pinjam Khusus untuk Anggota (Siswa) -->
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Anggota'): ?>
                                    <?php if ($buku['status'] === 'Tersedia'): ?>
                                        <a href="index.php?page=buku&action=pinjam&id_buku=<?= $buku['id_buku'] ?>" 
                                           class="btn-primary" 
                                           style="padding: 6px 12px; font-size: 12px; text-decoration: none; display: inline-block;"
                                           onclick="return confirm('Apakah Anda yakin ingin meminjam buku ini?')">
                                            Pinjam Buku
                                        </a>
                                    <?php else: ?>
                                        <button disabled style="padding: 6px 12px; font-size: 12px; border-radius: 4px; border:none; background:#e5e7eb; color:#9ca3af;">Sedang Dipinjam</button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Tombol Edit (Buka Modal Popup) -->
                                <button type="button" 
                                        onclick="openModalEdit('<?= $buku['id_buku'] ?>', '<?= htmlspecialchars($buku['judul_buku'], ENT_QUOTES) ?>', '<?= htmlspecialchars($buku['pengarang'], ENT_QUOTES) ?>', '<?= $buku['id_kategori'] ?>', '<?= $buku['tahun'] ?>')" 
                                        style="background-color: #161515; color: white; border: none; padding: 4px 8px; font-size: 12px; border-radius: 4px; cursor: pointer;">
                                     Edit
                                </button>

                                <!-- Form Hapus (Mengarah ke controller/proses_hapus.php) -->
                                <form action="controller/proses_hapus.php" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id" value="<?= $buku['id_buku'] ?>">
                                    <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 4px 8px; font-size: 12px; border-radius: 4px; cursor: pointer; margin-left: 4px;">
                                         Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align:center;">Belum ada data buku.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL POPUP TAMBAH BUKU -->
<?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'Admin' || $_SESSION['user']['role'] === 'Petugas')): ?>
<div class="modal-overlay" id="modalBuku" style="display:none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Tambah Buku Baru</h3>
            <button class="close-btn" onclick="closeModalTambah()">&times;</button>
        </div>
        <form action="index.php?page=buku" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tambah_buku" value="1">
            
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" class="form-control" required placeholder="Contoh: Laskar Pelangi">
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required placeholder="Nama pengarang">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($kategoriList)): ?>
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun" class="form-control" required value="2024">
            </div>

            <div class="form-group">
                <label>File Cover Gambar</label>
                <input type="file" name="cover" class="form-control" accept="image/*" required>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn-primary" style="background:#6b7280" onclick="closeModalTambah()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL POPUP EDIT BUKU -->
<div class="modal-overlay" id="modalEditBuku" style="display:none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Edit Data Buku</h3>
            <button class="close-btn" onclick="closeModalEdit()">&times;</button>
        </div>
        <form action="controller/proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_buku" id="edit_id_buku">
            
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" id="edit_judul_buku" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" id="edit_pengarang" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" id="edit_id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($kategoriList)): ?>
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun" id="edit_tahun" class="form-control" required>
            </div>

            <div class="form-group">
                <label>File Cover Gambar (Kosongkan jika tidak diubah)</label>
                <input type="file" name="cover" class="form-control" accept="image/*">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn-primary" style="background:#6b7280" onclick="closeModalEdit()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambah() {
        document.getElementById('modalBuku').style.display = 'flex';
    }
    function closeModalTambah() {
        document.getElementById('modalBuku').style.display = 'none';
    }

    function openModalEdit(id, judul, pengarang, kategori, tahun) {
        document.getElementById('edit_id_buku').value = id;
        document.getElementById('edit_judul_buku').value = judul;
        document.getElementById('edit_pengarang').value = pengarang;
        document.getElementById('edit_id_kategori').value = kategori;
        document.getElementById('edit_tahun').value = tahun;
        document.getElementById('modalEditBuku').style.display = 'flex';
    }
    function closeModalEdit() {
        document.getElementById('modalEditBuku').style.display = 'none';
    }
</script>
<?php endif; ?>