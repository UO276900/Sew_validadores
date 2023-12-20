"use strict";
class Fondo { 
    constructor(nombrePais, nombreCapital, coordsCapital) {
        this.nombrePais=nombrePais;
        this.nombreCapital=nombreCapital;
        this.coordsCapital = coordsCapital;
    }

    obtenerImagenFlickr() {
        var flickrAPI = "http://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?";
        $.ajax({
            url: flickrAPI,
            data: {
                tags: this.nombreCapital,
                tagmode: "any",
                format: "json"
            },
            dataType: "json",
            method: "GET",
            success: function (data) {
                $.each(data.items, function (i, item) {
                    $("body").css("background-image", `url("${item.media.m}")`.replace("_m", "_b"));
                    $("body").css("background-size", "cover");
                });
            },
            error: function (error) {
                console.error('Error en la llamada AJAX:', error);

            }
        });
    }


    
}