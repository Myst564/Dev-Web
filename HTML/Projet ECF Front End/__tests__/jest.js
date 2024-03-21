module.exports = {
    // Indique à Jest d'utiliser le preset pour les projets JavaScript
    preset: 'ts-jest',
    // Indique à Jest d'ignorer les fichiers dans les dossiers node_modules et build
    testPathIgnorePatterns: [
      '/node_modules/',
      '/build/',
    ],
    // Définir les extensions de fichiers à tester
    moduleFileExtensions: ['js', 'jsx', 'ts', 'tsx'],
    // Définir les dossiers à inclure dans les tests
    roots: ['<app.test.js>/src'],
    // Indique à Jest d'utiliser babel-jest pour transpiler les fichiers JS/JSX
    transform: {
      '^.+\\.jsx?$': 'babel-jest',
      '^.+\\.tsx?$': 'ts-jest',
    },
    // Ignorer les fichiers de configuration de babel et typescript
    transformIgnorePatterns: ['<rootDir>/node_modules/'],
    // Utiliser la couverture de code pour évaluer la qualité des tests
    collectCoverage: true,
    // Définir les dossiers pour la couverture de code
    coverageDirectory: 'coverage',
  };
  