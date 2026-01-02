<?php require_once '../includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="content-header">
        <h2>Kelola Layanan</h2>
        <a href="service_form.php" class="btn-add">Tambah Layanan Baru</a>
    </div>

    <?php include '../includes/admin_notifications.php'; ?>

    <p class="drag-info"><i class="fas fa-arrows-alt"></i> Klik dan seret pada kolom "Urutan" untuk memindahkan baris.
    </p>

    <table class="draggable-table">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center;">Urutan</th>
                <th>Ikon</th>
                <th>Nama Layanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="sortable-rows">
            <?php
            $sql = "SELECT id, icon_class, title, order_num FROM services ORDER BY order_num ASC, id DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr data-id='" . $row['id'] . "'>";
                    echo "<td data-label='Urutan' class='order-handle' style='text-align: center;'>" . htmlspecialchars($row['order_num']) . "</td>";
                    echo "<td data-label='Ikon'><i class='" . htmlspecialchars($row['icon_class']) . "'></i></td>";
                    echo "<td data-label='Nama Layanan'>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td data-label='Aksi'>
                            <a href='service_form.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                            <form action='process.php' method='POST' style='display:inline-block;'>
                                <input type='hidden' name='action' value='delete_service'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='button' class='btn-delete delete-trigger-btn'>Hapus</button>
                            </form>
                           </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Belum ada data layanan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Library SortableJS dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Script untuk inisialisasi drag-and-drop -->
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
                    rows.forEach((item, index) => {
                        orderData.push({
                            id: item.dataset.id,
                            position: index + 1
                        });
                    });

                    fetch('process_ordering.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ type: 'services', order: orderData })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                rows.forEach((row, index) => {
                                    row.querySelector('.order-handle').textContent = index + 1;
                                });
                                console.log('Urutan layanan berhasil disimpan!');
                            } else {
                                alert('Gagal menyimpan urutan: ' + (data.message || 'Error tidak diketahui'));
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghubungi server.');
                        });
                }
            });
        }
    });
</script>

<?php require_once '../includes/admin_footer.php'; ?>