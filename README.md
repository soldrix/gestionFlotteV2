lancer les commandes suivantes:
```
npm i
composer i
npm run dev
php artisan key:generate
```
modifier le fichier .env.exemple en .env <br> 
command mac:
```
cp .env.example .env 
```
command windows:
```text
copy .env.example .env
```

puis modifier :
```
DB_PORT=8889 port du server sql
DB_DATABASE=app_auth_db nom de la base de donner
DB_USERNAME=root utilisateur de base
DB_PASSWORD=root mot de passe de base
```
puis lancer la commande suivante
```
php artisan migrate
php artisan storage:link
php artisan serve
```
Créer les roles avec les commandes suivante :
```
php artisan permission:create-role user
php artisan permission:create-role admin
php artisan permission:create-role fournisseur
```

Ajouter les valeur dans env pour smtp:
```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
```


Enfin creer un compte 
