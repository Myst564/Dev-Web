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
    }
}

// Fonction pour afficher les personnages dans le conteneur
function displayCharacters(characters) {
    const container = document.getElementById('charactersContainer');
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
function showModal(character) {
    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');

    // Remplir les éléments de la modale avec les informations du personnage
    document.getElementById('modalTitle').textContent = character.name;
    document.getElementById('modalImage').src = character.image;
    document.getElementById('modalOrigin').textContent = `Origin: ${character.origin.name}`;
    document.getElementById('modalLocation').textContent = `Last Location: ${character.location.name}`;
    document.getElementById('modalEpisodes').innerHTML = `Episodes: <ul>${character.episode.map(episode => `<li>${episode}</li>`).join('')}</ul>`;

    // Ajouter un écouteur d'événement pour fermer la modale en cliquant sur le bouton Close
    const closeModalButton = document.getElementById('closeModal');
    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Ajouter un écouteur d'événement pour fermer la modale en cliquant en dehors de celle-ci
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
}

// Fonction pour récupérer et afficher 12 personnages aléatoires
async function getRandomCharacters(url) {
    // Ajout du paramètre count=12 à l'URL
    const urlWithCount = new URL(url);
    urlWithCount.searchParams.set('count', '12');
     
    const characters = await fetchCharacters(urlWithCount.href);
    displayCharacters(characters);
}

// Appeler la fonction pour récupérer et afficher 12 personnages au chargement de la page
window.onload = () => {
    getRandomCharacters('https://rickandmortyapi.com/api/character/?per_page=12');
};

// Ajouter des écouteurs d'événements pour les boutons et appeler les fonctions appropriées pour récupérer et afficher les personnages
document.getElementById('randomButton').addEventListener('click', () => {
    getRandomCharacters('https://rickandmortyapi.com/api/character/?per_page=12');
});

document.getElementById('aliveButton').addEventListener('click', () => {
    getRandomCharacters('https://rickandmortyapi.com/api/character/?status=alive&per_page=12');
});

document.getElementById('deadButton').addEventListener('click', () => {
    getRandomCharacters('https://rickandmortyapi.com/api/character/?status=dead&per_page=12');
});

document.getElementById('unknownButton').addEventListener('click', () => {
    getRandomCharacters('https://rickandmortyapi.com/api/character/?status=unknown&per_page=12');
});






