<?php include 'views/layout/header.php'; ?>
<div class="layout">
    <?php include 'views/layout/sidebar.php'; ?>
    <div class="main-content">
        <h2>Edit Data Buku</h2>
        
        <!-- Form mengarah ke controller/proses_edit.php -->
        <form action="controller/proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" class="form-control" value="<?= htmlspecialchars($buku['judul_buku']) ?>" required>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" class="form-control" value="<?= htmlspecialchars($buku['pengarang']) ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?= $kat['id_kategori'] ?>" <?= $kat['id_kategori'] == $buku['id_kategori'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun" class="form-control" value="<?= $buku['tahun'] ?>" required>
            </div>

            <div class="form-group">
                <label>Cover (Kosongkan jika tidak diganti)</label>
                <input type="file" name="cover" class="form-control" accept="image/*">
            </div>

            <div style="margin-top: 20px;">
                <a href="index.php?page=buku" class="btn-primary" style="background:#6b7280; text-decoration:none;">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>