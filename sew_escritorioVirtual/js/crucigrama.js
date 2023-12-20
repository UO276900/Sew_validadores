// Version 1.1 23/10/2021 
"use strict";
class Crucigrama {
    constructor(nivel) {
        this.nivel = nivel;
        this.board = []; 
        this.numColumns = 9;
        this.numRows = 11;
        this.init_time = null; 
        this.end_time = null; 
        this.gameActive = true;
  

        this.initializeBoard(nivel);
        this.start();
    }

    initializeBoard(nivel) {
        let tableroString = "";
        switch (nivel) {
            case "facil":
                tableroString =
                    "4,*,.,=,12,#,#,#,5,#,#,*,#,/,#,#,#,*,4,-,.,=,.,#,15,#,.,*,#,=,#,=,#,/,#,=,.,#,3,#,4,*,.,=,20,=,#,#,#,#,#,=,#,#,8,#,9,-,.,=,3,#,.,#,#,-,#,+,#,#,#,*,6,/,.,=,.,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,6,#,8,*,.,=,16";
                break;
            case "medio":
                tableroString =
                    "12,*,.,=,36,#,#,#,15,#,#,*,#,/,#,#,#,*,.,-,.,=,.,#,55,#,.,*,#,=,#,=,#,/,#,=,.,#,15,#,9,*,.,=,45,=,#,#,#,#,#,=,#,#,72,#,20,-,.,=,11,#,.,#,#,-,#,+,#,#,#,*,56,/,.,=,.,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,12,#,16,*,.,=,32";
                break;
            case "dificil":
                tableroString =
                    "4,.,.,=,36,#,#,#,25,#,#,*,#,.,#,#,#,.,.,-,.,=,.,#,15,#,.,*,#,=,#,=,#,.,#,=,.,#,18,#,6,*,.,=,30,=,#,#,#,#,#,=,#,#,56,#,9,-,.,=,3,#,.,#,#,*,#,+,#,#,#,*,20,.,.,=,18,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,18,#,24,.,.,=,72";
                break;
            default:
                tableroString =
                    "4,*,.,=,12,#,#,#,5,#,#,*,#,/,#,#,#,*,4,-,.,=,.,#,15,#,.,*,#,=,#,=,#,/,#,=,.,#,3,#,4,*,.,=,20,=,#,#,#,#,#,=,#,#,8,#,9,-,.,=,3,#,.,#,#,-,#,+,#,#,#,*,6,/,.,=,.,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,6,#,8,*,.,=,16";
        }

        const tableroArray = tableroString.split(",");
        let currentIndex = 0;
        for (let i = 0; i < this.numRows; i++) {
            this.board[i] = [];
            for (let j = 0; j < this.numColumns; j++) {
                this.board[i][j] = tableroArray[currentIndex];
                currentIndex++;
            }
        }
    }

    start() {
        for (let i = 0; i < this.numRows; i++) {
            for (let j = 0; j < this.numColumns; j++) {
                const valorCelda = this.board[i][j];

                if (!isNaN(valorCelda)) {
                    this.board[i][j] = parseInt(valorCelda);
                } else if (valorCelda === ".") {
                    this.board[i][j] = 0;
                } else if (valorCelda === "#") {
                    this.board[i][j] = -1;
                }
            }
        }
    }

    paintMathword() {
        const section = $("main section:nth-child(3)");
        const sec = $("<section></section>");
        const h3 = $("<h3> Crucigrama </h3>");
        sec.append(h3);
        section.after(sec);
    
        for (let i = 0; i < this.numRows; i++) {
            for (let j = 0; j < this.numColumns; j++) {
                const par = $("<p></p>");
                sec.append(par);
    
                const cellValue = this.board[i][j];
                const paragraph = par;
                paragraph.attr("data-row", i); 
                paragraph.attr("data-col", j);
    
                if (cellValue === 0) {
                    paragraph.on('click', function() {
                        var pClicked = $("p[data-state=clicked]");
                        pClicked.attr("data-state", "");
                        paragraph.attr("data-state","clicked");
                    });
                } else if (cellValue === -1) {
                    paragraph.attr("data-state", "empty");
                } else if (cellValue > 0) {
                    paragraph.text(cellValue);
                    paragraph.attr("data-state", "blocked");
                } else {
                    paragraph.text(cellValue);
                    paragraph.attr("data-state", "blocked");
                }
            }
        }
        const currentDate = new Date();
        this.init_time = currentDate;
    }

    check_win_condition() {
        for (let i = 0; i < this.numRows; i++) {
            for (let j = 0; j < this.numColumns; j++) {
                if (this.board[i][j] == 0) {
                    return false;
                }
            }
        }
        return true;
    }

    calculate_date_difference() {
        if (this.initTime === null || this.endTime === null) {
            return "El temporizador no se ha iniciado o detenido correctamente.";
        }

        const tiempoTotal = (this.end_time - this.init_time) / 1000;

        let horas = Math.floor(tiempoTotal / 3600);
        let minutos = Math.floor((tiempoTotal % 3600) / 60);
        let segundos = Math.floor(tiempoTotal % 60);

        horas = horas < 10 ? "0" + horas : horas;
        minutos = minutos < 10 ? "0" + minutos : minutos;
        segundos = segundos < 10 ? "0" + segundos : segundos;

        return horas + ":" + minutos + ":" + segundos;
    }

