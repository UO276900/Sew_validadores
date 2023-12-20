"use strict";
class Viajes {
    constructor() {
        document.addEventListener("DOMContentLoaded", function () {
            inicializarCarrusel();
        });
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                this.getUserPosition.bind(this),
                this.verErrores.bind(this)
            );
        } else {
            alert("El navegador no soporta API Geolocation");
        }
    }

    getUserPosition(position) {
        if (position.coords) {
            this.userLongitud = position.coords.longitude;
            this.userLatitud = position.coords.latitude;
        } else {
            console.error("Error al obtener las coordenadas de geolocalización");
        }
    }

    getLongitud() {
        return this.userLongitud
    }

    getLatitud() {
        return this.userLatitud;
    }

    verErrores(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                this.mensaje = "El usuario no permite la petición de geolocalización"
                break;
            case error.POSITION_UNAVAILABLE:
                this.mensaje = "Información de geolocalización no disponible"
                break;
            case error.TIMEOUT:
                this.mensaje = "La petición de geolocalización ha caducado"
                break;
            case error.UNKNOWN_ERROR:
                this.mensaje = "Se ha producido un error desconocido"
                break;
        }
    }

    getMapaEstaticoGoogle() {
        this.mapaEstatico = true;
        var section = document.createElement("section");
        section.setAttribute("data-state", "mapaestatico");
        var h3 = document.createElement("h3");
        h3.textContent = "Mi ubicación en mapa estático";
        var bt = $('button:contains("Mapa estático")');
        bt.prop('disabled', true);
        var apiKey = "&key=AIzaSyBsWUH0aj9mmyO81Ma237xzuYb2aHQu3fk";
        var url = "https://maps.googleapis.com/maps/api/staticmap?";
        var centro = "center=" + this.userLatitud + "," + this.userLongitud;
        var zoom = "&zoom=16";
        var tamaño = "&size=400x300";
        var marcador = "&markers=color:red%7Clabel:S%7C" + this.userLatitud + "," + this.userLongitud;
        var sensor = "&sensor=false";

        this.imagenMapa = url + centro + zoom + tamaño + marcador + sensor + apiKey;
        var imgMapa = document.createElement("img");
        imgMapa.src = this.imagenMapa;
        imgMapa.alt = "mapa estático google";

        section.append(h3);
        section.append(imgMapa);
        $("main").append(section);

    }
    readInputFile() {
        const archivoInput = $('input[type="file"]')[0];
        const main = $('main');

        if (archivoInput.files.length > 0) {
            const archivo = archivoInput.files[0];
            const tipoTexto = /text.*/;

            if (archivo.type.match(tipoTexto)) {
                var lector = new FileReader();
                lector.onload = function (evento) {
                    var nuevaSeccion = $("<section> </section>");
                    var res = evento.target.result;
                    var xml = $(res);

                    var tituloSec = $("<h3> Rutas </h3>");
                    nuevaSeccion.append(tituloSec);
                    var parrafoIntro = $("<p> A continuación se muestran rutas turísticas para disfrutar en tus vacaciones por Santiago de Chile </p>");
                    nuevaSeccion.append(parrafoIntro);
                    var rutas = xml.find("ruta");
                    rutas.each(function () {
                        var secRuta = $("<section> </section>");
                        var nombreRuta = $("<h4> </h4>");
                        var nomRuta = $(this).find("nombre:first").text();
                        nombreRuta.text(`${nomRuta}`)

                        var listaRuta = $("<ul> </ul>");
                        var tipo = $(this).find("tipo").text();
                        listaRuta.append(`<li>Tipo: ${tipo}</li>`);

                        var transporte = $(this).find("transporte").text();
                        listaRuta.append(`<li>Transporte: ${transporte}</li>`);

                        var fecha = $(this).find("fecha").text();
                        listaRuta.append(`<li>Fecha: ${fecha}</li>`);

                        var hora = $(this).find("hora").text();
                        listaRuta.append(`<li>Hora: ${hora}</li>`);

                        var duracion = $(this).find("duracion").text();
                        listaRuta.append(`<li>Duración: ${duracion}</li>`);

                        var agencia = $(this).find("agencia").text();
                        listaRuta.append(`<li>Agencia: ${agencia}</li>`);

                        var desc = $(this).find("descripcion").text();
                        listaRuta.append(`<li>Descripción: ${desc}</li>`);

                        var personas = $(this).find("personas").text();
                        listaRuta.append(`<li>Personas: ${personas}</li>`);

                        var listaInicio = $("<ul> </ul>");
                        var lugarIni = $(this).find("inicio").find("lugar").text();
                        var direccionIni = $(this).find("inicio").find("direccion").text();

                        listaInicio.append(`<li>Lugar de inicio: ${lugarIni}</li>`);
                        listaInicio.append(`<li>Dirección de inicio: ${direccionIni}</li>`);

                        var listaCoords = $("<ul> </ul>");
                        var longitudIni = $(this).find("inicio").find("coordenadas").find("longitud").text();
                        var latitudIni = $(this).find("inicio").find("coordenadas").find("latitud").text();
                        var altitudIni = $(this).find("inicio").find("coordenadas").find("altitud").text();

                        listaCoords.append(`<li>Longitud: ${longitudIni}</li>`);
                        listaCoords.append(`<li>Latitud: ${latitudIni}</li>`);
                        listaCoords.append(`<li>Altitud: ${altitudIni}</li>`);

                        listaInicio.append(`<li>Coordenadas de inicio:${listaCoords.prop('outerHTML')}</li>`);
                        listaRuta.append(`<li>Inicio:${listaInicio.prop('outerHTML')}</li>`);

                        var listaReferencias = $("<ul> </ul>");
                        var refs = $(this).find("referencias").find("referencia");
                        var counter = 1;
                        refs.each(function () {
                            var ref = $(this).text();
                            listaReferencias.append(`<li> <a href=${ref}>Referencia ${counter}</a></li>`);
                            counter = counter + 1;
                        });

                        listaRuta.append(`<li>Referencias:${listaReferencias.prop('outerHTML')}</li>`);

                        var rec = $(this).find("recomendacion").text();
                        listaRuta.append(`<li>Recomendación: ${rec}</li>`);

                        var listaHitos = $("<ul> </ul>");
                        var hitos = $(this).find("hito");
                        hitos.each(function () {
                            var nHito = $(this).find("nombre").text();
                            var descr = $(this).find("descripcion").text();
                            var longitudHito = $(this).find("coordenadas").find("longitud").text();
                            var latitudHito = $(this).find("coordenadas").find("latitud").text();
                            var altitudHito = $(this).find("coordenadas").find("altitud").text();
                            var distAnterior = $(this).find("distancia_anterior").find("unidades").text();

                            var listaGaleria = $("<ul> </ul>");
                            var galeria = $(this).find("galeria_fotografias").find("fotografia");
                            galeria.each(function () {
                                var foto = $(this).text();
                                listaGaleria.append(`<li> <img src=multimedia/${foto} alt="${foto}"></li>`);
                            });

                            listaHitos.append(`
                            <li>Nombre: ${nHito} 
                                <ul>                                   
                                    <li>Descripción: ${descr} </li>
                                    <li>Coordenadas: ${longitudHito}, ${latitudHito}, ${altitudHito} </li>
                                    <li>Distancia anterior: ${distAnterior} </li>
                                    <li>Galería de Fotografías:${listaGaleria.prop('outerHTML')}</li>
                                </ul> 
                            </li>` );
                        });

                        listaRuta.append(`<li>Hitos:${listaHitos.prop('outerHTML')}</li>`);

                        secRuta.append(nombreRuta);
                        secRuta.append(listaRuta);
                        nuevaSeccion.append(secRuta);
                    });





                    main.append(nuevaSeccion);
                };

                lector.readAsText(archivo);
            }
        }
    }
    initMap() {
        var sec = document.createElement("section");
        var h3 = document.createElement("h3");
        sec.setAttribute("data-state", "mapadinamico");
        h3.textContent = "Mi ubicación en mapa dinámico";
        var bt = $('button:contains("Mapa dinámico")');
        bt.prop('disabled', true);
        sec.append(h3);
        mapboxgl.accessToken = 'pk.eyJ1IjoiYWRyaWVzdHJhZGEiLCJhIjoiY2xxNzIzdXBkMHJnYjJycGFnOXZ1aWkyZCJ9.CZFnbYAvEiNoUAXS6Db8RQ';

        new mapboxgl.Map({
            container: sec,
            style: 'mapbox://styles/mapbox/standard',
            center: [this.userLongitud, this.userLatitud],
            zoom: 10
        });
        $("main").append(sec);
    }

    generarMapaDinamicoConKML(files) {
        var sec = document.createElement("section");
        sec.setAttribute("data-state", "mapadinamico");

        var h3 = document.createElement("h3");
        h3.textContent = "Mapa con Coordenadas KML";
        sec.append(h3);

        mapboxgl.accessToken = 'pk.eyJ1IjoiYWRyaWVzdHJhZGEiLCJhIjoiY2xxNzIzdXBkMHJnYjJycGFnOXZ1aWkyZCJ9.CZFnbYAvEiNoUAXS6Db8RQ';
        var map = new mapboxgl.Map({
            container: sec,
            style: 'mapbox://styles/mapbox/standard',
            center: [-70.6483, -33.4569], 
            zoom: 10
        });

        map.on('style.load', function () {
            for (var i = 0; i < files.length; i++) {
                var kmlArchivo = files[i];

                var lector = new FileReader();
                lector.onload = function (evento) {
                    var res = evento.target.result;
                    var xmlDoc = $.parseXML(res);
                    var $xml = $(xmlDoc);
                    var coordinates = [];
                    $xml.find('Placemark Point coordinates').each(function () {
                        var coords = $(this).text().split(',');
                        var longitud = parseFloat(coords[0]);
                        var latitud = parseFloat(coords[1]);
                        coordinates.push([longitud, latitud]);
                    });
                    var layerId = 'line' + Date.now();
                    map.addLayer({
                        'id': layerId,
                        'type': 'line',
                        'source': {
                            'type': 'geojson',
                            'data': {
                                'type': 'Feature',
                                'properties': {},
                                'geometry': {
                                    'type': 'LineString',
                                    'coordinates': coordinates
                                }
                            }
                        }
                    });
                };

                lector.readAsText(kmlArchivo);
            }
        });

        $("main").append(sec);
    }



    leerSVGs(files) {
        const main = $('main');

        for (let i = 0; i < files.length; i++) {
            const svgArchivo = files[i];

            const lector = new FileReader();
            lector.onload = function (evento) {
                const nuevaSeccion = $("<section> </section>");
                nuevaSeccion.attr("data-state", "svg");
                var count = i + 1;
                const h3 = $("<h3> Altimetria SVG: " + count + " </h3>");
                const svgContent = evento.target.result;

                nuevaSeccion.append(h3);
                nuevaSeccion.append(svgContent);

                main.append(nuevaSeccion);
            };

            lector.readAsText(svgArchivo);
        }
    }


}


function inicializarCarrusel() {
    const slides = document.querySelectorAll("img");
    const nextSlide = document.querySelector("button[data-action='next']");
    let curSlide = 3;

    let maxSlide = slides.length - 1;

    nextSlide.addEventListener("click", function () {
        if (curSlide === maxSlide) {
            curSlide = 0;
        } else {
            curSlide++;
        }

        slides.forEach((slide, indx) => {
            var trans = 100 * (indx - curSlide);
            $(slide).css('transform', 'translateX(' + trans + '%)')
        });
    });

    const prevSlide = document.querySelector("button[data-action='prev']");

    prevSlide.addEventListener("click", function () {
        if (curSlide === 0) {
            curSlide = maxSlide;
        } else {
            curSlide--;
        }

        slides.forEach((slide, indx) => {
            var trans = 100 * (indx - curSlide);
            $(slide).css('transform', 'translateX(' + trans + '%)')
        });
    });
}



