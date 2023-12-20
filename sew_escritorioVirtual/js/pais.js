"use strict";
class Pais {

    constructor (nombrePais, nombreCapital, nPoblacion){
        this.nombrePais=nombrePais;
        this.nombreCapital=nombreCapital;
        this.nPoblacion=nPoblacion;
        this.rellenaDatosPais();
    }

    rellenaDatosPais() {
        this.tipoGob = "República";
        this.latitud = "-33.45694";
        this.longitud = "-70.62827";
        this.religion = "Católica";
    }
    getNombrePais() {
        return "<main> <h2> " + this.nombrePais + " </h2>";
    }
    getNombreCapital() {
        return "<h3> Capital: " + this.nombreCapital + "</h3>";
    }
    
    getExtraInfo() {
        return " <h3> Datos de Chile </h3> " +
               " <ul> <li> Número de habitantes: " + this.nPoblacion + "</li> " +
               "<li> Tipo de gobierno: " + this.tipoGob + "</li> " +
               "<li> Religión mayoritaria: " + this.religion + "</li> </ul>";
    }
    writeCoordsCapital() {
        document.write("<h3> Coordenadas de la capital </h3>")
        document.write("<p> " + this.latitud + ", " + this.longitud + "</p>");
    }

    getWeatherData() {

        var weatherApi = "https://api.openweathermap.org/data/2.5/forecast?lat="+ this.latitud + "&lon=" +this.longitud +"&appid=6f33956882ff89acf6a2e46822860521&units=metric";

        $.ajax({
            url: weatherApi,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var tableHtml = `
                    <table>
                        <caption> Datos meteorológicos de Santiago de Chile en los próximos 5 días </caption>
                        <tr>
                            <th scope='col' id='dia'>Día</th>
                            <th scope='col' id='tempmax'>Temp Máxima (°C)</th>
                            <th scope='col' id='tempmin'>Temp Mínima (°C)</th>
                            <th scope='col' id='humedad'>Humedad (%)</th>
                            <th scope='col' id='icono'>Icono</th>
                            <th scope='col' id='lluvia'>Lluvia (mm)</th>
                        </tr>
                `;

                for (var i = 0; i < 5; i++) {
                    var dayForecast = data.list[i];
    
                    var maxTemp = dayForecast.main.temp_max;
                    var minTemp = dayForecast.main.temp_min;
                    var humidity = dayForecast.main.humidity;
                    var weatherIcon = dayForecast.weather[0].icon;
                    var rainAmount = dayForecast.rain ? dayForecast.rain["3h"] : 0;
    
                    tableHtml += `
                        <tr>
                            <td headers='dia'>${i + 1}</td>
                            <td headers='tempmax'>${maxTemp}</td>
                            <td headers='tempmin'>${minTemp}</td>
                            <td headers='humedad'>${humidity}</td>
                            <td headers='icono'><img src="http://openweathermap.org/img/w/${weatherIcon}.png" alt="Weather Icon"></td>
                            <td headers='lluvia'>${rainAmount}</td>
                        </tr>
                    `;
                }
    
                tableHtml += `</table>`;
                $("main").append(tableHtml);
            },
            error: function (error) {
                console.error('Error en la llamada AJAX:', error);
            }
        });

    }

    
}
