<?php

namespace Config;
use PDO;
use PDOException;

class ConfigConnect{

    private static $instance = null; //En este caso haremos que la instancia de la clase siempre sea una sola con el patron Singleton
    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dsn;
    public $conn;
    private const CONFIG_PATH = __DIR__ . "/data_config.json"; //Ruta al Json, me causo muchos problemas JAJAJA LOL ☠️

    public function __construct() { //No pasamos parametros al constructor ya que el codigo sera el mismo, y estos se pasan cuando la clase es configurable o reutilizable

        $this->setConfigData(self::CONFIG_PATH); //Separamos la responsabilidad a un metodo que se encarga de validar y evaluar el JSON
        $this->dsn = "mysql:host={$this->dbHost};dbname={$this->dbName}";
    }

    public static function getInstance () { // Uso del patron sigleton para obtener la instancia si esta existe
        if (!isset(self::$instance)) { //La variable no esta definida y es null?
            self::$instance = new ConfigConnect();
        }
        return self::$instance;
    }

    private function setConfigData ($configPath) {
        $config = json_decode(file_get_contents($configPath), true);
        $this->dbHost = $config["dbHost"];
        $this->dbName = $config["dbName"];
        $this->dbUser = $config["dbUser"];
        $this->dbPass = $config["dbPass"];
    }

    //Método para obtener la conexion PDO

    public function getConnection() {
        $this->conn = null;
        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT            => true,
            PDO::ATTR_CASE                  => PDO::CASE_NATURAL,
        ];

        try {
            $this->conn = new PDO($this->dsn, $this->dbUser, $this->dbPass, $options);
            // echo "Conexion extablecida Exitosamente";
        } catch (PDOException $e) {
            echo "Erro en la conexion a la BD: " . $e->getMessage();
        }

        return $this->conn;
    }
}
