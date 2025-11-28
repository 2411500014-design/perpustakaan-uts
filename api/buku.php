<?php
session_start();
require "../includes/config.php";

function response($status, $msg, $data = null)
{
    header('Content-Type: application/json');
    echo json_encode([
        "status"  => $status,
        "message" => $msg,
        "data"    => $data
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    response("error", "Gunakan metode GET.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare("SELECT * FROM buku WHERE id_buku = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();
    $stmt->close();

    if (!$buku) {
        response("error", "Buku tidak ditemukan");
    }

    $stmt2 = $mysqli->prepare("
        SELECT kategori.nama_kategori 
        FROM buku_kategori 
        JOIN kategori ON buku_kategori.id_kategori = kategori.id_kategori 
        WHERE buku_kategori.id_buku = ?
    ");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $kategori = [];
    $stmt2->bind_result($nama);
    while ($stmt2->fetch()) {
        $kategori[] = $nama;
    }
    $stmt2->close();

    $data = [
        "id_buku"      => $buku['id_buku'],
        "judul"        => $buku['judul'],
        "pengarang"    => $buku['pengarang'],
        "penerbit"     => $buku['penerbit'],
        "tahun_terbit" => $buku['tahun_terbit'],
        "stok"         => $buku['stok'],
        "kategori"     => $kategori,
        "cover_buku"   => "http://localhost/perpustakaan-uts/uploads/buku/" . $buku['cover_buku']
    ];

    response("success", "Detail buku ditemukan", $data);

}