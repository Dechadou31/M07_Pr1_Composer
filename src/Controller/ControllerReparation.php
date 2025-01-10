<?php
namespace App\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Service\ServiceReparation; 
use App\View\ViewReparation;


if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(isset($_POST["getReparation"])){
    getReparation();
}

if(isset($_POST["createReparation"])){
    createReparation();
}
    function getReparation(): void {
        
        $role = $_SESSION['role'];
        $idReparation = htmlspecialchars(trim($_POST['uuid']));

        $service = new ServiceReparation();
        $reparation = $service->getReparation($role, $idReparation);

        $view = new ViewReparation();
        $view->render($reparation);
    }

    function createReparation(): void {
  
    $nameWorkshop = htmlspecialchars(trim($_POST['name_workshop']));
    $registerDate = htmlspecialchars(trim($_POST['register_date']));
    $licensePlate = htmlspecialchars(trim($_POST['license_plate']));

    $service = new ServiceReparation();

    $isCreated = $service->createReparation($nameWorkshop, $registerDate, $licensePlate);

    if ($isCreated) {
        echo "<script>alert('Reparación creada con éxito.'); window.location.href='../view/ViewReparation.php';</script>";
    } else {
        echo "<script>alert('Error al crear la reparación.'); window.history.back();</script>";
    }
}

