<?php
session_start();
require "../includes/config.php";

function response($status, $msg, $data = null)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status"  => $status,
        "message" => $msg,
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    response("error", "Gunakan metode GET.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $mysqli->query("SELECT * FROM buku WHERE id_buku = $id");
    $buku = $result->fetch_assoc();

    if (!$buku) {
        response("error", "Buku tidak ditemukan");
    }

    $kategori = [];
    $resKat = $mysqli->query("
        SELECT k.nama_kategori 
        FROM buku_kategori bk
        JOIN kategori k ON bk.id_kategori = k.id_kategori 
        WHERE bk.id_buku = $id
    ");
    while ($row = $resKat->fetch_assoc()) {
        $kategori[] = $row['nama_kategori'];
    }

    $data = [
        "id_buku"      => $buku['id_buku'],
        "judul"        => $buku['judul'],
        "penulis"      => $buku['penulis'],
        "penerbit"     => $buku['penerbit'],
        "tahun_terbit" => $buku['tahun_terbit'],
        "stok"         => $buku['stok'],
        "kategori"     => $kategori,
        "cover_buku"   => $buku['cover_buku'] 
            ? "http://localhost/perpustakaan-uts/uploads/buku/" . $buku['cover_buku'] 
            : null
    ];

    response("success", "Detail buku ditemukan", $data);

} else {
    $result = $mysqli->query("SELECT * FROM buku");
    $list = [];

    while ($buku = $result->fetch_assoc()) {
        $list[] = [
            "id_buku"      => $buku['id_buku'],
            "judul"        => $buku['judul'],
            "penulis"      => $buku['penulis'],
            "penerbit"     => $buku['penerbit'],
            "tahun_terbit" => $buku['tahun_terbit'],
            "stok"         => $buku['stok'],
            "cover_buku"   => $buku['cover_buku'] 
                ? "http://localhost/perpustakaan-uts/uploads/buku/" . $buku['cover_buku'] 
                : null
        ];
    }

    response("success", "Daftar semua buku ditemukan", $list);
}
?>
