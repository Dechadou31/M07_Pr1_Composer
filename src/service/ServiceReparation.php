<?php
namespace App\Service;

use App\Model\Reparation;
use mysqli;

class ServiceReparation
{
    private $db;

    public function __construct()
    {
        $this->db = $this->connect();
    }

    /**
     * Conexión a la base de datos
     */
    private function connect()
    {
        $config = parse_ini_file(__DIR__ . "/../../conf/db_conf.ini");

        $connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['dbname'],
            $config['port']
        );

        if ($connection->connect_error) {
            die("Error de conexión: " . $connection->connect_error);
        }

        return $connection;
    }

    /**
     * Crear una nueva reparación en la base de datos.
     */
    public function createReparation($nameWorkshop, $registerDate, $licensePlate)
    {
        $uuid = \Ramsey\Uuid\Guid\Guid::uuid4()->toString();

        $stmt = $this->db->prepare("INSERT INTO Reparation (id_reparation, name_workshop, register_date, license_plate) 
                                    VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $uuid, $nameWorkshop, $registerDate, $licensePlate);

        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    /**
     * Buscar una reparación en la base de datos
     */
    public function getReparation($role, $idReparation)
    {
        $this->connect();
        $stmt = $this->db->prepare("SELECT * FROM Reparation WHERE id_reparation = ?");
        $stmt->bind_param("s", $idReparation);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            if ($role === 'client') {
                $data['license_plate'] = '[CENSORED]';
            }

            return $data;
        }

        return false;
    }
}
