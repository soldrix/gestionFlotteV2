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
php artisan migrate:fresh --seed --seeder=DatabaseSeeder
php artisan storage:link
```


Ajouter les valeur dans env pour smtp:
```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
```


Démarer le server:
```
php artisan serve
```
Enfin utiliser le compte suivant :

email : ```admin@gmail.com```
mot de passe : ```1234567890```

Ne pas oublier de créer un autre compte admin par la suite ou changer le mot de passe.
