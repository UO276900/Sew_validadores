"use strict";

class ApiJuego {
    constructor() {
        this.cantantes = [
            {
                element: "Peso Pluma",
                source: "multimedia/pesopluma.jpg",
                audio: "./multimedia/pesopluma.mp3"
            },
            {
                element: "Duki",
                source: "multimedia/duki.jpg",
                audio: "./multimedia/duki.mp3"
            },
            {
                element: "Nicki Nicole",
                source: "multimedia/nicki.jpg",
                audio: "./multimedia/nicki.mp3"
            },
            {
                element: "Melendi",
                source: "multimedia/melendi.jpg",
                audio: "./multimedia/melendi.mp3"
            },
            {
                element: "Estopa",
                source: "multimedia/estopa.jpg",
                audio: "./multimedia/estopa.mp3"
            },
            {
                element: "Amaral",
                source: "multimedia/amaral.jpg",
                audio: "./multimedia/amaral.mp3"
            },
        ];
        this.nombres = [
            {
                element: "Peso Pluma"
            },
            {
                element: "Duki"
            },
            {
                element: "Nicki Nicole"
            },
            {
                element: "Melendi"
            },
            {
                element: "Estopa"
            },
            {
                element: "Amaral"
            },
        ];
        this.createGameStructure();
        $('button').on('click', function(){
            var sections = document.querySelectorAll('section');
            var elem = sections[2];
            if(document.webkitFullscreenElement) {
              document.webkitCancelFullScreen();
              this.textContent = "Expandir"
            }
            else {
              elem.webkitRequestFullScreen();
            };
          });
        
        this.shuffleCards();
        this.createCards();
        this.createElements();
        this.handleDragEvents();
        this.currentAudioNode = null;
    }

    shuffleCards() {
        for (let i = this.cantantes.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [this.cantantes[i], this.cantantes[j]] = [this.cantantes[j], this.cantantes[i]];
        }

    }

    createGameStructure() {
        const main = document.querySelector("main");
        const sectionGame = document.createElement("section");
        const button = document.createElement("button");
        button.textContent = "Expandir"
        const h3 = document.createElement("h3");
        h3.textContent = "¿Quién canta la canción?";
        sectionGame.append(h3);
        sectionGame.append(button);
        main.append(sectionGame);
    }

    createCards() {
        var sections = document.querySelectorAll('section');
        var sec = sections[2];
        for (var i = 0; i < this.cantantes.length; i++) {
            const article = document.createElement("article");
            const header = document.createElement("h3");
            const img = document.createElement("img");
    
            article.setAttribute("data-element", this.cantantes[i].element);
            header.textContent = 'Play';
    
            img.src = this.cantantes[i].source;
            img.alt = this.cantantes[i].element;
            const audio = this.cantantes[i].audio;

            article.addEventListener('click', () => this.clickCard(audio));
    
            article.appendChild(header);
            article.appendChild(img);
            sec.appendChild(article);
        }
    }
    
    createElements() {
        var sections = document.querySelectorAll('section');
        var sec = sections[2];
        for (var i = 0; i < this.nombres.length; i++) {
            const article = document.createElement("article");
            const header = document.createElement("h3");
    
            article.setAttribute("data-element", this.nombres[i].element);
            article.setAttribute("draggable", true);
            header.textContent = this.nombres[i].element;
    
            article.appendChild(header);
            sec.appendChild(article);
        }
    }


    clickCard(file) {
        if (this.currentAudioNode !== null) {
            this.currentAudioNode.stop();
            this.currentAudioNode = null;
        } else {
            this.playAudio(file);
        }
        
    }

    playAudio(audioFile) {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const audioNode = audioContext.createBufferSource();

        fetch(audioFile)
            .then(response => response.arrayBuffer())
            .then(buffer => audioContext.decodeAudioData(buffer))
            .then(decodedData => {
                audioNode.buffer = decodedData;
                audioNode.connect(audioContext.destination);
                audioNode.start();

                this.currentAudioNode = audioNode;
            })
            .catch(error => console.error('Error al cargar y reproducir el audio:', error));
    }

    handleDragEvents(){
        var drag = null;
        function handleDragStart(e) {
            this.dataset.state = 'moving';
            drag = this;
          }
        
        function handleDragEnd(e) {
            this.dataset.state = '';
          }
        
        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
        
            return false;
            }
        
        function handleDragEnter(e) {
            this.classList.add('over');
            }
        
        function handleDragLeave(e) {
            this.classList.remove('over');
        }
        
        function handleDrop(e) {
            e.stopPropagation();
            const targetElement = this.dataset.element;
        
            if (drag.dataset.element === targetElement && this.dataset.state !== 'confirmed') {
                this.dataset.state = 'confirmed';
                const articles = document.querySelectorAll("article[data-element]");
                const confirmedElements = Array.from(articles).filter(article => article.dataset.state === 'confirmed');

                if (confirmedElements.length === 6) {
                    setTimeout(function() {
                        alert("¡Felicidades, has completado el juego!");
                    }, 500);
        }
            }
          
            this.classList.remove('over');
            return false;
        }

        const articles = document.querySelectorAll("article");
        articles.forEach(function(article) {

            article.addEventListener('dragstart', handleDragStart);
            article.addEventListener('dragend', handleDragEnd);

            article.addEventListener('dragover', handleDragOver);
            article.addEventListener('dragenter', handleDragEnter);
            article.addEventListener('dragleave', handleDragLeave);
            article.addEventListener('drop', handleDrop);
        });
    }

}


