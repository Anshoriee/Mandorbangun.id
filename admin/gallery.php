<?php require_once '../includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="content-header">
        <h2>Kelola Galeri</h2>
        <a href="gallery_form.php" class="btn-add">Tambah Gambar Baru</a>
    </div>

    <?php include '../includes/admin_notifications.php'; ?>

    <p class="drag-info"><i class="fas fa-arrows-alt"></i> Klik dan seret pada kolom "Urutan" untuk memindahkan baris.
    </p>

    <table class="draggable-table">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center;">Urutan</th>
                <th>Gambar</th>
                <th>Judul Gambar</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="sortable-rows">
            <?php
            // Mengambil data item galeri beserta nama kategorinya
            $sql = "SELECT gi.id, gi.title, gi.image_path, gi.order_num, gc.title as category_title 
                    FROM gallery_items gi
                    JOIN gallery_categories gc ON gi.category_id = gc.id
                    ORDER BY gi.order_num ASC, gi.id DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr data-id='" . $row['id'] . "'>";
                    echo "<td data-label='Urutan' class='order-handle' style='text-align: center;'>" . htmlspecialchars($row['order_num']) . "</td>";
                    echo "<td data-label='Gambar'><img src='" . BASE_URL . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "' height='50'></td>";
                    echo "<td data-label='Judul Gambar'>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td data-label='Kategori'>" . htmlspecialchars($row['category_title']) . "</td>";
                    echo "<td data-label='Aksi'>
                            <a href='gallery_form.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                            <form action='process.php' method='POST' style='display:inline-block;'>
                                <input type='hidden' name='action' value='delete_gallery_item'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='button' class='btn-delete delete-trigger-btn'>Hapus</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Belum ada gambar di galeri.</td></tr>";
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
                        orderData.push({ id: row.dataset.id, position: index + 1 });
                    });

                    fetch('process_ordering.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ type: 'gallery_items', order: orderData })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                rows.forEach((row, index) => {
                                    row.querySelector('.order-handle').textContent = index + 1;
                                });
                            }
                        });
                }
            });
        }
    });
</script>

<?php require_once '../includes/admin_footer.php'; ?>