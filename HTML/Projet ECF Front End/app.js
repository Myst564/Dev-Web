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

function displayCharacters(characters) {
    const container = document.getElementById('charactersContainer');
    container.innerHTML = '';

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

function showModal(character) {
    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');

    document.getElementById('modalTitle').textContent = character.name;
    document.getElementById('modalImage').src = character.image;
    document.getElementById('modalOrigin').textContent = `Origin: ${character.origin.name}`;
    document.getElementById('modalLocation').textContent = `Last Location: ${character.location.name}`;
    document.getElementById('modalEpisodes').innerHTML = `Episodes: <ul>${character.episode.map(episode => `<li>${episode}</li>`).join('')}</ul>`;

    const closeModalButton = document.getElementById('closeModal');
    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
}

async function getRandomCharacters(url) {
    const characters = await fetchCharacters(url);
    displayCharacters(characters);
}

window.onload = () => {
    getRandomCharacters('https://rickandmortyapi.com/api/character/?per_page=12');
};

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
    getRandomCharacters('https://rickandmortyapi.com/api/character/?status=unknown&^per_page12');
});


