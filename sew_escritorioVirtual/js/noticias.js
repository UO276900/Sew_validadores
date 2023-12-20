// Version 1.1 23/10/2021 
"use strict";
class Noticias {
    constructor() {
        if (!(window.File && window.FileReader && window.FileList && window.Blob)) 
        {  
            alert("Este navegador NO soporta el API File y este programa puede no funcionar correctamente !!!");
        }
    }

    readInputFile() {
        const noticias = new Noticias();
        const archivoInput = document.querySelector('input[type="file"]');
    
        if (archivoInput.files.length > 0) {
            const archivo = archivoInput.files[0];
            const tipoTexto = /text.*/;
    
            if (archivo.type.match(tipoTexto)) {
                const main = document.querySelector("main");
                const lector = new FileReader();
                lector.onload = function (evento) {
                    const lineas = lector.result.split('\n');
    
                    lineas.forEach((linea, index) => {
                        if (linea.trim() !== '') {
                            const datosNoticia = linea.split('_');
                            noticias.agregarNuevaNoticia(datosNoticia[0],datosNoticia[1], datosNoticia[2], datosNoticia[3]);
    
                            
                        }
                    });
                };
    
                lector.readAsText(archivo);
            } else {
                console.error("Error: Archivo no válido");
            }
        }
    }

    agregarNuevaNoticia(titular, entradilla, texto, autor) {
        const nuevaSeccion = document.createElement('section');

        const main = document.querySelector("main");
        const parrafoTitular = document.createElement('h3');
        parrafoTitular.innerText = titular;
        nuevaSeccion.appendChild(parrafoTitular);
    
        const parrafoEntradilla = document.createElement('p');
        parrafoEntradilla.innerText = entradilla;
        nuevaSeccion.appendChild(parrafoEntradilla);
    
        const parrafoTexto = document.createElement('p');
        parrafoTexto.innerText = texto;
        nuevaSeccion.appendChild(parrafoTexto);
    
        const parrafoAutor = document.createElement('p');
        parrafoAutor.innerText = "Autor: " + autor;
        nuevaSeccion.appendChild(parrafoAutor);

        main.appendChild(nuevaSeccion);

    }

    agregarNoticiaDesdeFormulario() {
        const inputs = document.querySelectorAll('section input[type="text"], section textarea');
        const titular = inputs[0].value;
        const entradilla = inputs[1].value;
        const texto = inputs[2].value;
        const autor = inputs[3].value;
    
        this.agregarNuevaNoticia(titular, entradilla, texto, autor);

        inputs.forEach(input => {
            input.value = '';
        });
    
    }

}
