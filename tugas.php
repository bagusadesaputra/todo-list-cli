<?php

require_once __DIR__ . "/table.php";

$tugas_path = __DIR__ . "/task.json";

// Cek apakah ada argumen aksi yang diberikan
if (!isset($argv[1])) {
    echo "Gunakan: php tugas.php [aksi] [parameter]\n";
    echo "Contoh:\n";
    echo "  php tugas.php tambah \"Beli susu\"\n";
    echo "  php tugas.php daftar\n";
    echo "  php tugas.php update 1 done\n";
    echo "  php tugas.php edit 1 \"Beli susu segar\"\n";
    echo "  php tugas.php hapus 1\n";
    exit(1);
}

$aksi = strtolower($argv[1]);

switch ($aksi) {
    case 'tambah':
        if (!isset($argv[2]) || trim($argv[2]) === '') {
            echo "Tugas dibutuhkan!\n";
            exit(1);
        }

        $tugas = trim($argv[2]);
        $curr_tugas = [];

        // Baca file task.json jika sudah ada
        if (file_exists($tugas_path)) {
            $tugas_file = file_get_contents($tugas_path);
            $decoded = json_decode($tugas_file, true);
            if (is_array($decoded)) {
                $curr_tugas = $decoded;
            }
        }

        // Hitung ID berikutnya secara otomatis
        $highest_id = 0;
        if (!empty($curr_tugas)) {
            $ids = array_column($curr_tugas, 'id');
            if (!empty($ids)) {
                $highest_id = max($ids);
            }
        }

        $tanggal = date('Y-m-d H:i:s');
        $data = [
            "id" => $highest_id + 1,
            "tugas" => $tugas,
            "status" => "Todo",
            "created_at" => $tanggal,
            "updated_at" => null
        ];

        $curr_tugas[] = $data;

        // Simpan kembali ke file
        file_put_contents(
            $tugas_path,
            json_encode($curr_tugas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        echo "Tugas berhasil ditambahkan! [ID: {$data['id']}] {$data['tugas']}\n";
        break;

    case 'daftar':
    case 'list':
        $curr_tugas = [];
        if (file_exists($tugas_path)) {
            $tugas_file = file_get_contents($tugas_path);
            $decoded = json_decode($tugas_file, true);
            if (is_array($decoded)) {
                $curr_tugas = $decoded;
            }
        }

        cetak_tabel_tugas($curr_tugas);
        break;

    case 'hapus':
        if (!isset($argv[2]) || !is_numeric($argv[2])) {
            echo "ID tugas dibutuhkan dan harus berupa angka!\n";
            echo "Contoh: php tugas.php hapus 1\n";
            exit(1);
        }

        $id_hapus = (int)$argv[2];
        $curr_tugas = [];
        if (file_exists($tugas_path)) {
            $tugas_file = file_get_contents($tugas_path);
            $decoded = json_decode($tugas_file, true);
            if (is_array($decoded)) {
                $curr_tugas = $decoded;
            }
        }

        $ditemukan = false;
        $tugas_dihapus = null;
        $tugas_tersisa = [];

        foreach ($curr_tugas as $item) {
            if (isset($item['id']) && (int)$item['id'] === $id_hapus) {
                $ditemukan = true;
                $tugas_dihapus = $item;
            } else {
                $tugas_tersisa[] = $item;
            }
        }

        if (!$ditemukan) {
            echo "Tugas dengan ID {$id_hapus} tidak ditemukan!\n";
            exit(1);
        }

        file_put_contents(
            $tugas_path,
            json_encode($tugas_tersisa, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        echo "Tugas berhasil dihapus! [ID: {$id_hapus}] {$tugas_dihapus['tugas']}\n";
        break;

    case 'update':
        if (!isset($argv[2]) || !is_numeric($argv[2])) {
            echo "ID tugas dibutuhkan dan harus berupa angka!\n";
            echo "Contoh: php tugas.php update 1 in-progress\n";
            exit(1);
        }

        if (!isset($argv[3])) {
            echo "Status baru dibutuhkan! Pilihan status: todo, in-progress, done\n";
            echo "Contoh: php tugas.php update 1 done\n";
            exit(1);
        }

        $id_update = (int)$argv[2];
        $input_status = strtolower(trim($argv[3]));

        // Pemetaan status yang valid dan format standarnya
        $valid_status_map = [
            'todo' => 'Todo',
            'in-progress' => 'In Progress',
            'inprogress' => 'In Progress',
            'done' => 'Done',
            'selesai' => 'Done'
        ];

        if (!array_key_exists($input_status, $valid_status_map)) {
            echo "Status tidak valid: '{$argv[3]}'\n";
            echo "Status yang diperbolehkan: todo, in-progress, done\n";
            exit(1);
        }

        $status_baru = $valid_status_map[$input_status];

        $curr_tugas = [];
        if (file_exists($tugas_path)) {
            $tugas_file = file_get_contents($tugas_path);
            $decoded = json_decode($tugas_file, true);
            if (is_array($decoded)) {
                $curr_tugas = $decoded;
            }
        }

        $ditemukan = false;
        foreach ($curr_tugas as &$item) {
            if (isset($item['id']) && (int)$item['id'] === $id_update) {
                $item['status'] = $status_baru;
                $item['updated_at'] = date('Y-m-d H:i:s');
                $ditemukan = true;
                break;
            }
        }
        unset($item);

        if (!$ditemukan) {
            echo "Tugas dengan ID {$id_update} tidak ditemukan!\n";
            exit(1);
        }

        file_put_contents(
            $tugas_path,
            json_encode($curr_tugas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        echo "Status tugas [ID: {$id_update}] berhasil diubah menjadi: {$status_baru}\n";
        break;

    case 'edit':
        if (!isset($argv[2]) || !is_numeric($argv[2])) {
            echo "ID tugas dibutuhkan dan harus berupa angka!\n";
            echo "Contoh: php tugas.php edit 1 \"Beli susu segar\"\n";
            exit(1);
        }

        if (!isset($argv[3]) || trim($argv[3]) === '') {
            echo "Teks tugas baru dibutuhkan!\n";
            exit(1);
        }

        $id_edit = (int)$argv[2];
        $tugas_baru = trim($argv[3]);

        $curr_tugas = [];
        if (file_exists($tugas_path)) {
            $tugas_file = file_get_contents($tugas_path);
            $decoded = json_decode($tugas_file, true);
            if (is_array($decoded)) {
                $curr_tugas = $decoded;
            }
        }

        $ditemukan = false;
        foreach ($curr_tugas as &$item) {
            if (isset($item['id']) && (int)$item['id'] === $id_edit) {
                $item['tugas'] = $tugas_baru;
                $item['updated_at'] = date('Y-m-d H:i:s');
                $ditemukan = true;
                break;
            }
        }
        unset($item);

        if (!$ditemukan) {
            echo "Tugas dengan ID {$id_edit} tidak ditemukan!\n";
            exit(1);
        }

        file_put_contents(
            $tugas_path,
            json_encode($curr_tugas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        echo "Tugas [ID: {$id_edit}] berhasil diperbarui menjadi: \"{$tugas_baru}\"\n";
        break;

    default:
        echo "Aksi tidak diketahui: '{$aksi}'\n";
        echo "Aksi yang tersedia saat ini: tambah, daftar, update, edit, hapus\n";
        exit(1);
}
