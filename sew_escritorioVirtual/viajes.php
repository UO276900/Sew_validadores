<?php

class Carrusel {
    
    private $nombreCapital;
    private $nombrePais;
    private $flickrApiKey = 'e0e0b660c359954001699707abbe5233';
    
    // Método constructor
    public function __construct($nombreCapital, $nombrePais) {
        $this->nombreCapital = $nombreCapital;
        $this->nombrePais = $nombrePais;
    }

    public function obtenerFotosFlickr() {
        $url = "https://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?";
        $url .= "&tags={$this->nombrePais},{$this->nombreCapital}";
        $url .= "&tagmode=any";
        $url .= "&format=json";

        // Realiza la llamada a la API de Flickr
        $response = file_get_contents($url);

        if ($response === false) {
            // Manejo de error
            echo 'Error en la llamada a la API de Flickr.';
            return [];
        }

        $data = json_decode(substr($response, 1, -1), true);

        if ($data === null || !isset($data['items'])) {
            // Manejo de error en la decodificación JSON
            echo 'Error al decodificar la respuesta de la API de Flickr.';
            return [];
        }

        // Obtener las URLs de las fotos
        $fotos = [];
        foreach ($data['items'] as $item) {
            $fotos[] = str_replace("_m", "_b", $item['media']['m']);
        }

        // Devolver las URLs de las fotos
        return $fotos;
    }


    public function mostrarCarrusel() {
        $fotos = $this->obtenerFotosFlickr();

        // Limitar a las primeras 10 fotos
        $fotosLimitadas = array_slice($fotos, 0, 10);

        foreach ($fotosLimitadas as $foto) {
            echo "<img src='{$foto}' alt='Foto de Chile'>";
        }
    }
}

class Moneda {
    private $moneda1;
    private $moneda2;
    private $apiKey;

    // Constructor que recibe las dos monedas
    public function __construct($moneda1, $moneda2) {
        $this->moneda1 = $moneda1;
        $this->moneda2 = $moneda2;
        $this->apiKey = "30fd3642caf6a52c7f14bb7e";
    }

    public function getMoneda1() {
        return $this->moneda1;
    }

    public function getMoneda2() {
        return $this->moneda2;
    }

    public function obtenerCambio() {
        $url = "https://open.er-api.com/v6/latest/{$this->moneda1}";

        $respuesta = file_get_contents($url);

        if ($respuesta === false) {
            return null;
        }

        $datos = json_decode($respuesta, true);

        if (!$datos || !isset($datos['rates'][$this->moneda2])) {
            return null; 
        }

        return $datos['rates'][$this->moneda2];
    }

    public function mostrarCambio()
    {
        $cambio = $this->obtenerCambio();
        echo "<p>Cambio de Euros({$this->moneda1}) a Pesos Chilenos({$this->moneda2}): {$cambio}</p>";
        echo "<p>1 {$this->moneda1} equivale a {$cambio} {$this->moneda2}</p>";
    }
}

$miMoneda = new Moneda('EUR', 'CLP');
$cambio = $miMoneda->obtenerCambio();

?>
<?php
echo '
<!DOCTYPE HTML>
<html lang="es">
<head>
 <!-- Datos que describen el documento -->
 <meta charset="UTF-8" />
 <link rel="icon" type="image/icon" href="multimedia/favicon.ico">
 <title> Escritorio Virtual - Viajes</title>
 <meta name ="author" content ="Adrian Estrada González" />
 <meta name ="description" content ="Viajes" />
 <meta name ="keywords" content ="viajes, chile, santiago, carrusel, fotos, kml, svg, xml, rutas, hitos" />
 <meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
 <link rel="stylesheet" type="text/css" href="estilo/estilo.css" />
 <link rel="stylesheet" type="text/css" href="estilo/viajes.css" />
 
 <script src="js/viajes.js"></script>
 <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css" rel="stylesheet">
 <script src="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js"></script>
 
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
 <!-- Datos con el contenido que aparece en el navegador -->
 <header>
    <h1> Escritorio Virtual </h1>
    <nav>
        <ul>
            <li><a tabindex="1" accesskey="I" href="index.html">Inicio</a></li>
            <li><a tabindex="2" accesskey="S" href="sobremi.html">Sobre mi</a></li>
            <li><a tabindex="3" accesskey="N" href="noticias.html">Noticias</a></li>
            <li><a tabindex="4" accesskey="A" href="agenda.html">Agenda</a></li>
            <li><a tabindex="5" accesskey="M" href="meteorologia.html">Meteorología</a></li>
            <li><a tabindex="6" accesskey="J" href="juegos.html">Juegos</a></li>
            <li><a tabindex="7" accesskey="V" href="viajes.php">Viajes</a></li>
        </ul>
    </nav>
</header>
<!-- 276900 mod 194 = 62 -> 62+1 = 63 (AMERICA - CHILE - SANTIAGO DE CHILE)-->
<main>
    <h2>Viajes</h2>
    <article>
        <h3> Carrusel de fotos </h3>';
        $carrusel = new Carrusel("SantiagodeChile", "Chile");       
        $carrusel->mostrarCarrusel();
echo '
    <button data-action="next"> > </button>
    <button data-action="prev"> < </button>
    </article>
    <section>
        <h3> Cambio de moneda </h3>';
        $miMoneda = new Moneda('EUR', 'CLP');
        $miMoneda -> mostrarCambio();
echo '
    </section>
    <section>
        <h3> Mis mapas </h3>
        <button onclick="viajes.getMapaEstaticoGoogle()"> Mapa estático </button>
        <button onclick="viajes.initMap()"> Mapa dinámico </button>
    </section>
    <section>
        <h3> Procesar XML </h3>
        <label for="fileInput">Elige un archivo:</label>
        <input type="file" id="fileInput" onchange="viajes.readInputFile()">
    </section>
    <section> 
        <h3> Procesar archivos KML </h3>
        <label for="fileInputKML">Elige un archivo:</label>
        <input type="file" id="fileInputKML" onchange="viajes.generarMapaDinamicoConKML(this.files);" multiple>
    </section>
    <section> 
        <h3> Procesar archivos SVG </h3>
        <label for="fileInputSVG">Elige un archivo:</label>
        <input type="file" id="fileInputSVG" onchange="viajes.leerSVGs(this.files);" multiple>
    </section>
    
</main>
<script>
    var viajes = new Viajes();
</script>
</body>
</html>
';?>

