# Utilisez la base d'image de Node.js
FROM Node:14 

# Mettre en place la direction de travail
WORKDIR /app

#Copier package.json et package-lock.json 
COPY package*.json ./

# Installer les dépendenciels
RUN npm install 

# Copier la source app du code
COPY . / /app/

# Exposer le portage
EXPOSE 3000

# Lancez l'application
CMD ["npm", "start"]