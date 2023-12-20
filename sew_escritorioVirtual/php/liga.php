<?php
class Liga {

    private $server;
    private $user;
    private $pass;
    private $dbname;
    private $db;

    public function __construct() {
        $this->server = "localhost";
        $this->user = "DBUSER2023";
        $this->pass = "DBPSWD2023";
        $this->dbname = "ligaVoleibol";

        $this->crearConexion();    
        $this->createDatabaseAndTables();
        $this->seleccionarBase();
              
        }

    public function seleccionarBase(){
        $this->db->select_db("ligaVoleibol");
    }

    public function crearConexion() {
        $this->db = new mysqli($this->server, $this->user, $this->pass);

        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function cerrarConexion() {
        $this->db->close();
    }

    public function executeQuery($query)
    {
        $res = $this->db->query($query);
        return $res;
    }

    public function loadData($nombreArchivo){
        $file = fopen($nombreArchivo, "r");
        
        while (($line = fgetcsv($file)) !== false) {
            switch ($line[0]) {
                case "Equipos":
                    $this->loadDataEquipos(array_slice($line, 1));
                    break;
                case "Entrenadores":
                    $this->loadDataEntrenadores(array_slice($line, 1));
                    break;
                case "Jugadores":
                    $this->loadDataJugadores(array_slice($line, 1));
                    break;
                case "Partidos":
                    $this->loadDataPartidos(array_slice($line, 1));
                    break;
                case "Estadisticas":
                    $this->loadDataEstadisticas(array_slice($line, 1));
                    break;
            }
        }
    
        fclose($file);
    }
    
    private function loadDataEquipos($data) {
        $query = "INSERT INTO Equipos (nombreEquipo, ciudad, categoria, puntos) VALUES (?, ?, ?, ?)";
        $this->executeInsertQuery($query, $data);
    }
    
    private function loadDataEntrenadores($data) {
        $query = "INSERT INTO Entrenadores (nombre, apellido, edad, idEquipo) VALUES (?, ?, ?, ?)";
        $this->executeInsertQuery($query, $data);
    }
    
    private function loadDataJugadores($data) {
        $query = "INSERT INTO Jugadores (nombre, apellido, edad, altura, posicion, idEquipo) VALUES (?, ?, ?, ?, ?, ?)";
        $this->executeInsertQuery($query, $data);
    }
    
    private function loadDataPartidos($data) {
        $query = "INSERT INTO Partidos (fecha, lugar, idEquipoLocal, idEquipoVisitante, idEquipoGanador) VALUES (?, ?, ?, ?, ?)";
        $this->executeInsertQuery($query, $data);
    }
    
    private function loadDataEstadisticas($data) {
        $query = "INSERT INTO Estadisticas (idJugador, puntos, bloqueos, ataquesExitosos) VALUES (?, ?, ?, ?)";
        $this->executeInsertQuery($query, $data);
    }
    
    private function executeInsertQuery($query, $data) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
        $stmt->close();
    }

