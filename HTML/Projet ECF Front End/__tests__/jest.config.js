module.exports = {
  // Utiliser l'environnement de test jsdom pour simuler le navigateur
  testEnvironment: 'jsdom',
  // Ignorer les dossiers node_modules et build
  testPathIgnorePatterns: [
    '/node_modules/',
    '/build/',
  ],
  // Définir les extensions de fichiers à tester
  moduleFileExtensions: ['js', 'jsx'],
  // Définir les dossiers à inclure dans les tests
  roots: ['<rootDir>/__tests__'],
  // Utiliser babel-jest pour transpiler les fichiers JS/JSX
  transform: {
    '^.+\\.jsx?$': 'babel-jest',
  },
  // Ignorer les fichiers de configuration de babel
  transformIgnorePatterns: ['<rootDir>/node_modules/'],
  // Activer la couverture de code pour évaluer la qualité des tests
  collectCoverage: true,
  // Définir le dossier de la couverture de code
  coverageDirectory: 'coverage',
};




  