<?php require_once '../includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="content-header">
        <h2>Kelola Popup Ads</h2>
        <a href="popup_form.php" class="btn-add">Tambah Popup Baru</a>
    </div>

    <?php include '../includes/admin_notifications.php'; ?>

    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Link</th>
                <th>Status</th>
                <th>Delay (ms)</th>
                <th>Frekuensi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM popup ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status_badge = $row['is_active'] ?
                        '<span style="background: #198754; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">Aktif</span>' :
                        '<span style="background: #dc3545; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">Nonaktif</span>';

                    $frequency_text = '';
                    switch ($row['show_frequency']) {
                        case 'always':
                            $frequency_text = 'Selalu';
                            break;
                        case 'once_per_session':
                            $frequency_text = 'Sekali per sesi';
                            break;
                        case 'once_per_day':
                            $frequency_text = 'Sekali per hari';
                            break;
                    }

                    echo "<tr>";
                    echo "<td data-label='Gambar'><img src='" . BASE_URL . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "' style='height: 50px; width: auto; border-radius: 4px;'></td>";
                    echo "<td data-label='Judul'>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td data-label='Link'>" . (empty($row['link_url']) ? '-' : '<a href="' . htmlspecialchars($row['link_url']) . '" target="_blank" style="color: #0d6efd;">Link</a>') . "</td>";
                    echo "<td data-label='Status'>" . $status_badge . "</td>";
                    echo "<td data-label='Delay (ms)'>" . number_format($row['show_delay']) . "</td>";
                    echo "<td data-label='Frekuensi'>" . $frequency_text . "</td>";
                    echo "<td data-label='Aksi'>
                                <a href='popup_form.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                                <form action='process.php' method='POST' style='display:inline-block;'>
                                <input type='hidden' name='action' value='delete_popup'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='button' class='btn-delete delete-trigger-btn'>Hapus</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Belum ada popup ads.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/admin_footer.php'; ?>