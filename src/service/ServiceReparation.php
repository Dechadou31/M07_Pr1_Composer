<?php
namespace App\Service;

use App\Model\Reparation;
use mysqli;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ServiceReparation
{
    private $db;
    private $logger;

    public function __construct()
    {
        $this->initializeLogger(); // Inicializa el logger antes de cualquier operación
        $this->db = $this->connect();
    }

    /**
     * Inicializar el logger Monolog
     */
    private function initializeLogger()
    {
        $this->logger = new Logger('ServiceReparation');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/service_reparation.log', Logger::DEBUG));
    }

    /**
     * Conexión a la base de datos
     */
    private function connect()
    {
        $config = parse_ini_file(__DIR__ . "/../../conf/db_conf.ini");
    
        try {
            $connection = new mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['dbname'],
                $config['port']
            );
    
            if ($connection->connect_error) {
                // Registrar el error en el logger
                $this->logger->error("Error de conexión a la base de datos: " . $connection->connect_error, [
                    'host' => $config['host'],
                    'dbname' => $config['dbname']
                ]);
    
                // Mostrar un mensaje al usuario
                die("<p>Error de conexión a la base de datos. Por favor, contacte al administrador.</p>");
            }
    
            $this->logger->info("Conexión a la base de datos establecida.");
            return $connection;
    
        } catch (\Exception $e) {
            // Registrar excepciones no controladas
            $this->logger->critical("Excepción crítica al intentar conectar a la base de datos.", [
                'exception' => $e->getMessage(),
                'host' => $config['host'],
                'dbname' => $config['dbname']
            ]);
    
            // Mostrar un mensaje genérico al usuario
            die("<p>Ocurrió un error inesperado al intentar conectar a la base de datos. Por favor, contacte al administrador.</p>");
        }
    }
    

    /**
     * Crear una nueva reparación en la base de datos.
     */
    public function createReparation($nameWorkshop, $registerDate, $licensePlate)
    {
        try {
            // Validar el nombre del taller (longitud máxima: 50)
            if (strlen($nameWorkshop) > 50) {
                $this->logger->warning("El nombre del taller excede la longitud permitida.", [
                    'name_workshop' => $nameWorkshop
                ]);
                return false;
            }
    
            // Validar la fecha de registro (formato: YYYY-MM-DD)
            $datePattern = '/^\d{4}-\d{2}-\d{2}$/';
            if (!preg_match($datePattern, $registerDate)) {
                $this->logger->warning("La fecha de registro no tiene el formato válido.", [
                    'register_date' => $registerDate
                ]);
                return false;
            }
    
            // Validar la matrícula (alfanumérica, longitud máxima: 10)
            if (strlen($licensePlate) > 10 || !ctype_alnum($licensePlate)) {
                $this->logger->warning("La matrícula no es válida.", [
                    'license_plate' => $licensePlate
                ]);
                return false;
            }
    
            // Generar UUID para la reparación
            $uuid = \Ramsey\Uuid\Guid\Guid::uuid4()->toString();
    
            // Preparar consulta de inserción
            $stmt = $this->db->prepare("INSERT INTO Reparation (id_reparation, name_workshop, register_date, license_plate) 
                                        VALUES (?, ?, ?, ?)");
    
            if (!$stmt) {
                // Registrar el error si la preparación falla
                $this->logger->error("Error al preparar la consulta de inserción.", [
                    'error' => $this->db->error,
                    'query' => "INSERT INTO Reparation (id_reparation, name_workshop, register_date, license_plate)"
                ]);
                return false;
            }
    
            $stmt->bind_param("ssss", $uuid, $nameWorkshop, $registerDate, $licensePlate);
    
            if ($stmt->execute()) {
                // Registrar el éxito de la inserción
                $this->logger->info("Reparación creada exitosamente.", [
                    'id_reparation' => $uuid,
                    'name_workshop' => $nameWorkshop,
                    'register_date' => $registerDate,
                    'license_plate' => $licensePlate
                ]);
                return true;
            } else {
                // Registrar el error si la ejecución de la consulta falla
                $this->logger->error("Error al insertar la reparación en la base de datos.", [
                    'error' => $stmt->error,
                    'name_workshop' => $nameWorkshop,
                    'register_date' => $registerDate,
                    'license_plate' => $licensePlate
                ]);
                return false;
            }
        } catch (\Exception $e) {
            // Registrar cualquier excepción que ocurra durante la inserción
            $this->logger->critical("Excepción al intentar crear una reparación.", [
                'exception' => $e->getMessage(),
                'name_workshop' => $nameWorkshop,
                'register_date' => $registerDate,
                'license_plate' => $licensePlate
            ]);
            return false;
        }
    }
    
    

    /**
     * Buscar una reparación en la base de datos
     */
    public function getReparation($role, $idReparation)
    {
        $stmt = $this->db->prepare("SELECT * FROM Reparation WHERE id_reparation = ?");
        $stmt->bind_param("s", $idReparation);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            if ($role === 'client') {
                $data['license_plate'] = '[CENSORED]';
            }

            $this->logger->info("Reparación encontrada.", [
                'id_reparation' => $idReparation,
                'data' => $data,
            ]);
            return $data;
        }

        $this->logger->warning("No se encontró ninguna reparación con el ID proporcionado.", [
            'id_reparation' => $idReparation,
        ]);
        return false;
    }
}
