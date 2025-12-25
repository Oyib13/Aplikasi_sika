<?php
require_once "Database.php";

class FormCRUD {

    /* ================= HANDLE REQUEST ================= */
    public static function handleRequest($table, $fields, $primaryKey = "id") {
        $db = Database::connect();

        /* ===== DELETE ===== */
        if (isset($_GET['delete'])) {

            if ($table === "mahasiswa") {
                $stmt = $db->prepare("SELECT id_user FROM mahasiswa WHERE $primaryKey=?");
                $stmt->execute([$_GET['delete']]);
                $id_user = $stmt->fetchColumn();

                if ($id_user) {
                    $db->prepare("DELETE FROM user WHERE id_user=?")
                       ->execute([$id_user]);
                }
            }

            $db->prepare("DELETE FROM $table WHERE $primaryKey=?")
               ->execute([$_GET['delete']]);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        /* ===== CREATE ===== */
        if (isset($_POST['create'])) {

            if ($table === "mahasiswa") {
                try {
                    $db->beginTransaction();

                    // 1️⃣ CREATE USER
                    $stmtUser = $db->prepare("
                        INSERT INTO user (username, pasword, role)
                        VALUES (?, ?, 'mahasiswa')
                    ");
                    $stmtUser->execute([
                        $_POST['NIM'],
                        password_hash($_POST['NIM'], PASSWORD_DEFAULT)
                    ]);

                    $id_user = $db->lastInsertId();

                    // 2️⃣ CREATE MAHASISWA
                    $cols  = implode(",", $fields);
                    $marks = rtrim(str_repeat("?,", count($fields)), ",");
                    $stmtMhs = $db->prepare("INSERT INTO mahasiswa ($cols) VALUES ($marks)");

                    $vals = [];
                    foreach ($fields as $f) {
                        $vals[] = ($f === "id_user") ? $id_user : ($_POST[$f] ?? null);
                    }

                    $stmtMhs->execute($vals);
                    $db->commit();

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;

                } catch (Exception $e) {
                    $db->rollBack();
                    die("Gagal simpan mahasiswa: " . $e->getMessage());
                }
            }

            // CREATE NORMAL
            $cols  = implode(",", $fields);
            $marks = rtrim(str_repeat("?,", count($fields)), ",");
            $stmt  = $db->prepare("INSERT INTO $table ($cols) VALUES ($marks)");

            $vals = [];
            foreach ($fields as $f) {
                $vals[] = $_POST[$f] ?? null;
            }

            $stmt->execute($vals);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        /* ===== UPDATE ===== */
        if (isset($_POST['update'])) {

            if ($table === "mahasiswa") {
                $stmt = $db->prepare("SELECT id_user FROM mahasiswa WHERE $primaryKey=?");
                $stmt->execute([$_POST[$primaryKey]]);
                $id_user = $stmt->fetchColumn();

                if ($id_user) {
                    $db->prepare("
                        UPDATE user 
                        SET username=?, password=?
                        WHERE id_user=?
                    ")->execute([
                        $_POST['nim'],
                        password_hash($_POST['nim'], PASSWORD_DEFAULT),
                        $id_user
                    ]);
                }
            }

            $set = implode("=?, ", $fields) . "=?";
            $stmt = $db->prepare("UPDATE $table SET $set WHERE $primaryKey=?");

            $vals = [];
            foreach ($fields as $f) {
                $vals[] = $_POST[$f] ?? null;
            }
            $vals[] = $_POST[$primaryKey];

            $stmt->execute($vals);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    /* ================= RENDER ================= */
    public static function render($table, $fields, $primaryKey = "id", $classMap = [], $predefined = []) {
        $db = Database::connect();

        $editData = null;
        if (isset($_GET['edit'])) {
            $stmt = $db->prepare("SELECT * FROM $table WHERE $primaryKey=?");
            $stmt->execute([$_GET['edit']]);
            $editData = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $rows = $db->query("SELECT * FROM $table ORDER BY $primaryKey DESC")
                   ->fetchAll(PDO::FETCH_ASSOC);

        $inputClass  = $classMap['input'] ?? 'form-control';
        $selectClass = $classMap['select'] ?? 'form-control';
        $btnCreate   = $classMap['button_create'] ?? 'btn btn-success';
        $btnUpdate   = $classMap['button_update'] ?? 'btn btn-primary';
        $tableClass  = $classMap['table'] ?? 'table table-bordered table-hover';

        echo "<div class='card shadow mb-4'>";
        echo "<div class='card-header'><h6 class='fw-bold text-primary'>Form $table</h6></div>";
        echo "<div class='card-body'>";

        /* ===== FORM ===== */
        echo "<form method='POST'>";
        if ($editData) {
            echo "<input type='hidden' name='$primaryKey' value='{$editData[$primaryKey]}'>";
        }

        foreach ($fields as $f) {
            if ($f === "id_user") continue; // 🔥 sembunyikan

            echo "<div class='mb-3'>";
            echo "<label class='form-label'>".ucfirst($f)."</label>";
            $value = $editData[$f] ?? '';

            if (isset($predefined[$f])) {
                echo "<select name='$f' class='$selectClass' required>";
                echo "<option value=''>-- Pilih --</option>";
                foreach ($predefined[$f] as $opt) {
                    $sel = ($value == $opt) ? "selected" : "";
                    echo "<option value='$opt' $sel>$opt</option>";
                }
                echo "</select>";
            } else {
                echo "<input type='text' name='$f' value='$value' class='$inputClass' required>";
            }

            echo "</div>";
        }

        echo $editData
            ? "<button name='update' class='$btnUpdate'>Update</button>"
            : "<button name='create' class='$btnCreate'>Simpan</button>";

        echo "</form><hr>";

        /* ===== TABLE ===== */
        echo "<div class='table-responsive'>";
        echo "<table class='$tableClass'><thead><tr>";

        foreach ($fields as $f) {
            if (!str_contains($f, 'id')) {
                echo "<th>".ucfirst($f)."</th>";
            }
        }
        echo "<th>Aksi</th></tr></thead><tbody>";

        foreach ($rows as $r) {
            echo "<tr>";
            foreach ($fields as $f) {
                if (str_contains($f, 'id')) continue;
                echo "<td>{$r[$f]}</td>";
            }

            echo "<td>
                    <a href='?edit={$r[$primaryKey]}' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='?delete={$r[$primaryKey]}' 
                       class='btn btn-sm btn-danger'
                       onclick=\"return confirm('Hapus data?')\">Hapus</a>
                  </td></tr>";
        }

        echo "</tbody></table></div>";
        echo "</div></div>";
    }
}
