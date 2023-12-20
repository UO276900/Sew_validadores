// Version 1.1 23/10/2021 
"use strict";
class Memoria {
    constructor() {
        this.elements = [
            {
                element: "HTML5",
                source: "multimedia/HTML5_Badge.svg" 
            },
            {
                element: "CSS3",
                source: "multimedia/CSS3_logo.svg"
            },
            {
                element: "JS",
                source: "multimedia/Javascript_badge.svg"
            },
            {
                element: "PHP",
                source: "multimedia/PHP-logo.svg"
            },
            {
                element: "SVG",
                source: "multimedia/SVG_Logo.svg"
            },
            {
                element: "W3C",
                source: "multimedia/W3C_icon.svg"
            },
            {
                element: "HTML5",
                source: "multimedia/HTML5_Badge.svg" 
            },
            {
                element: "CSS3",
                source: "multimedia/CSS3_logo.svg"
            },
            {
                element: "JS",
                source: "multimedia/Javascript_badge.svg"
            },
            {
                element: "PHP",
                source: "multimedia/PHP-logo.svg"
            },
            {
                element: "SVG",
                source: "multimedia/SVG_Logo.svg"
            },
            {
                element: "W3C",
                source: "multimedia/W3C_icon.svg"
            }
        ];
        this.elements = this.shuffleElements(); 
        this.hasFlippedCard = false;
        this.lockBoard = false;
        this.firstCard = null;
        this.secondCard = null;
    }

    createElements() {
        const main = document.querySelector("main");
        const sec = document.createElement('section');
        const h3 = document.createElement('h3');
        h3.textContent = "Juego de Memoria";
        sec.appendChild(h3);
        for (var i=0; i < this.elements.length; i++) {
            const article = document.createElement("article");
            const header = document.createElement("h3");
            const img = document.createElement("img");

            article.setAttribute("data-element", this.elements[i].element);
            header.textContent = 'Tarjeta de memoria';

            img.src = this.elements[i].source;
            img.alt = this.elements[i].element;

            article.appendChild(header);
            article.appendChild(img);
            sec.appendChild(article);

        }
        main.appendChild(sec);
    }

    addEventListeners() {
        const cards = document.querySelectorAll('article');  
        cards.forEach(card => {
            card.addEventListener('click', this.flipCard.bind(card, this));
        });
    }

    shuffleElements() {
        const cards = [];
        for (const i in this.elements) {
            cards.push({ ...this.elements[i] });
        }
        for (let i = cards.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [cards[i], cards[j]] = [cards[j], cards[i]];
        }
        return cards;
    }

    flipCard(game) {
        
        if (
            this.dataset.state === 'revealed' ||
            game.lockBoard ||
            (game.firstCard && game.firstCard === this)
        ) {
            return;
        }

        this.dataset.state = 'flip';

        this.lastChild.hidden = false;

        

        if (!game.flippedCard) {
            game.flippedCard = true;
            game.firstCard = this;
        } else {
            game.flippedCard = false;
            game.secondCard = this;
            game.checkForMatch();
        }
    }

    unflipCards() {
        this.lockBoard = true;
        setTimeout(() => {
            if (this.firstCard.dataset.state === 'flip')
                this.firstCard.dataset.state = '';
            if (this.secondCard.dataset.state === 'flip')
                this.secondCard.dataset.state = '';
            this.resetBoard();
        }, 1000); 
         
    }

    resetBoard() {
        [this.hasFlippedCard, this.lockBoard] = [false, false];
        [this.firstCard, this.secondCard] = [null, null];
    }

    checkForMatch() {
        const isMatch = this.firstCard.dataset.element === this.secondCard.dataset.element;
        isMatch ? this.disableCards() : this.unflipCards();  
    }

    disableCards() {
        this.firstCard.dataset.state = 'revealed';
        this.secondCard.dataset.state = 'revealed';
        this.resetBoard();
        this.checkForWin(); 
    }

    checkForWin() {
        const revealedCards = document.querySelectorAll('article[data-state="revealed"]');
        if (revealedCards.length === this.elements.length) {
            setTimeout(() => {
                alert('¡Enhorabuena! Has ganado el juego.');
           }, 500); 
        }
    }

}
