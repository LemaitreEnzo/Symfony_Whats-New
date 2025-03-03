# What’s New @La Manu

### Contexte
La direction du campus de La Manu a commandé un programme d'affichage en continu destiné aux étudiants. Ce programme sera exécuté sur un Raspberry Pi et affiché sur un écran à l’entrée du campus.

### Fonctionnalités
L'application affichera les informations suivantes :
- **Emploi du temps du jour** pour chaque promotion (B1, B2, B3)
- **Emploi du temps du lendemain** pour chaque promotion (B1, B2, B3)
- **Actualités** du campus, de la ville de Compiègne et autres nouvelles générales (anniversaires, célébrations, IT/Digital/Design)
- **Fun facts**

Les informations défileront en continu avec une interface optimisée pour une meilleure expérience utilisateur.

### Gestion des données
- Le staff doit pouvoir **ajouter, modifier et supprimer** des informations de manière simple afin d’assurer la mise à jour régulière des affichages.
- Les données seront fournies par le campus.

### Technologies utilisées
- **Framework :** Symfony
- **Frontend :** Twig, HTML, CSS, JavaScript
- **Backend :** PHP avec Symfony
- **Base de données :** MySQL

### Installation & Déploiement
1. **Cloner le dépôt** :
   ```sh
   git clone https://github.com/LemaitreEnzo/Symfony_Whats-New.git
   cd Symfony_Whats-New
   ```
2. **Installer les dépendances** :
   ```sh
   composer install
   ```

3. **Lancer l’application** :
   ```sh
   symfony server:start
   ```
4. **Déploiement sur Raspberry Pi** :
   - Configurer l’écran en mode kiosque
   - Déployer l’application sur un serveur local ou utiliser un conteneur Docker
