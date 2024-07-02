import os 

class Config: 
    # Configuration de la BDD (base de données)
    SQLALCHEMY_DATABASE_URI = 'sqlite:///site.db' # Url de la BDD SQLite 
    SQLALCHEMY_TRACK_MODIFICATIONS = False 