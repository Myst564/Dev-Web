// Importez les fonctions ou modules que vous souhaitez tester depuis votre fichier app.js
const { displayCharacters } = require('C:\Users\ACS\Documents\GitHub\Dev-Web\HTML\Projet ECF Front End\app.js');

// Testez vos fonctions ou modules
test('test example displaycharacters', () => {
  // Votre assertion ici
  expect(displayCharacters(1, 2)).toBe(3);
});

const { fetchCharacters } = require('C:\Users\ACS\Documents\GitHub\Dev-Web\HTML\Projet ECF Front End\app.js')

test('test example fetchcharacter', () => {
    expect(fetchCharacters(1,2)).toBe(3);
});

const { showModal } = require('C:\Users\ACS\Documents\GitHub\Dev-Web\HTML\Projet ECF Front End\app.js')

test('test example fetchcharacter', () => {
    expect(showModal(1,2)).toBe(3);
});

const { getElementById } = require('C:\Users\ACS\Documents\GitHub\Dev-Web\HTML\Projet ECF Front End\app.js')

test('test example fetchcharacter', () => {
    expect(getElementById(1,2)).toBe(3);
});


const { getRandomCharacters } = require('C:\Users\ACS\Documents\GitHub\Dev-Web\HTML\Projet ECF Front End\app.js')

test('test example fetchcharacter', () => {
    expect(getRandomCharacters(1,2)).toBe(3);
});


const { getCharacters } = require('C:\Users\ACS\Documents\GitHub\Dev-Web\HTML\Projet ECF Front End\app.js')

test('test example fetchcharacter', () => {
    expect(getCharacters(1,2)).toBe(3);
});


