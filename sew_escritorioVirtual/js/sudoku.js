
"use strict";
class Sudoku {
  constructor() {
    this.boardString = "3.4.69.5....27...49.2..4....2..85.198.9...2.551.39..6....8..5.32...46....4.75.9.6";
    this.rows = 9;
    this.cols = 9;
    this.boardArray = this.initializeBoard();
    this.start();

  }

  initializeBoard() {
    const boardArray = new Array(this.rows);
    for (let i = 0; i < this.rows; i++) {
      boardArray[i] = new Array(this.cols);
    }
    return boardArray;
  }

  start() {
    for (let i = 0; i < this.rows; i++) {
      for (let j = 0; j < this.cols; j++) {
        const index = i * this.cols + j;
        const value = this.boardString[index];

        value == "." ? this.boardArray[i][j] = 0 : this.boardArray[i][j] = parseInt(value);
      }
    }
  }

  createStructure() {
    const mainElement = document.querySelector('main');
    const sec = document.createElement('section');
    const h3 = document.createElement('h3');
    h3.textContent = 'Sudoku';
    sec.appendChild(h3);
    for (let i = 0; i < this.rows; i++) {
      for (let j = 0; j < this.cols; j++) {
        const paragraph = document.createElement('p');
        paragraph.dataset.row = i;
        paragraph.dataset.col = j;
        sec.appendChild(paragraph);

      }
    }
    mainElement.appendChild(sec);
  }

  paintSudoku() {
    this.createStructure();
    const paragraphs = document.querySelectorAll('main section p');

    for (let index = 0; index < paragraphs.length; index++) {
      const paragraph = paragraphs[index];
      const row = Math.floor(index / this.cols);
      const col = index % this.cols;
      const value = this.boardArray[row][col];

      if (value === 0) {
        paragraph.addEventListener('click', this.cellClick.bind(this));
        paragraph.addEventListener('keydown', this.handleKeyDown.bind(this));
        paragraph.setAttribute('tabindex', '0');
      } else {
        paragraph.textContent = value;
        paragraph.dataset.state = 'blocked';
      }
    }
  }

  cellClick(event) {
    const paragraphs = document.querySelectorAll('main section p');

    paragraphs.forEach(paragraph => {
      if (paragraph.dataset.state !== 'blocked') {
        paragraph.dataset.state = '';
      }
    });

    const clickedParagraph = event.target;
    clickedParagraph.dataset.state = 'clicked';
  }

  introduceNumber(key) {
    const selectedCell = document.querySelector("p[data-state='clicked']");
    if (selectedCell) {
      const row = parseInt(selectedCell.dataset.row);
      const col = parseInt(selectedCell.dataset.col);
      if (
        this.isNumberValidInRow(row, key) &&
        this.isNumberValidInColumn(col, key) &&
        this.isNumberValidInSubgrid(row, col, key)
      ) {
        this.boardArray[row][col] = parseInt(key);
        selectedCell.textContent = key;
        selectedCell.dataset.state = 'correct';

        if (this.isSudokuCompleted()) {
          alert('¡Sudoku completado!');
        }
      } else {
        alert('Número no válido para la casilla seleccionada. Inténtalo de nuevo.');
      }
    }
  }

  handleKeyDown(event) {
    const key = event.key;
    const isNumericKey = /^[1-9]$/.test(key);

    if (isNumericKey) {
      event.preventDefault();
      this.introduceNumber(key);
    }
  }

  isNumberValidInRow(row, num) {
    return !this.boardArray[row].includes(parseInt(num));
  }

  isNumberValidInColumn(col, num) {
    for (let i = 0; i < this.rows; i++) {
      if (this.boardArray[i][col] === parseInt(num)) {
        return false;
      }
    }
    return true;
  }

  isNumberValidInSubgrid(row, col, num) {
    const subgridRowStart = Math.floor(row / 3) * 3;
    const subgridColStart = Math.floor(col / 3) * 3;

    for (let i = subgridRowStart; i < subgridRowStart + 3; i++) {
      for (let j = subgridColStart; j < subgridColStart + 3; j++) {
        if (this.boardArray[i][j] === parseInt(num)) {
          return false;
        }
      }
    }
    return true;
  }

  isSudokuCompleted() {
    for (let i = 0; i < this.rows; i++) {
      for (let j = 0; j < this.cols; j++) {
        if (this.boardArray[i][j] === 0) {
          return false;
        }
      }
    }
    return true;
  }

}