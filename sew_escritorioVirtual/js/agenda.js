"use strict";
class Agenda {
    constructor() {
        this.apiUrl = 'http://ergast.com/api/f1/current';
        this.lastApiCall = null;  
        this.lastApiResult = null;      
    }

    consultarCarreras() {
        const tiempoPasado = this.isTimeout();

        if (!tiempoPasado) {
            return; 
        }

        $.ajax({
            url: this.apiUrl,
            method: 'GET',
            dataType: 'xml',
            success: (data) => {
                this.lastApiCall = new Date();
                this.lastApiResult = data;
                this.mostrarCarrerasEnHTML(data);
            },
            error: (error) => {
                console.error('Error en la llamada AJAX:', error);
            }
        });
    }

    mostrarCarrerasEnHTML(apiResponse) {
        var tableHtml = `
            <table>
                <caption> Datos de carrera de la temporada actual </caption>
                <tr>
                    <th scope="col" id="nombre">Nombre de la carrera</th>
                    <th scope="col" id="nombreCircuito">Nombre del circuito</th>
                    <th scope="col" id="coordenadas">Coordenadas del circuito</th>
                    <th scope="col" id="fecha">Fecha</th>
                    <th scope="col" id="hora">Hora</th>
                </tr>
        `;
        var races = $('Race', apiResponse);
        for (var i = 0; i < races.length; i++) {
            var carrera = races[i];
            var nombreCarrera = $('RaceName',carrera).text(); 
            var nombreCircuito = $('CircuitName',carrera).text(); 
            var latitud = $(carrera).find('Circuit').find('Location').attr('lat').valueOf(); 
            var longitud = $(carrera).find('Circuit').find('Location').attr('long').valueOf(); 
            var fecha = $(carrera).find('Date')[0].textContent;
            var horaInfo = $(carrera).find('Time')[0].textContent.replace("Z","");

            tableHtml += `
                <tr>
                    <td headers="nombre">${nombreCarrera}</td>
                    <td headers="nombreCircuito">${nombreCircuito}</td>
                    <td headers="coordenadas">${latitud} N, ${longitud} W</td>
                    <td headers="fecha">${fecha}</td>
                    <td headers="hora">${horaInfo} </td>
                </tr>
            `;
        }

        tableHtml += `</table>`;
        $("main").append(tableHtml);
        $("button").hide();
    }

    isTimeout() {
        if (!this.lastApiCall) {
            return true;
        }

        const intervaloMinutos = 10;
        const ahora = new Date();
        const diferenciaMinutos = (ahora - this.lastApiCall) / (1000 * 60);

        return diferenciaMinutos >= intervaloMinutos;
    }

}