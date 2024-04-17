from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()

class Utilisateur(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom_utilisateur = db.Column(db.String(100), unique=True, nullable=False)
    mot_de_passe = db.Column(db.String(100), nullable=False)
    
class Voyage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    destination = db.Column(db.String(100), nullable=False)
    prix = db.Column(db.Float, nullable=False)
    description = db.Column(db.Text, nullable=False)
    image_url = db.Column(db.String(200), nullable=False)
    
class Message(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom = db.Column(db.String(100), nullable=False)
    email = db.Column(db.String(100), nullable=False)
    message = db.Column(db.Text, nullable=False)
    
# Import des modules Flask 
from flask import Flask, render_template, request, redirect, url_for
from flask_sqlalchemy import SQLAlchemy

# Initialisation de l'application Flask 
app = Flask(__name__)
app.config['SQLACHEMY_DATABASE_URI'] = 'sqlite///site.db'
app.config['SECREY_KEY'] = 'your_secret_key'

# Initialisation de la base de données
db = SQLAlchemy(app)

# Définition du modèle de la base de données 
class Utilisateur(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom_utilisateur = db.Column(db.String(100), unique=True, nullable=False)
    mot_de_passe = db.Column(db.String(100), nullable=False)
    
# Route pour l'interface de connexion 
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        nom_utilisateur = request.form['username']
        mot_de_passe = request.form['password']
        utilisateur = Utilisateur.query.filter_by(nom_utilisateur=nom_utilisateur, mot_de_passe=mot_de_passe).first()
        if utilisateur: 
            # Authentification réussie
            return redirect(url_for('accueil'))
        else:
            # Authentification échouée 
            return render_template('Projet Solo Voyage.html', error=True)
        return render_template('Projet Solo Voyage', error=False)
    if __name__ == "__main__":
        app.run(debug=True)
@app.route('/')
def accueil():
    voyages = Voyage.query.all()
    return render_template('accueil.html', voyages=voyages)
@app.route('/envoyer_message', methods=['POST'])
def envoyer_message():
    nom = request.form['nom']
    email = request.form['email']
    message = request.form['message']
    db.session.add(nouveau_message)
    db.session.commit()
    return redirect(url_for('accueil'))

@app.route('/destination/<int:destination_id>')
def destination(id):
    voyage = Voyage.query.get(id)
    return render_template('destination.html', voyage=voyage)
