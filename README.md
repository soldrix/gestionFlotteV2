lancer les commandes suivantes:

command mac:
```
cp .env.example .env 
```
command windows:
```
copy .env.example .env
```
commande pour le projet :
```
npm i
composer i
npm run dev
npx mix
php artisan key:generate
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
```
Créer les roles avec les commandes suivante :
```
php artisan permission:create-role user
php artisan permission:create-role admin
php artisan permission:create-role "responsable auto"
php artisan permission:create-role "fournisseur"
php artisan permission:create-role "responsable fournisseur"
php artisan permission:create-role "chef agence"
php artisan permission:create-role "responsable agences"
php artisan permission:create-role secretaire
php artisan permission:create-role RH
```

Ajouter les valeur dans env pour smtp:
```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
```


Enfin démarer le server et ensuite créer un compte:
```
php artisan serve
```
