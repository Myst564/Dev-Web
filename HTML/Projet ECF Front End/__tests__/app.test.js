// Importez les fonctions ou modules que vous souhaitez tester depuis votre fichier app.js
const { displayCharacters, fetchCharacters, showModal, getCharacters } = require('../app.js');

// Test de la fonction displayCharacters
test('test de displayCharacters', () => {
  const characters = [
    { name: 'Rick', status: 'Alive', gender: 'Male', species: 'Human', image: 'rick.jpg' },
    { name: 'Morty', status: 'Alive', gender: 'Male', species: 'Human', image: 'morty.jpg' }
  ];
  const container = document.createElement('div'); // Créer un conteneur simulé
  document.body.appendChild(container); // Ajouter le conteneur au DOM

  displayCharacters(characters);

  // Vérifier si les cartes des personnages ont été ajoutées au conteneur
  expect(container.children.length).toBe(characters.length);

  // Supprimer le conteneur du DOM après le test
  document.body.removeChild(container);
});

// Test de la fonction fetchCharacters (utilise une approche de test asynchrone)
test('test de fetchCharacters', async () => {
  // Simuler une réponse de l'API
  global.fetch = jest.fn().mockResolvedValue({
    ok: true,
    json: () => Promise.resolve({ results: [{ name: 'Rick' }, { name: 'Morty' }] })
  });

  const characters = await fetchCharacters('https://rickandmortyapi.com/');

  // Vérifier si les personnages ont été récupérés avec succès
  expect(characters.length).toBe(2);
  expect(characters[0].name).toBe('Rick');
  expect(characters[1].name).toBe('Morty');
});

// Test de la fonction showModal
test('test de showModal', () => {
  // Créer un personnage simulé
  const character = { name: 'Rick', image: 'rick.jpg', origin: { name: 'Earth' }, location: { name: 'Planet' }, episode: ['S01E01', 'S01E02'] };

  // Créer des éléments simulés nécessaires pour la modale
  const modal = document.createElement('div');
  modal.id = 'modal';
  document.body.appendChild(modal); // Ajouter la modale au DOM

  showModal(character);

  // Vérifier si la modale est affichée avec les bonnes informations du personnage
  expect(modal.classList.contains('hidden')).toBe(false);
  expect(modal.querySelector('#modalTitle').textContent).toBe('Rick');
  expect(modal.querySelector('#modalImage').src).toBe('rick.jpg');
  expect(modal.querySelector('#modalOrigin').textContent).toBe('Origin: Earth');
  expect(modal.querySelector('#modalLocation').textContent).toBe('Last Location: Planet');
  expect(modal.querySelector('#modalEpisodes').innerHTML).toContain('<li>S01E01</li>');
  expect(modal.querySelector('#modalEpisodes').innerHTML).toContain('<li>S01E02</li>');

  // Supprimer la modale du DOM après le test
  document.body.removeChild(modal);
});

// Test de la fonction getCharacters
test('test de getCharacters', async () => {
  // Simuler une réponse de l'API
  global.fetch = jest.fn().mockResolvedValue({
    ok: true,
    json: () => Promise.resolve({ results: [{ name: 'Rick' }, { name: 'Morty' }] })
  });

  const url = 'https://rickandmortyapi.com/';
  await getCharacters(url);

  // Vérifier si la fonction getCharacters appelle correctement fetchCharacters et displayCharacters
  expect(fetchCharacters).toHaveBeenCalledWith(url);
  expect(displayCharacters).toHaveBeenCalled();
});





