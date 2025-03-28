Système de Billetterie Événementielle - Architecture Microservices

Un système évolutif de billetterie événementielle construit avec une architecture microservices Laravel, conçu pour gérer des événements de petite et grande envergure, comme des concerts internationaux.

Aperçu de l'Architecture
Le système se compose des microservices suivants :

API Gateway (Port 8000)

Point d'entrée pour les requêtes des clients

Gestion du routage et de l'équilibrage de charge

Découverte des services

Service Auth (Port 8001)

Authentification et autorisation

Gestion des tokens JWT

Rôles utilisateur : Admin, Créateur d'événements, Opérateur, Utilisateur

Service Utilisateur (Port 8002)

Gestion des profils utilisateurs

Historique des achats

Paramètres du compte

Service Événement (Port 8003)

Création, modification et suppression d'événements

Gestion de la capacité des événements

Détails et planification des événements

Service Billet (Port 8004)

Achat et gestion des billets

Traitement des paiements

Validation des billets

Service Notification (Port 8005)

Notifications par email/SMS

Confirmation d'achat

Mises à jour d'événements

Frontend (Port 3000)

Application web React.js

Interface utilisateur pour toutes les opérations

Load Balancer (Port 80)

Équilibrage de charge Nginx

Distribution des requêtes

Haute disponibilité

Choix des Technologies
React.js (Frontend)

Performant grâce au DOM virtuel

Modulaire avec des composants réutilisables

Interface dynamique et interactive

Écosystème riche (Redux, React Router)

Laravel (Backend)

Optimisation des performances avec ORM Eloquent

Sécurisé contre les injections SQL et CSRF

Architecture MVC pour une maintenance aisée

API prête pour intégrer le frontend React.js

Diagramme d'Architecture
mermaid
Copier
Modifier
graph TD
    LB[Load Balancer<br/>Nginx] --> AG[API Gateway]
    subgraph Services
        AG --> AS[Auth Service]
        AG --> US[User Service]
        AG --> ES[Event Service]
        AG --> TS[Ticket Service]
        AG --> NS[Notification Service]
        AG --> FE[Frontend<br/>React.js]
    end
    subgraph Databases
        AS --> ADB[(Auth DB)]
        US --> UDB[(User DB)]
        ES --> EDB[(Event DB)]
        TS --> TDB[(Ticket DB)]
        NS --> NDB[(Notification DB)]
    end
    classDef gateway fill:#f96,stroke:#333,stroke-width:2px
    classDef service fill:#58a,stroke:#333,stroke-width:2px
    classDef database fill:#eb4,stroke:#333,stroke-width:2px
    classDef loadbalancer fill:#7f7,stroke:#333,stroke-width:2px
    class LB loadbalancer
    class AG gateway
    class AS,US,ES,TS,NS,FE service
    class ADB,UDB,EDB,TDB,NDB database
Fonctionnalités
Authentification et autorisation utilisateur

Création et gestion des événements

Achat sécurisé de billets

Notifications automatiques par email

Traitement des paiements

Validation des billets

Historique d'achats des utilisateurs

Tableau de bord Admin

Prérequis
Docker

Docker Compose

Git

Installation
Clonez le dépôt :

bash
Copier
Modifier
git clone https://github.com/chabbasaad/Events_Microservices &&
cd laravel_Microservices
Démarrez les containers :

bash
Copier
Modifier
docker-compose up -d
Exécutez la commande pour les notifications :

bash
Copier
Modifier
php artisan queue:work
Comptes Démo
Admin

Email: admin@example.com

Mot de passe: password

Rôle: Admin

Créateur d'événements

Email: eventcreator@example.com

Mot de passe: password

Rôle: Créateur d'événements

Opérateur

Email: operator@example.com

Mot de passe: password

Rôle: Opérateur

Utilisateur

Email: user@example.com

Mot de passe: password

Rôle: Utilisateur

URLs des Services
Frontend: http://localhost:3000

API Gateway: http://localhost:8000

Auth Service: http://localhost:8001

User Service: http://localhost:8002

Event Service: http://localhost:8003

Ticket Service: http://localhost:8004

Notification Service: http://localhost:8005

Documentation API
La documentation est disponible aux URLs suivantes :

Auth Service: http://localhost:8001/docs/api

User Service: http://localhost:8002/docs/api

Event Service: http://localhost:8003/docs/api

Ticket Service: http://localhost:8004/docs/api

Sécurité
Authentification JWT

Chiffrement des mots de passe

Protection CORS

Sauvegarde des bases de données

Traitement sécurisé des paiements avec Firebase

Gestion des Erreurs
Journalisation des erreurs

Système de notifications asynchrones pour les opérations échouées

Rétablissement des transactions en cas d'échec

Mécanisme de reprise automatique pour les notifications échouées

Logs
Chaque service conserve ses logs dans storage/logs/laravel.log

Configuration du Load Balancer (NGINX)
Redirection des requêtes /api/ vers l'API Gateway

Accès direct pour les tests aux services via les routes /auth/, /users/, /events/, /tickets/, /notifications/
