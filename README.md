lancer les commandes suivantes:

npm i
composer i
npm run dev
php artisan key:generate
modifier le fichier .env.exemple en .env
creer dans la base de donner un table du nom auth_app_db
puis modifier
DB_PORT=8889 port du server sql
DB_DATABASE=app_auth_db nom de la base de donner
DB_USERNAME=root utilisateur de base
DB_PASSWORD=root mot de passe de base

puis lancer la commande suivante
php artisan migrate
php artisan storage:link
php artisan serve

Créer les roles avec les commandes suivante :

php artisan permission:create-role user
php artisan permission:create-role admin
php artisan permission:create-role fournisseur


Enfin creer un compte 