    public function exportarDatosCSV() {
        $this->db->select_db($this->dbname);
        $tablas = array('equipos', 'jugadores', 'entrenadores', 'partidos', 'estadisticas');

        $contenidoTotalCSV = '';

        foreach ($tablas as $tabla) {
            $contenidoCSV = $this->obtenerContenidoCSV($tabla);

            $contenidoTotalCSV .= $contenidoCSV;
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="liga_exportada.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $contenidoTotalCSV;
        exit();
    }

    private function obtenerContenidoCSV($tabla) {
        $query = "SELECT * FROM $tabla";
        $result = $this->db->query($query);
    
        $tablaMayuscula = ucfirst($tabla); // Convertir la primera letra a mayúscula
        $contenidoCSV = '';
        while ($fila = $result->fetch_assoc()) {
            $contenidoCSV .= $tablaMayuscula . ',';
            foreach ($fila as $clave => $valor) {
                // Excluir el segundo valor (idEquipo, idEntrenador, idJugador, idEstadistica, idPartido)
                if ($clave !== 'idEquipo' && $clave !== 'idEntrenador' && $clave !== 'idJugador' && $clave !== 'idEstadistica' && $clave !== 'idPartido') {
                    $contenidoCSV .= $valor . ',';
                }
            }
            // Agregar idEquipo solo para Jugadores y Entrenadores
            if ($tabla === 'jugadores' || $tabla === 'entrenadores') {
                $contenidoCSV .= $fila['idEquipo'] . ',';
            }
            $contenidoCSV = rtrim($contenidoCSV, ',') . "\n";
        }
        return $contenidoCSV;
    }

public function createDatabaseAndTables()
{

    $queryCrearDatabase = "CREATE DATABASE IF NOT EXISTS ligaVoleibol COLLATE utf8_spanish_ci;";
    $this->executeQuery($queryCrearDatabase);

    $this->db->select_db("ligaVoleibol");

    $queryCrearEquipos = "
        CREATE TABLE IF NOT EXISTS Equipos (
            idEquipo INT PRIMARY KEY AUTO_INCREMENT,
            nombreEquipo VARCHAR(255) NOT NULL,
            ciudad VARCHAR(255) NOT NULL,
            categoria VARCHAR(50) NOT NULL,
            puntos INT
        )
    ";
    $this->executeQuery($queryCrearEquipos);

    $queryCrearEntrenadores = "
        CREATE TABLE IF NOT EXISTS Entrenadores (
            idEntrenador INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(100) NOT NULL,
            apellido VARCHAR(100) NOT NULL,
            edad INT,
            idEquipo INT,
            FOREIGN KEY (idEquipo) REFERENCES Equipos(idEquipo)
        )
    ";
    $this->executeQuery($queryCrearEntrenadores);

    $queryCrearJugadores = "
        CREATE TABLE IF NOT EXISTS Jugadores (
            idJugador INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(100) NOT NULL,
            apellido VARCHAR(100) NOT NULL,
            edad INT,
            altura DECIMAL(5,2),
            posicion VARCHAR(50),
            idEquipo INT,
            FOREIGN KEY (idEquipo) REFERENCES Equipos(idEquipo)
        )
    ";
    $this->executeQuery($queryCrearJugadores);

    $queryCrearPartidos = "
        CREATE TABLE IF NOT EXISTS Partidos (
            idPartido INT PRIMARY KEY AUTO_INCREMENT,
            fecha DATE NOT NULL,
            lugar VARCHAR(255) NOT NULL,
            idEquipoLocal INT,
            idEquipoVisitante INT,
            idEquipoGanador INT,
            FOREIGN KEY (idEquipoLocal) REFERENCES Equipos(idEquipo),
            FOREIGN KEY (idEquipoVisitante) REFERENCES Equipos(idEquipo),
            FOREIGN KEY (idEquipoGanador) REFERENCES Equipos(idEquipo)
        )
    ";
    $this->executeQuery($queryCrearPartidos);

    $queryCrearEstadisticas = "
        CREATE TABLE IF NOT EXISTS Estadisticas (
            idEstadistica INT PRIMARY KEY AUTO_INCREMENT,
            idJugador INT,
            puntos INT,
            bloqueos INT,
            ataquesExitosos INT,
            FOREIGN KEY (idJugador) REFERENCES Jugadores(idJugador)
        )
    ";
    $this->executeQuery($queryCrearEstadisticas);

    }

    
    

    public function obtenerClasificacion() {
        $query = "SELECT * FROM Equipos ORDER BY puntos DESC";
        $result = $this->db->query($query);

        if (!$result) {
            die("Error al obtener la clasificación: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function mostrarClasificacion() {
        $clasificacion = $this->obtenerClasificacion();

        echo "<table>";
        echo "<tr>
                <th scope='col' id='nombreEq'>Equipo</th>
                <th scope='col' id='ciudadEq'>Ciudad</th>
                <th scope='col' id='puntosEq'>Puntos</th>
            </tr>";

        foreach ($clasificacion as $equipo) {
            echo "<tr>";
            echo "<td headers='nombreEq'>{$equipo['nombreEquipo']}</td>";
            echo "<td headers='ciudadEq'>{$equipo['ciudad']}</td>";
            echo "<td headers='puntosEq'>{$equipo['puntos']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    public function mostrarDetallesEquipo() {
            $idEquipoSeleccionado = $_POST["verEquipo"];
            if (!empty($idEquipoSeleccionado)) {
                $detallesEquipo = $this->obtenerDetallesEquipo($idEquipoSeleccionado);
                echo "<h4> Equipo: {$detallesEquipo['equipo']['nombreEquipo']}</h4>";

                echo "<table>";
                echo "<caption>Jugadores</caption>";
                echo "<tr>
                        <th scope='col' id='nombreJug'>Nombre</th>
                        <th scope='col' id='apellidoJug'>Apellido</th>
                        <th scope='col' id='edadJug'>Edad</th>
                        <th scope='col' id='alturaJug'>Altura</th>
                        <th scope='col' id='posicionJug'>Posición</th>
                        <th scope='col' id='btEliminar'>Eliminar</th>
                    </tr>";
                foreach ($detallesEquipo['jugadores'] as $jugador) {
                    echo "<tr>";
                    echo "<td headers='nombreJug'>{$jugador['nombre']}</td>";
                    echo "<td headers='apellidoJug'>{$jugador['apellido']}</td>";
                    echo "<td headers='edadJug'>{$jugador['edad']}</td>";
                    echo "<td headers='alturaJug'>{$jugador['altura']}</td>";
                    echo "<td headers='posicionJug'>{$jugador['posicion']}</td>";
                    echo "<td headers='btEliminar'><form method='post' action='#'>";
                    echo "<input type='hidden' name='idJugadorEliminar' value='{$jugador['idJugador']}'>";
                    echo "<input type='submit' name='eliminarJugador' value='Eliminar'>";
                    echo "</form></td>";
                    echo "</tr>";
                }
                echo "</table>";

                echo "<table>";
                echo "<caption>Entrenador</caption>";
                echo "<tr>
                        <th scope='col' id='nombreEnt'>Nombre</th>
                        <th scope='col' id='apellidoEnt'>Apellido</th>
                        <th scope='col' id='edadEnt'>Edad</th></tr>";
                echo "<tr>";
                echo "<td headers='nombreEnt'>{$detallesEquipo['entrenador']['nombre']}</td>";
                echo "<td headers='apellidoEnt'>{$detallesEquipo['entrenador']['apellido']}</td>";
                echo "<td headers='edadEnt'>{$detallesEquipo['entrenador']['edad']}</td>";
                echo "</tr>";
                echo "</table>";

                echo "</section>";

            }
        }   
    
    public function obtenerDetallesEquipo($idEquipo) {
        $detallesEquipo = array();
    
        $queryEquipo = "SELECT * FROM Equipos WHERE idEquipo = ?";
        $stmtEquipo = $this->db->prepare($queryEquipo);
        $stmtEquipo->bind_param("i", $idEquipo);
        $stmtEquipo->execute();
        $resultEquipo = $stmtEquipo->get_result();
        $detallesEquipo['equipo'] = $resultEquipo->fetch_assoc();
    
        $queryJugadores = "SELECT * FROM Jugadores WHERE idEquipo = ?";
        $stmtJugadores = $this->db->prepare($queryJugadores);
        $stmtJugadores->bind_param("i", $idEquipo);
        $stmtJugadores->execute();
        $resultJugadores = $stmtJugadores->get_result();
        $detallesEquipo['jugadores'] = $resultJugadores->fetch_all(MYSQLI_ASSOC);
    
        $queryEntrenador = "SELECT * FROM Entrenadores WHERE idEquipo = ?";
        $stmtEntrenador = $this->db->prepare($queryEntrenador);
        $stmtEntrenador->bind_param("i", $idEquipo);
        $stmtEntrenador->execute();
        $resultEntrenador = $stmtEntrenador->get_result();
        $detallesEquipo['entrenador'] = $resultEntrenador->fetch_assoc();
    
        return $detallesEquipo;
    }

    public function obtenerPlantilla($idEquipo) {
        $query = "SELECT * FROM Jugadores WHERE idEquipo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idEquipo);
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result) {
            die("Error al obtener la plantilla: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function agregarJugador($datosJugador) {
        $idEquipo = $datosJugador['equipoNuevoJugador'];
        $nombre = $datosJugador['nombreNuevoJugador'];
        $apellido = $datosJugador['apellidoNuevoJugador'];
        $edad = $datosJugador['edadNuevoJugador'];
        $altura = $datosJugador['alturaNuevoJugador'];
        $posicion = $datosJugador['posicionNuevoJugador'];
    
        $query = "INSERT INTO Jugadores (idEquipo, nombre, apellido, edad, altura, posicion) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isssds", $idEquipo, $nombre, $apellido, $edad, $altura, $posicion);
        
        if ($stmt->execute()) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error al agregar el jugador: " . $stmt->error;
        }
    
        $stmt->close();
    }

    public function eliminarJugador($idJugador) {
        $query = "DELETE FROM Jugadores WHERE idJugador = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJugador);
        
        $stmt->execute();
        
    
        $stmt->close();
    }

    public function obtenerJugadorPorId($idJugador) {
        $query = "SELECT * FROM Jugadores WHERE idJugador = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJugador);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        return $result->fetch_assoc();
    }

    public function añadirPartido($fecha, $lugar, $idEquipoLocal, $idEquipoVisitante, $idEquipoGanador) {
        $query = "INSERT INTO Partidos (fecha, lugar, idEquipoLocal, idEquipoVisitante, idEquipoGanador) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssiii", $fecha, $lugar, $idEquipoLocal, $idEquipoVisitante, $idEquipoGanador);
    
        if ($stmt->execute()) {
            $idPartido = $stmt->insert_id;
            $this->actualizarClasificacion($idPartido);
            return true;
        } else {
            return false;
        }
    
        $stmt->close();
    }
    
    public function actualizarClasificacion($idPartido) {
        $queryGanador = "SELECT idEquipoGanador FROM Partidos WHERE idPartido = ?";
        $stmtGanador = $this->db->prepare($queryGanador);
        $stmtGanador->bind_param("i", $idPartido);
        $stmtGanador->execute();
        $resultGanador = $stmtGanador->get_result();
    
        $ganador = $resultGanador->fetch_assoc();
    
        if ($ganador) {
            $idEquipoGanador = $ganador['idEquipoGanador'];
            $puntosGanados = 3;
    
            $queryUpdate = "UPDATE Equipos SET puntos = puntos + ? WHERE idEquipo = ?";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->bind_param("ii", $puntosGanados, $idEquipoGanador);
    
            $stmtUpdate->execute();
    
            $stmtUpdate->close();
        }
    
        $stmtGanador->close();
    }

    public function guardarEstadisticas($idJugador, $puntos, $bloqueos, $ataquesExitosos) {
        $estadisticasActuales = $this->obtenerEstadisticasJugador($idJugador);
    
        if ($estadisticasActuales) {
            $puntos += $estadisticasActuales['puntos'];
            $bloqueos += $estadisticasActuales['bloqueos'];
            $ataquesExitosos += $estadisticasActuales['ataquesExitosos'];

            $query = "UPDATE Estadisticas SET puntos = ?, bloqueos = ?, ataquesExitosos = ? WHERE idJugador = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iiii", $puntos, $bloqueos, $ataquesExitosos, $idJugador);
        } else {
            $query = "INSERT INTO Estadisticas (idJugador, puntos, bloqueos, ataquesExitosos) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iiii", $idJugador, $puntos, $bloqueos, $ataquesExitosos);
        }
    
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerJugadoresPorEquipo($idEquipo) {
        $query = "SELECT * FROM Jugadores WHERE idEquipo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idEquipo);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cargarJugadores($idEquipo) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cargarJugadores"])) {
            if (!empty($idEquipo)) {
                $jugadores = $this->obtenerJugadoresPorEquipo($idEquipo);
    
                return $jugadores;
            } else {
                return;
            }
        }
        
        return array(); 
    }

    public function obtenerTodosLosJugadores() {
        $query = "SELECT * FROM Jugadores";
        $result = $this->db->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerEstadisticasJugador($idJugador) {
        $query = "SELECT * FROM Estadisticas WHERE idJugador = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJugador);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

}

$liga = new Liga();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregarJugador"])) {
    $datosJugador = array(
        'equipoNuevoJugador' => $_POST["equipoNuevoJugador"],
        'nombreNuevoJugador' => $_POST["nombreNuevoJugador"],
        'apellidoNuevoJugador' => $_POST["apellidoNuevoJugador"],
        'edadNuevoJugador' => $_POST["edadNuevoJugador"],
        'alturaNuevoJugador' => $_POST["alturaNuevoJugador"],
        'posicionNuevoJugador' => $_POST["posicionNuevoJugador"]
    );
    $liga->agregarJugador($datosJugador);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminarJugador"])) {
    $idJugadorEliminar = $_POST["idJugadorEliminar"];
    $liga->eliminarJugador($idJugadorEliminar);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregarResultado"])) {
    $fechaPartido = $_POST["fechaPartido"];
    $lugarPartido = $_POST["lugarPartido"];
    $equipoLocal = $_POST["equipoLocal"];
    $equipoVisitante = $_POST["equipoVisitante"];
    $equipoGanador = $_POST["equipoGanador"];

    if ($equipoGanador != $equipoLocal && $equipoGanador != $equipoVisitante) {

    } else {
        $liga->añadirPartido($fechaPartido, $lugarPartido, $equipoLocal, $equipoVisitante, $equipoGanador);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["equipoSeleccionado"])) {
    $equipoSeleccionado = $_POST["equipoSeleccionado"];
    $jugadores = $liga->cargarJugadores($equipoSeleccionado);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardarStats"])) {
    $idJugador = $_POST["jugadorSeleccionado"];
    $puntos = $_POST["puntos"];
    $bloqueos = $_POST["bloqueos"];
    $ataquesExitosos = $_POST["ataquesExitosos"];
    $liga->guardarEstadisticas($idJugador, $puntos, $bloqueos, $ataquesExitosos);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["importarCSV"])) {
    if (isset($_FILES["csvFile"])) {
        $nombreArchivoCSV = $_FILES["csvFile"]["name"];
        $liga->loadData($nombreArchivoCSV);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["exportarCSV"])) {
    $nombreArchivoCSV = "liga_exportada.csv";
    $liga->exportarDatosCSV();
}





