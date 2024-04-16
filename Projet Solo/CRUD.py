from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from dotenv import load_dotenv
import os 

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///database.db'
db = SQLAlchemy(app)

# Modèle destination
class Destination(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(50), unique=True, nullable=False)
    password = db.Column(db.String(50), nullable=False)
    
# Modèle destination
class Destination(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)
    
# Route pour créer une destination 
@app.route('/destination', methods=['Post'])
def create_destination():
    data = request.get_json()
    new_destination = Destination(name=data['name'], description=data['description'])
    db.session.add(new_destination)
    db.session.commit()
    return jsonify({'message': 'Destination créée avec succès'}), 201

# Route pour récupérer toutes les destinations 
@app.route('/destinations', methods=['GET'])
def get_destinations():
    destinations = Destination.query.all()
    output = []
    for destination in destinations:
        destination_data = {'id': destination.id, 'name':destination.name, 'description': destination.description}
        output.append(destination_data)
    return jsonify({'destinations': output})

if __name__ == '__main__':
    app.run(debug=True)
