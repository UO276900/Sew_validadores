<?php
session_start();

class Record {
    private $server;
    private $user;
    private $pass;
    private $dbname;
    private $db;

    public function __construct() {
        $this->server = "localhost";
        $this->user = "DBUSER2023";
        $this->pass = "DBPSWD2023";
        $this->dbname = "records";
        
        $this->crearConexion();
        $this->createTableAndDataBase();
        $this->db->select_db("records");
    }

    public function createTableAndDataBase() {
        $this->db = new mysqli($this->server, $this->user, $this->pass);

        $queryCrearDatabase = "CREATE DATABASE IF NOT EXISTS records COLLATE utf8_spanish_ci;";
        $this->executeQuery($queryCrearDatabase);

        $this->db->select_db("records");

        $this->db->query("CREATE TABLE IF NOT EXISTS Registro (
                            nombre VARCHAR(32) NOT NULL,
                            apellidos VARCHAR(32) NOT NULL,
                            nivel VARCHAR(32) NOT NULL,
                            tiempo TIME NOT NULL
        );");
    }
    public function executeQuery($query)
    {
        $res = $this->db->query($query);
        return $res;
    }

    public function getTopRecords($nivel) {
        $this->db = new mysqli($this->server, $this->user, $this->pass);
        $this->db->select_db("records");
        $query = "SELECT nombre, apellidos, tiempo
              FROM Registro
              WHERE nivel = ?
              ORDER BY tiempo
              LIMIT 10";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $nivel);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = "";
        if ($result->num_rows > 0) {
            $list .= "<ol>";
            while ($row = $result->fetch_assoc()) {
                $list .= "<li>";
                $list .= $row["nombre"] . " " . $row["apellidos"] . " - " . $row["tiempo"];
                $list .= "</li>";
            }
            $list .= "</ol>";
        } else {
            $list .= "<p>No hay récords para este nivel.</p>";
        }
        $stmt->close();
        return $list;
    }

    public function insertRecord($nombre, $apellidos, $nivel, $time){
        $this->db = new mysqli($this->server, $this->user, $this->pass);
        $this->db->select_db("records");
        $tiempo = date('H:i:s', strtotime($time));
        $query = "INSERT INTO Registro(nombre, apellidos, nivel, tiempo) VALUES ('$nombre', '$apellidos', '$nivel', '$tiempo')";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stmt->close();
    }

    public function crearConexion()
    {
        $this->db = new mysqli($this->server, $this->user, $this->pass);
    }

    public function cerrarConexion()
    {
        $this->db->close();
    }
    

}

$db = new Record();
$topRecords = " ";
if ($topRecords === " ") {
    $topRecords = "<p> ¡Completa el crucigrama para ver los records! </p>";
}

if (count($_POST) > 0) {
    if (isset($_POST['insertar_registro'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $nivel = $_POST['nivel'];
        $tiempo = $_POST['tiempo'];

        $db->insertRecord($nombre, $apellidos, $nivel, $tiempo);
        $topRecords = $db->getTopRecords($nivel);
    
    }

    $db->cerrarConexion();
    $_SESSION['database'] = $db;
}

echo "
<!DOCTYPE HTML>
<html lang='es'>

<head>
    <!-- Datos que describen el documento -->
    <meta charset='UTF-8' />
    <link rel='icon' type='image/icon' href='multimedia/favicon.ico'>
    <title> Escritorio Virtual - Juegos</title>
    <meta name='author' content='Adrian Estrada González' />
    <meta name='description' content='Crucigrama' />
    <meta name='keywords' content='juego, crucigrama, matematicas, operaciones' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <link rel='stylesheet' type='text/css' href='estilo/estilo.css' />
    <link rel='stylesheet' type='text/css' href='estilo/crucigrama.css' />
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'
        integrity='sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=' crossorigin='anonymous'></script>
    <script src='js/crucigrama.js'> </script>
</head>

<body>
    <!-- Datos con el contenido que aparece en el navegador -->
    <header>
        <h1> Escritorio Virtual </h1>
        <nav>
        <ul>
            <li><a tabindex='1' accesskey='I' href='index.html'>Inicio</a></li>
            <li><a tabindex='2' accesskey='S' href='sobremi.html'>Sobre mi</a></li>
            <li><a tabindex='3' accesskey='N' href='noticias.html'>Noticias</a></li>
            <li><a tabindex='4' accesskey='A' href='agenda.html'>Agenda</a></li>
            <li><a tabindex='5' accesskey='M' href='meteorologia.html'>Meteorología</a></li>
            <li><a tabindex='6' accesskey='J' href='juegos.html'>Juegos</a></li>
            <li><a tabindex='7' accesskey='V' href='viajes.php'>Viajes</a></li>
        </ul>
    </nav>
    </header>

    <main>
        <h2> Crucigrama </h2>
        <section>
            <h3>Elige un juego</h3>
            <ul>
                <li><a href='memoria.html'>Juego de Memoria</a></li>
                <li><a href='sudoku.html'>Sudoku</a></li>
                <li><a href='crucigrama.php'>Crucigrama</a></li>
                <li><a href='api.html'>ApiGame</a></li>
                <li><a href='php/liga.php'> Mi liga de Voleibol </a></li>
            </ul>
        </section>
        <section>
            <h3>Instrucciones</h3>
            <ol>
                <li> Selecciona una casilla </li>
                <li> Introduce un número del 0 al 9 o un operador (*,/,+,-) </li>
                <li> El operador + se corresponde con la suma </li>
                <li> El operador - se corresponde con la resta </li>
                <li> El operador * se corresponde con la multiplicación </li>
                <li> El operador / se corresponde con la división entera </li>
                <li> ¡Completa todas las operaciones en filas y columnas para ganar! </li>
                <li> Cuando completes el crucigrama, puedes rellenar el formulario de abajo para ver los mejores tiempos del nivel </li>
            </ol>
        </section>
        <section data-type='botonera'>
            <h3>Botonera</h3>
            <button onclick='crucigrama.introduceElement(1)'>1</button>
            <button onclick='crucigrama.introduceElement(2)'>2</button>
            <button onclick='crucigrama.introduceElement(3)'>3</button>
            <button onclick='crucigrama.introduceElement(4)'>4</button>
            <button onclick='crucigrama.introduceElement(5)'>5</button>
            <button onclick='crucigrama.introduceElement(6)'>6</button>
            <button onclick='crucigrama.introduceElement(7)'>7</button>
            <button onclick='crucigrama.introduceElement(8)'>8</button>
            <button onclick='crucigrama.introduceElement(9)'>9</button>
            <button onclick='crucigrama.introduceElement(\"*\")'>x</button>
            <button onclick='crucigrama.introduceElement(\"+\")'>+</button>
            <button onclick='crucigrama.introduceElement(\"-\")'>-</button>
            <button onclick='crucigrama.introduceElement(\"/\")'>/</button>
        </section>
        <section>
            <h3> Top 10 - Records  </h3>
            $topRecords
        </section>
    </main>

    <script>
        var crucigrama = new Crucigrama('facil');
        crucigrama.paintMathword();

        document.addEventListener('keydown', function (event) {

            if (!crucigrama.gameActive) {
                return;
            }

            var clickedCell = $('p[data-state=\"clicked\"]');

            if (clickedCell.length > 0) {
                const key = event.key;
                if ((key >= '0' && key <= '9') || ['+', '-', '*', '/'].includes(key)) {
                    crucigrama.introduceElement(key);
                }
            }
            else {
                alert('Por favor, selecciona una celda antes de pulsar cualquier tecla.');
            }

        });
    </script>

</body>

</html>";
?>