?>

<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/icon" href="multimedia/favicon.ico">
    <title>Escritorio Virtual - Juegos</title>
    <meta name="author" content="Adrian Estrada González" />
    <meta name="description" content="Mi liga de voleibol" />
    <meta name="keywords" content="liga, voleibol, clasificacion, jugadores, entrenadores, estadisticas, partidos, resultados" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../estilo/estilo.css" />
    <link rel="stylesheet" type="text/css" href="../estilo/liga.css" />
</head>
<body>
    <header>
        <h1>Escritorio Virtual</h1>
        <nav>
            <ul>
                <li><a tabindex="1" accesskey="I" href="../index.html">Inicio</a></li>
                <li><a tabindex="2" accesskey="S" href="../sobremi.html">Sobre mi</a></li>
                <li><a tabindex="3" accesskey="N" href="../noticias.html">Noticias</a></li>
                <li><a tabindex="4" accesskey="A" href="../agenda.html">Agenda</a></li>
                <li><a tabindex="5" accesskey="M" href="../meteorologia.html">Meteorología</a></li>
                <li><a tabindex="6" accesskey="J" href="../juegos.html">Juegos</a></li>
                <li><a tabindex="7" accesskey="V" href="../viajes.php">Viajes</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Mi liga de Voleibol</h2>
        <section>
            <h3>Elige un juego</h3>
            <ul>
                <li><a href="../memoria.html">Juego de Memoria</a></li>
                <li><a href="../sudoku.html">Sudoku</a></li>
                <li><a href="../crucigrama.php">Crucigrama</a></li>
                <li><a href="../api.html">ApiGame</a></li>
                <li><a href="liga.php">Mi liga de Voleibol</a></li>
            </ul>
        </section>
        <section>
            <h3> Importar datos iniciales desde un CSV </h3>
            <form action="#" method="post" enctype="multipart/form-data">
            <label for="csvFile">Selecciona un archivo CSV:</label>
            <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
            <input type="submit" name="importarCSV" value="Importar">
            </form>
        </section>
        <section>
            <h3> Exportar datos actuales de la base de datos a un CSV </h3>
            <form action="#" method="post">
            <input type="submit" name="exportarCSV" value="Exportar">
            </form>
        </section>

        <section>
            <h3>Clasificacion</h3>
            <?php $liga->mostrarClasificacion(); ?>
        </section>
        <section>
            <h3>Plantillas</h3>
            <form method="post" action="#">
                <label for="verEquipo">Selecciona un equipo:</label>
                <select name="verEquipo" id="verEquipo">
                    <option value="">Selecciona un equipo</option>
                    <?php
                    $equipos = $liga->obtenerClasificacion();
                    foreach ($equipos as $equipo) {
                        echo "<option value='{$equipo['idEquipo']}'>{$equipo['nombreEquipo']}</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="verplantilla" value="Ver Plantilla">
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verplantilla"])) {
                $liga->mostrarDetallesEquipo();
            }
            ?>
        </section>
        <section>
            <h3>Añadir Jugador</h3>
            <form method="post" action="#">
                <label for="equipoNuevoJugador">Equipo:</label>
                <select name="equipoNuevoJugador" id="equipoNuevoJugador">
                    <option value="">Selecciona un equipo</option>
                    <?php
                    $equipos = $liga->obtenerClasificacion();
                    foreach ($equipos as $equipo) {
                        echo "<option value='{$equipo['idEquipo']}'>{$equipo['nombreEquipo']}</option>";
                    }
                    ?>
                </select>
                <label for="nombreNuevoJugador">Nombre:</label>
                <input type="text" name="nombreNuevoJugador" id="nombreNuevoJugador" required>
                <label for="apellidoNuevoJugador">Apellido:</label>
                <input type="text" name="apellidoNuevoJugador" id="apellidoNuevoJugador" required>
                <label for="edadNuevoJugador">Edad:</label>
                <input type="number" name="edadNuevoJugador" id="edadNuevoJugador" required>
                <label for="alturaNuevoJugador">Altura:</label>
                <input type="number" name="alturaNuevoJugador" id="alturaNuevoJugador" step="0.01" required>
                <label for="posicionNuevoJugador">Posición:</label>
                <input type="text" name="posicionNuevoJugador" id="posicionNuevoJugador" required>
                <input type="submit" name="agregarJugador" value="Añadir Jugador">
            </form>
        </section>
        <section>
        <h3>Estadísticas individuales</h3>
        <form method="post" action="#">
            <label for="equipoSeleccionado">Selecciona un equipo:</label>
            <select name="equipoSeleccionado" id="equipoSeleccionado" required>
                <option value="">Selecciona un equipo</option>
                <?php
                $equipos = $liga->obtenerClasificacion();
                foreach ($equipos as $equipo) {
                    echo "<option value='{$equipo['idEquipo']}'>{$equipo['nombreEquipo']}</option>";
                }
                ?>
            </select>
            <input type="submit" name="cargarJugadores" value="Seleccionar">
        </form>

        <?php
        if (isset($jugadores) && !empty($jugadores)) {
            ?>
            <form method="post" action="#">
                <label for="jugadorSeleccionado">Selecciona un jugador:</label>
                <select name="jugadorSeleccionado" id="jugadorSeleccionado" required>
                    <option value="">Selecciona un jugador</option>
                    <?php
                    foreach ($jugadores as $jugador) {
                        echo "<option value='{$jugador['idJugador']}'>{$jugador['nombre']} {$jugador['apellido']}</option>";
                    }
                    ?>
                </select>

                <label for="puntos">Número de puntos:</label>
                <input type="number" name="puntos" id="puntos" required>

                <label for="bloqueos">Número de bloqueos:</label>
                <input type="number" name="bloqueos" id="bloqueos" required>

                <label for="ataquesExitosos">Número de ataques exitosos:</label>
                <input type="number" name="ataquesExitosos" id="ataquesExitosos" required>

                <input type="submit" name="guardarStats" value="Guardar Estadísticas">
            </form>
            <?php
        }
        ?>
        </section>
        <section>
            <h3>Ver Estadísticas de Jugador</h3>

            <form method="post" action="#">
                <label for="jugadorSelec">Selecciona un jugador:</label>
                <select name="jugadorSelec" id="jugadorSelec" required>
                    <option value="">Selecciona un jugador</option>
                    <?php
                    $todosLosJugadores = $liga->obtenerTodosLosJugadores();

                    foreach ($todosLosJugadores as $jugador) {
                        echo "<option value='{$jugador['idJugador']}'>{$jugador['nombre']} {$jugador['apellido']}</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="mostrarStats" value="Ver Estadísticas">
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mostrarStats"])) {
                $idJugadorSeleccionado = $_POST["jugadorSelec"];
                $estadisticasJugador = $liga->obtenerEstadisticasJugador($idJugadorSeleccionado);
                $jugador = $liga->obtenerJugadorPorId($idJugadorSeleccionado);  
            
                if ($estadisticasJugador && $jugador) {
                    echo "<h4>Estadísticas de {$jugador['nombre']} {$jugador['apellido']}:</h4>";
                    echo "<table>";
                    echo "<tr>
                            <th scope='col' id='puntos'>Puntos</th>
                            <th scope='col' id='bloqueos'>Bloqueos</th>
                            <th scope='col' id='ataques'>Ataques Exitosos</th>
                          </tr>";
                    echo "<tr>";
                    echo "<td headers='puntos'>{$estadisticasJugador['puntos']}</td>";
                    echo "<td headers='bloqueos'>{$estadisticasJugador['bloqueos']}</td>";
                    echo "<td headers='ataques'>{$estadisticasJugador['ataquesExitosos']}</td>";
                    echo "</tr>";
                    echo "</table>";
                } else {
                    echo "<p>No hay estadísticas disponibles para este jugador.</p>";
                }
            }
            ?>
        </section>
        <section>
            <h3>Añadir Resultado</h3>
            <form method="post" action="#">
                <label for="fechaPartido">Fecha del Partido:</label>
                <input type="date" name="fechaPartido" id="fechaPartido" required>

                <label for="lugarPartido">Lugar del Partido:</label>
                <input type="text" name="lugarPartido" id="lugarPartido" required>

                <label for="equipoLocal">Equipo Local:</label>
                <select name="equipoLocal" id="equipoLocal" required>
                    <option value="">Selecciona un equipo</option>
                    <?php
                    $equipos = $liga->obtenerClasificacion();
                    foreach ($equipos as $equipo) {
                        echo "<option value='{$equipo['idEquipo']}'>{$equipo['nombreEquipo']}</option>";
                    }
                    ?>
                </select>

                <label for="equipoVisitante">Equipo Visitante:</label>
                <select name="equipoVisitante" id="equipoVisitante" required>
                    <option value="">Selecciona un equipo</option>
                    <?php
                    foreach ($equipos as $equipo) {
                        echo "<option value='{$equipo['idEquipo']}'>{$equipo['nombreEquipo']}</option>";
                    }
                    ?>
                </select>

                <label for="equipoGanador">Equipo Ganador:</label>
                <select name="equipoGanador" id="equipoGanador" required>
                    <option value="">Selecciona un equipo</option>
                    <?php
                    foreach ($equipos as $equipo) {
                        echo "<option value='{$equipo['idEquipo']}'>{$equipo['nombreEquipo']}</option>";
                    }
                    ?>
                </select>

                <input type="submit" name="agregarResultado" value="Añadir Resultado">
            </form>
        </section>
    </main>

</body>
</html>