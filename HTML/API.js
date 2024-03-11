// Création de données à envoyer
const postData = {
  key1: 'valeur1',
  key2: 'valeur2'
};

// Options de la requête
const options = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json' // Spécifie le type de contenu JSON
  },
  body: JSON.stringify(postData) // Convertit les données en format JSON
};

// URL de l'API à laquelle envoyer la requête
const url = 'https://example.com/api-endpoint';

// Envoi de la requête
fetch(url, options)
  .then(response => {
    if (!response.ok) {
      throw new Error('Erreur lors de la requête');
    }
    return response.json(); // Convertit la réponse en JSON
  })
  .then(data => {
    console.log('Réponse de l\'API:', data);
  })
  .catch(error => {
    console.error('Erreur:', error);
  });
