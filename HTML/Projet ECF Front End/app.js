// Fonction asynchrone pour récupérer les personnages depuis l'API
async function fetchCharacters(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Failed to fetch characters');
        }
        const data = await response.json();
        return data.results;
    } catch (error) {
        console.error(error);
        return []; // Retourne un tableau vide en cas d'erreur
    }
}

// Fonction pour afficher les personnages dans le conteneur
function displayCharacters(characters, container) {
    if (!container) return; // Vérifie si le conteneur existe

    container.innerHTML = '';

    // Parcourir chaque personnage et créer une carte pour l'afficher
    characters.forEach(character => {
        const card = document.createElement('div');
        card.classList.add('card');
        card.innerHTML = `
            <img src="${character.image}" alt="${character.name}" class="w-full h-auto rounded">
            <h3 class="text-xl font-semibold mt-2">${character.name}</h3>
            <p>Status: ${character.status}</p>
            <p>Gender: ${character.gender}</p>
            <p>Species: ${character.species}</p>
        `;
        card.addEventListener('click', () => {
            showModal(character);
        });
        container.appendChild(card);
    });
}

// Fonction pour afficher la modale avec les détails du personnage
function showModal(character, modal) {
    if (!modal) return; // Vérifie si la modale existe

    modal.classList.remove('hidden');

    // Remplir les éléments de la modale avec les informations du personnage
    modal.querySelector('#modalTitle').textContent = character.name;
    modal.querySelector('#modalImage').src = character.image;
    modal.querySelector('#modalOrigin').textContent = `Origin: ${character.origin.name}`;
    modal.querySelector('#modalLocation').textContent = `Last Location: ${character.location.name}`;
    modal.querySelector('#modalEpisodes').innerHTML = `Episodes: <ul>${character.episode.map(episode => `<li>${episode}</li>`).join('')}</ul>`;

    // Ajouter un écouteur d'événement pour fermer la modale en cliquant sur le bouton Close
    const closeModalButton = modal.querySelector('#closeModal');
    if (closeModalButton) {
        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

    // Ajouter un écouteur d'événement pour fermer la modale en cliquant en dehors de celle-ci
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
}

// Fonction pour récupérer et afficher des personnages en fonction de l'URL spécifiée
async function getCharacters(url, container) {
    const characters = await fetchCharacters(url);
    displayCharacters(characters.slice(0, 12), container); // Limiter à 12 personnages
}

// Appeler la fonction pour récupérer et afficher 12 personnages au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('charactersContainer');
    getCharacters('https://rickandmortyapi.com/api/character/?per_page=12', container); // Au chargement, afficher des personnages aléatoires
});

// Ajouter des écouteurs d'événements pour chaque bouton et appeler les fonctions appropriées
document.getElementById('randomButton').addEventListener('click', () => {
    // Générer une nouvelle URL avec un paramètre de timestamp pour forcer le rechargement des données
    const timestamp = new Date().getTime()
    const url = `https://rickandmortyapi.com/api/character/?per_page=12&timestamp=${timestamp}`;

    // Passer le conteneur comme argument
    const container = document.getElementById('charactersContainer');
    getCharacters(url, container);
});

// Ajoutez des écouteurs d'évènements pour les autres boutons et appeler les fonction appropriées
document.getElementById('aliveButton').addEventListener('click', () => {
    const container = document.getElementById('charactersContainer');
    getCharacters('https://rickandmortyapi.com/api/character/?status=alive&per_page=12', container); // Pour le bouton "Random Alive Characters"
});

document.getElementById('deadButton').addEventListener('click', () => {
    const container = document.getElementById('charactersContainer');
    getCharacters('https://rickandmortyapi.com/api/character/?status=dead&per_page=12', container); // Pour le bouton "Random Dead Characters"
});

document.getElementById('unknownButton').addEventListener('click', () => {
    const container = document.getElementById('charactersContainer');
    getCharacters('https://rickandmortyapi.com/api/character/?status=unknown&per_page=12', container); // Pour le bouton "Random Unknown Characters"
});








