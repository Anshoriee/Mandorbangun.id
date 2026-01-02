<?php require_once '../includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="content-header">
        <h2>Kelola Alur Kerja</h2>
        <a href="alur_kerja_form.php" class="btn-add">Tambah Gambar Baru</a>
    </div>

    <?php include '../includes/admin_notifications.php'; ?>

    <p class="drag-info"><i class="fas fa-arrows-alt"></i> Klik dan seret pada kolom "Urutan" untuk memindahkan baris.
    </p>

    <table class="draggable-table">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center;">Urutan</th>
                <th>Gambar</th>
                <th>Judul (Opsional)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="sortable-rows">
            <?php
            $sql = "SELECT * FROM alur_kerja ORDER BY order_num ASC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr data-id='" . $row['id'] . "'>";
                    echo "<td data-label='Urutan' class='order-handle' style='text-align: center;'>" . htmlspecialchars($row['order_num']) . "</td>";
                    echo "<td data-label='Gambar'><img src='" . BASE_URL . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "' height='50'></td>";
                    echo "<td data-label='Judul'>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td data-label='Aksi'>
                            <a href='alur_kerja_form.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                             <form action='process.php' method='POST' style='display:inline-block;'>
                                <input type='hidden' name='action' value='delete_alur_kerja_item'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='button' class='btn-delete delete-trigger-btn'>Hapus</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Belum ada gambar di alur kerja.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sortableBody = document.getElementById('sortable-rows');
        if (sortableBody) {
            new Sortable(sortableBody, {
                animation: 150,
                handle: '.order-handle',
                onEnd: function (evt) {
                    const rows = sortableBody.querySelectorAll('tr');
                    const orderData = [];
                    rows.forEach((row, index) => {
                        orderData.push({
                            id: row.dataset.id,
                            position: index + 1
                        });
                    });

                    fetch('process_ordering.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            type: 'alur_kerja', // Menggunakan tipe 'alur_kerja'
                            order: orderData
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                rows.forEach((row, index) => {
                                    row.querySelector('.order-handle').textContent = index + 1;
                                });
                                console.log('Urutan alur kerja berhasil disimpan!');
                            }
                        });
                }
            });
        }
    });
</script>

<?php require_once '../includes/admin_footer.php'; ?>