    introduceElement(value) {
        var expression_row = true;
        var expression_col = true;

        var actualCell = $("p[data-state='clicked']");
        var actualRow = actualCell.data('row'); 
        var actualCol = actualCell.data('col'); 

        this.board[actualRow][actualCol] = value;
        if (value === 0) {
            return;
        }
        const siguienteCeldaDerecha = this.board[actualRow][actualCol + 1];

        if (actualCol + 1 <= this.numColumns-1) {
            if (siguienteCeldaDerecha === -1) {
                console.log('La expresión vertical está deshabilitada en la posición seleccionada.');
            } else {
                let col = actualCol + 1;
        
                while (col < this.numColumns && this.board[actualRow][col] !== '=') {
                    col++;
                }
        
                if (col < this.numColumns) {
                    const expression = this.board[actualRow][col - 2];
                    const first_number = this.board[actualRow][col - 3];
                    const second_number = this.board[actualRow][col - 1];
                    const result = this.board[actualRow][col + 1];

                    if (first_number !== 0 && second_number !== 0 && expression !== 0 && result !== 0) {
                        const mathematicalExpression = [first_number, expression, second_number].join('');
                        const evaluationResult = eval(mathematicalExpression);
        
                        if (evaluationResult != result) {
                            expression_row = false;
                        }
                    }
        
                } else {
                    console.log('No se encontró el carácter "=" hacia abajo.');
                }
            }
        }
        if (actualRow + 1 <= this.numRows-1) {
            const siguienteCeldaAbajo = this.board[actualRow + 1][actualCol];

            if (siguienteCeldaAbajo === -1) {
                console.log('La expresión vertical está deshabilitada en la posición seleccionada.');
            } else {
                let fila = actualRow + 1;

                while (fila < this.numRows && this.board[fila][actualCol] !== '=') {
                    fila++;
                }

                if (fila < this.numRows) {
                    const expression = this.board[fila - 2][actualCol];
                    const first_number = this.board[fila - 3][actualCol];
                    const second_number = this.board[fila - 1][actualCol];
                    const result = this.board[fila + 1][actualCol];

                    if (first_number !== 0 && second_number !== 0 && expression !== 0 && result !== 0) {
                        const mathematicalExpression = [first_number, expression, second_number].join('');
                        const evaluationResult = eval(mathematicalExpression);

                        if (evaluationResult != result) {
                            expression_col = false;
                        }
                    }
                } else {
                    console.log('No se encontró el carácter "=" hacia abajo.');
                }
            }
        }

        if (expression_row && expression_col) {
            actualCell.text(value);
            actualCell.attr("data-state", "correct");
        } else {
            actualCell.text("");
            actualCell.attr("data-state", "");
            var pClicked = $("p[data-state=clicked]");
            pClicked.attr("data-state", "");
            alert("El elemento introducido no es correcto para la casilla seleccionada.");
        }
    
        if (this.check_win_condition()) {
            this.end_time = new Date();
            const tiempoTranscurrido = this.calculate_date_difference();
            this.endGame();
            this.createRecordForm(tiempoTranscurrido);

            setTimeout(function() {
                alert("¡Has completado el crucigrama en " + tiempoTranscurrido + "!");
            }, 500);
        }
    }

    endGame() {
        document.removeEventListener("keydown", this.handleKeyDown);
        this.gameActive = false;
    }

    createRecordForm(tiempoTranscurrido) {
        const resultadosSection = $("<section></section>");
        resultadosSection.append("<h3>Resultados</h3>");
    
        const formElement = $("<form action='#' method='post'></form>");
    
        // Nombre
        const nombreLabel = $("<label></label>");
        nombreLabel.attr("for", "nombreInput");
        nombreLabel.text("Nombre");
        formElement.append(nombreLabel);
    
        const nombreInput = $("<input>");
        nombreInput.attr("type", "text");
        nombreInput.attr("name", "nombre");
        nombreInput.attr("id", "nombreInput");
        formElement.append(nombreInput);
    
        // Apellidos
        const apellidosLabel = $("<label></label>");
        apellidosLabel.attr("for", "apellidosInput");
        apellidosLabel.text("Apellidos");
        formElement.append(apellidosLabel);
    
        const apellidosInput = $("<input>");
        apellidosInput.attr("type", "text");
        apellidosInput.attr("name", "apellidos");
        apellidosInput.attr("id", "apellidosInput");
        formElement.append(apellidosInput);
    
        // Nivel
        const nivelLabel = $("<label></label>");
        nivelLabel.attr("for", "nivelInput");
        nivelLabel.text("Nivel");
        formElement.append(nivelLabel);
    
        const nivelInput = $("<input>");
        nivelInput.attr("type", "text");
        nivelInput.attr("name", "nivel");
        nivelInput.attr("id", "nivelInput");
        nivelInput.attr("value", this.nivel);
        nivelInput.attr("readonly", true);
        formElement.append(nivelInput);
    
        // Tiempo
        const tiempoLabel = $("<label></label>");
        tiempoLabel.attr("for", "tiempoInput");
        tiempoLabel.text("Tiempo");
        formElement.append(tiempoLabel);
    
        const tiempoInput = $("<input>");
        tiempoInput.attr("type", "text");
        tiempoInput.attr("name", "tiempo");
        tiempoInput.attr("id", "tiempoInput");
        tiempoInput.attr("value", tiempoTranscurrido);
        tiempoInput.attr("readonly", true);
        formElement.append(tiempoInput);
    
        const submitButton = $("<input>");
        submitButton.attr("type", "submit");
        submitButton.attr("name","insertar_registro");
        submitButton.attr("value", "Guardar Record");
        formElement.append(submitButton);
    
        resultadosSection.append(formElement);
    
        $("main").append(resultadosSection);
    }
    
}