// Importez les fonctions ou modules que vous souhaitez tester depuis votre fichier app.js
const { displayCharacters, fetchCharacters, showModal, getElementById, getRandomCharacters, getCharacters } = require('../app.js');

// Testez vos fonctions ou modules
test('test example displaycharacters', () => {
  // Votre assertion ici
  expect(displayCharacters(1, 2)).toBe(3);
});

test('test example fetchcharacter', () => {
    expect(fetchCharacters(1,2)).toBe(3);
});

test('test example fetchcharacter', () => {
    expect(showModal(1,2)).toBe(3);
});

test('test example fetchcharacter', () => {
    expect(getElementById(1,2)).toBe(3);
});


test('test example fetchcharacter', () => {
    expect(getRandomCharacters(1,2)).toBe(3);
});


test('test example fetchcharacter', () => {
    expect(getCharacters(1,2)).toBe(3);
});




