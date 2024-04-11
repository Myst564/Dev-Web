-- Création de la base de données pour une librairie (exercice)

-- Création de la table des auteurs 
CREATE TABLE Auteurs (
    id INT PRIMARY KEY, 
    nom VARCHAR(50),
    nationalite VARCHAR(50)
);
--@block
-- Création de la table des livres 
CREATE TABLE Livres (
    id INT PRIMARY KEY,
    titre VARCHAR(100),
    auteur_id INT, 
    genre VARCHAR(50),
    annee_publication INT,
    FOREIGN KEY (auteur_id) REFERENCES Auteurs(id)
);

-- Création de la table des emprunts 
CREATE TABLE Emprunts (
    id INT PRIMARY KEY,
    livre_id INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (livre_id) REFERENCES Livres(id)
);

-- Insérez des données dans la table AUteurs 
INSERT INto Auteurs (id, nom, nationalite) VALUES
(1,'Victor Hugo', 'Français'),
(2, 'Jane Austen', 'Anglais'),
(3, 'Gabriel Garcia Márquez', 'Colombien');

-- Insérer des données dans la table Livres
INSERT INTO Livres (id, titre, auteur_id, genre, annee_publication) VALUES
(1, 'Les Misérables', 1, 'Roman historique', 1862),
(2, 'Orgueil et Préjugés', 2, 'Roman', 1813),
(3, 'Cent ans de solitude', 3, 'Roman', 1967);

-- Insérer des données dans la table Emprunts
INSERT INTO Emprunts (id, livre_id, date_emprunt, date_retour) VALUES
(1, 1, '2023-03-15', '2023-04-15'),
(2, 2, '2023-04-01', '2023-05-01'),
(3, 3, '2023-05-10', '2023-06-10');

-- Requête pour récupérer les livres empruntés avec les détails sur les auteurs
SELECT Livres.titre, auteurs.nom AS auteur, Livres.annee_publication, Emprunts.date_emprunt, Emprunts.date_retour
FROM Livres
JOIN Auteurs ON Livres.auteur_id = Auteurs.id
JOIN Emprunts ON Livres.id = Emprunts.livre_id;

