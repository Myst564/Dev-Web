const { displayCharacters, fetchCharacters, showModal, getCharacters } = require('../app.js');

// Importer JSDOM pour simuler le DOM
const jsdom = require('jsdom');
const { JSDOM } = jsdom;
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;

// Test de la fonction displayCharacters
test('test de displayCharacters', () => {
  const characters = [
    { name: 'Rick', status: 'Alive', gender: 'Male', species: 'Human', image: 'rick.jpg' },
    { name: 'Morty', status: 'Alive', gender: 'Male', species: 'Human', image: 'morty.jpg' }
  ];
  const container = document.createElement('div');
  document.body.appendChild(container);

  displayCharacters(characters, container);

  expect(container.children.length).toBe(characters.length);
});

// Test de la fonction fetchCharacters (utilise une approche de test asynchrone)
test('test de fetchCharacters', async () => {
  global.fetch = jest.fn().mockResolvedValue({
    ok: true,
    json: () => Promise.resolve({ results: [{ name: 'Rick' }, { name: 'Morty' }] })
  });

  const characters = await fetchCharacters('https://rickandmortyapi.com/');

  expect(characters.length).toBe(2);
  expect(characters[0].name).toBe('Rick');
  expect(characters[1].name).toBe('Morty');
});

// Test de la fonction showModal
test('test de showModal', () => {
  const character = { name: 'Rick', image: 'rick.jpg', origin: { name: 'Earth' }, location: { name: 'Planet' }, episode: ['S01E01', 'S01E02'] };
  const modal = jest.fn();
  document.body.appendChild = modal;

  showModal(character);

  expect(modal).toHaveBeenCalled();
});

// Test de la fonction getCharacters
test('test de getCharacters', async () => {
  global.fetch = jest.fn().mockResolvedValue({
    ok: true,
    json: () => Promise.resolve({ results: [{ name: 'Rick' }, { name: 'Morty' }] })
  });
  const url = 'https://rickandmortyapi.com/';
  await getCharacters(url);

  expect(fetchCharacters).toHaveBeenCalledWith(url);
  expect(displayCharacters).toHaveBeenCalled();
});











