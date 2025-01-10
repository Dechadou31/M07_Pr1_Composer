<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Workshop Main Menu</title>
</head>
<body>
    <h1>Car Workshop Main Menu</h1>
    <h3>Selecciona tu Rol</h3>
    
    <form method="POST" action="src/view/ViewReparation.php">
        <label for="optionRole">Rol de Usuario:</label>
        <select name="role" id="optionRole" required>
            <option value="client">Cliente</option>
            <option value="employee">Empleado</option>
        </select>
        <br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
