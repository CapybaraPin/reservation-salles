#!/bin/bash

# Charger les variables d'environnement depuis le fichier .env
if [ -f .env ]; then
  set -a
  . .env  # Utilisation du point pour source
  set +a
else
  echo "Erreur : Le fichier .env est manquant."
  exit 1
fi

# Nom du fichier de sauvegarde
BACKUP_FILENAME="backup-$(date +'%d-%m-%Y').sql"

# Tester la connexion MySQL
echo "Test de connexion à MySQL..."
mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD $DB_NAME -e "SHOW TABLES;" > /dev/null 2>&1
if [ $? -ne 0 ]; then
  echo "Erreur : Impossible de se connecter à la base de données MySQL."
  exit 1
fi

# Commande de sauvegarde de la base de données
echo "Sauvegarde de la base de données..."
mysqldump -h$DB_HOST -u$DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_FILENAME

# Connexion au serveur FTP et transfert du fichier
ftp -n $FTP_SERVER <<END_SCRIPT
quote USER $FTP_USERNAME
quote PASS $FTP_PASSWORD
passive  # Passer en mode passif pour éviter les erreurs de connexion de données
cd $FTP_UPLOAD_DIR
put $BACKUP_FILENAME
quit
END_SCRIPT

# Suppression du fichier de sauvegarde local
rm $BACKUP_FILENAME

echo "Sauvegarde et envoi sur FTP terminés avec succès."
