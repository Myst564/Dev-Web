// Importez les fonctions ou modules que vous souhaitez tester depuis votre fichier app.js
const { displayCharacters, fetchCharacters, showModal, getCharacters } = require('../app.js');

// Test de la fonction displayCharacters
test('test de displayCharacters', () => {
  const characters = [
    { name: 'Rick', status: 'Alive', gender: 'Male', species: 'Human', image: 'rick.jpg' },
    { name: 'Morty', status: 'Alive', gender: 'Male', species: 'Human', image: 'morty.jpg' }
  ];
  // Créer un conteneur simulé
  const container = document.createElement('div');
  // Ajouter le conteneur au DOM
  document.body.appendChild(container);

  displayCharacters(characters, container);

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
  const modal = { classList: { contains: jest.fn(() => false) }, querySelector: jest.fn() };
  const body = { appendChild: jest.fn(), removeChild: jest.fn() };
  document.body = body;
  document.createElement = jest.fn(() => modal);

  showModal(character);

  // Vérifier si la modale est affichée avec les bonnes informations du personnage
  expect(modal.classList.contains).toHaveBeenCalledWith('hidden');
  expect(modal.querySelector).toHaveBeenCalledWith('#modalTitle');
  expect(modal.querySelector).toHaveBeenCalledWith('#modalImage');
  expect(modal.querySelector).toHaveBeenCalledWith('#modalOrigin');
  expect(modal.querySelector).toHaveBeenCalledWith('#modalLocation');
  expect(modal.querySelector).toHaveBeenCalledWith('#modalEpisodes');

  // Supprimer la modale du DOM après le test
  expect(body.removeChild).toHaveBeenCalledWith(modal);
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







