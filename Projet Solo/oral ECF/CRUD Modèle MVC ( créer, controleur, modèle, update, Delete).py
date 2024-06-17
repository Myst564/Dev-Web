#Créer

#Modèle: Créer une nouvelle entrée dans la BDD

class UserModel:
    def create_user(self, user_data):
        # Code pour insérer user_data dans la base de données
        
#Contrôleur: Récupère les données du formulaire et appelle le modèle pour créer l'utilisateur
        
class UserController:
    def create_user(self, request):
        user_data = request.form['username']
        UserModel().create_user(user_data)
        # Rediriger vers une autre page ou afficher un message de succès
        
#Read 

#Modèle: Récupère les données dans la BDD

class UserModel:
    def get_user(self, user_id):
        # Code pour récupérer les données utilisateur de la base de données
        
# Contrôleur: Récupère l'utilisateur du modèle et l'envoie à la vue
        
class UserController:
    def show_user(self, user_id):
        user = UserModel().get_user(user_id)
        return render_template('show_user.html', user=user)

#Update

#Modèle: Met à jour les données dans la BDD

class UserModel:
    def update_user(self, user_id, new_data):
        # Code pour mettre à jour les données utilisateur dans la base de données
        
#Contrôleur: Récupère les nouvelles données de formulaire et appelle le modèle pour mettre à jour l'utilisateur
        
class UserController:
    def update_user(self, user_id, request):
        new_data = request.form['username']
        UserModel().update_user(user_id, new_data)
        # Rediriger ou afficher un message de succès
        
#Delete

# Modèle: Supprime les données de la BDD

class UserModel:
    def delete_user(self, user_id):
        # Code pour supprimer l'utilisateur de la base de données

#Contrôleur: Récupère l'identifiant de l'utilisateur et appelle le modèle pour le supprimer 
        
class UserController:
    def delete_user(self, user_id):
        UserModel().delete_user(user_id)
        # Rediriger ou afficher un message de succès






        
        

