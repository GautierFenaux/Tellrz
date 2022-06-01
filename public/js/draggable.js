//Sélectionner les li 

// importer tableau php convertir en json, 
// à la fin fait ressortir le nouvel ordre du tableau
//Updater le chapter si position change ?
//donner à chapter un olonne position ? 

const chaptersArray = document.getElementsByClassName("box");

let dragStartIndex;



document.addEventListener('DOMContentLoaded', (event) => {

function handleDragStart(e) {
  this.style.opacity = '0.4';
  dragSrcEl = this;
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/html', this.innerHTML);

	dragStartIndex = +this.closest('li').getAttribute('data-index');

}
function handleDragEnd(e) {
this.style.opacity = '1';

items.forEach(function (item) {
item.classList.remove('over');
});
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

  if (dragSrcEl !== this) {
    dragSrcEl.innerHTML = this.innerHTML;
    this.innerHTML = e.dataTransfer.getData('text/html');
  }

  return false;
}

let items = document.querySelectorAll('.box');
items.forEach(function (item) {
item.addEventListener('dragstart', handleDragStart);
item.addEventListener('dragover', handleDragOver);
item.addEventListener('dragenter', handleDragEnter);
item.addEventListener('dragleave', handleDragLeave);
item.addEventListener('dragend', handleDragEnd);
item.addEventListener('drop', handleDrop);
item.addEventListener('drop', handleDrop);

});
}) 
