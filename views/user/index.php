<?php include 'views/layout/header.php'; ?>
<div class="layout">
    <?php include 'views/layout/sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div>
                <h2>Kelola Anggota / Pengguna</h2>
                <p style="color: var(--text-muted); font-size: 14px;">Daftar seluruh pengguna dan siswa yang terdaftar di sistem.</p>
            </div>
        </div>

        <div class="card-table" style="margin-top: 20px;">
            <table>
                <thead>
                    <tr>
                        <th>ID User</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role / Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($userList)): ?>
                        <?php foreach ($userList as $usr): ?>
                        <tr>
                            <td><strong>#<?= $usr['id_user'] ?? $usr['id'] ?></strong></td>
                            <td><?= htmlspecialchars($usr['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($usr['email']) ?></td>
                            <td>
                                <span class="badge <?= ($usr['role'] == 'Admin') ? 'badge-success' : 'badge-warning' ?>">
                                    <?= htmlspecialchars($usr['role']) ?>
                                </span>
                            </td>
                            <td>
                                <!-- Form Hapus User diarahkan ke routing index.php?page=hapus-user -->
                                <form action="index.php?page=hapus-user" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
                                    <input type="hidden" name="id" value="<?= $usr['id_user'] ?? $usr['id'] ?>">
                                    <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 4px 8px; font-size: 12px; border-radius: 4px; cursor: pointer;">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;">Belum ada data anggota.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; // Sesuaikan jika ada file footer ?>