<?php
namespace App\View;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['role'])) {
    $_SESSION['role'] = $_POST['role'];
}
?>
<html>
<body>
    <h1>Reparaciones de Autos</h1>
    <form method="post" action="../Controller/ControllerReparation.php" name="formSearchReparation">
        <label for="uuid">ID de Reparación:</label>
        <input type="text" id="uuid" name="uuid">
        <input type="submit" name="getReparation" value="Buscar">
    </form>
</body>
</html>
<?php

class ViewReparation {
    public function render($reparation) {
        echo "<h1>Detalles de Reparación</h1>";
        if ($reparation) {
            echo "<p>ID: {$reparation['id_reparation']}</p>";
            echo "<p>Taller: {$reparation['name_workshop']}</p>";
            echo "<p>Fecha: {$reparation['register_date']}</p>";
            echo "<p>Matrícula: {$reparation['license_plate']}</p>";
        } else {
            echo "<p>No se encontró la reparación.</p>";
        }
    }
}
?>
<?php 
if (isset($_SESSION["role"]) && $_SESSION["role"] == "employee") {
?>
    <h2>Crear Nueva Reparación</h2>
    <form method="POST" action="../Controller/ControllerReparation.php">
        <label for="name_workshop">Taller:</label>
        <input type="text" id="name_workshop" name="name_workshop" required><br><br>

        <label for="register_date">Fecha de Registro:</label>
        <input type="date" id="register_date" name="register_date" required><br><br>

        <label for="license_plate">Matrícula:</label>
        <input type="text" id="license_plate" name="license_plate" required><br><br>

        <input type="submit" name="createReparation" value="Crear Reparación">
    </form>
<?php
}
?>
