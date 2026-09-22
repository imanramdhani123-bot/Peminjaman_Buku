<?php include 'views/layout/header.php'; ?>
<div class="layout">
    <?php include 'views/layout/sidebar.php'; ?>
    <div class="main-content">
        <h2>Data Peminjaman Buku Aktif</h2>
        <br>
        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>ID Pinjam</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($peminjamanList)): ?>
                        <?php foreach($peminjamanList as $p): ?>
                        <tr>
                            <td><strong>#<?= $p['id_pinjam'] ?></strong></td>
                            <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($p['judul_buku']) ?></td>
                            <td><?= $p['tgl_pinjam'] ?></td>
                            <td><?= $p['tgl_jatuh_tempo'] ?></td>
                            <td>
                                <span class="badge <?= $p['status'] === 'Kembali' ? 'badge-success' : ($p['status'] === 'Terlambat' ? 'badge-danger' : 'badge-warning') ?>">
                                    <?= $p['status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?page=detail-peminjaman&id=<?= $p['id_pinjam'] ?>" style="margin-right: 8px;">Detail</a>
                                
                                <?php if ($p['status'] !== 'Kembali'): ?>
                                    <a href="index.php?page=peminjaman&action=kembali&id=<?= $p['id_pinjam'] ?>" 
                                       class="btn-primary" 
                                       style="padding: 4px 8px; font-size: 12px; text-decoration: none; background: #e11d48;"
                                       onclick="return confirm('Proses pengembalian buku ini?')">
                                        Kembalikan
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;">Belum ada riwayat peminjaman.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>