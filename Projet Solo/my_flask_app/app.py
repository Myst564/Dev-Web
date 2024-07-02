# app.py

from flask import Flask, request, jsonify
from config import Config
from models import db, User

app = Flask(__name__)
app.config.from_object(Config)

# Initialiser l'extension de la base de données avec l'application
db.init_app(app)

# Créer toutes les tables de la base de données (à utiliser une seule fois)
@app.before_first_request
def create_tables():
    db.create_all()

# Route pour créer un nouvel utilisateur (Create)
@app.route('/users', methods=['POST'])
def create_user():
    data = request.get_json()
    new_user = User(username=data['username'], email=data['email'])
    db.session.add(new_user)
    db.session.commit()
    return jsonify({'message': 'User created!'}), 201

# Route pour lire tous les utilisateurs (Read)
@app.route('/users', methods=['GET'])
def get_users():
    users = User.query.all()
    users_list = [{'id': user.id, 'username': user.username, 'email': user.email} for user in users]
    return jsonify(users_list), 200

# Route pour lire tout les utilisateur par ID (Read)
@app.route('/users/<int:id', methods=['GET'])
def get_users(id):
    user = User.query.get_or_404(id)
    user_data = {'id': user.id, 'username': user.username, 'email': user.email}
    return jsonify(user_data), 200

# Route pour mettre à jour un utilisateur (Update)
@app.route('/users/<int:id>', methods=['GET'])
def update_user(id):
    data = request.get_json()
    user = User.query.get_or_404(id)
    user.username = data.get('username', user.username)
    user.email = data.get('email', user.email)
    db.session.commit()
    return jsonify({'message': 'User updated!'}), 200

# Route pour supprimer un utilisateur (Delete)
@app.route('/users/<int:id>', methods=['DELETE'])
def delete_user(id):
    user = User.query.get_or_404(id)
    db.session.delete(user)
    db.session.commit()
    return jsonify({'message': 'User Deleted!'}), 200

if __name__ == '__Main__':
    app.run(debug=True)
    
# Commande pour lancer l'application: python app.py
    
