<?php

/**
 * Menampilkan daftar tugas dalam bentuk tabel CLI yang rapi.
 *
 * @param array $daftar_tugas Array kumpulan tugas
 * @return void
 */
function cetak_tabel_tugas(array $daftar_tugas): void
{
    if (empty($daftar_tugas)) {
        echo "Belum ada tugas yang tersimpan.\n";
        echo "Gunakan: php tugas.php tambah \"[nama tugas]\" untuk menambahkan tugas baru.\n";
        return;
    }

    $garis  = "+-----+--------------------------------+-------------+---------------------+---------------------+\n";
    $header = sprintf("| %-3s | %-30s | %-11s | %-19s | %-19s |\n", "ID", "Tugas", "Status", "Dibuat Pada", "Diperbarui Pada");

    echo "\n=== DAFTAR TUGAS ===\n";
    echo $garis;
    echo $header;
    echo $garis;

    foreach ($daftar_tugas as $item) {
        $id = $item['id'] ?? '-';
        $nama_tugas = $item['tugas'] ?? '-';

        // Potong teks jika terlalu panjang agar lebar tabel tetap rapi
        if (mb_strlen($nama_tugas) > 28) {
            $nama_tugas = mb_substr($nama_tugas, 0, 25) . '...';
        }

        $status = $item['status'] ?? 'Todo';
        $created_at = $item['created_at'] ?? '-';
        $updated_at = !empty($item['updated_at']) ? $item['updated_at'] : '-';

        echo sprintf(
            "| %-3s | %-30s | %-11s | %-19s | %-19s |\n",
            $id,
            $nama_tugas,
            $status,
            $created_at,
            $updated_at
        );
    }

    echo $garis;
    echo "Total: " . count($daftar_tugas) . " tugas\n\n";
}
