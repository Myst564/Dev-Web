from flask import Flask, render_template, request, redirect, url_for
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///users.db' # Chemin vers la base de donnée 
db = SQLAlchemy(app)

# Modèle de la base de données pour les utilisateurs
class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(50), unique=True, nullable=False)
    password = db.Column(db.String(50), nullable=False)
    
# Route pour la page de connexion 
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        user = User.query.filter_by(username=username, password=password).first()
        if user: 
            return redirect(url_for('accueil'))
        else:
            return "Invalid username or password. Please try again"
        return render_template('accueil.html')
    
# Route pour la page d'accueil 
@app.route('/accueil')
def accueil():
    return render_template('accueil.html')

if __name__ == '__main__':
    db.create_all() # Crée la base de données si elle n'existe pas encore
    app.run(debug=True)

    