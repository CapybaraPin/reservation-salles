#!/bin/bash

# Charger les variables d'environnement depuis le fichier .env
if [ -f .env ]; then
  export $(cat .env | grep -v '^#' | xargs)
else
  echo "Erreur : Le fichier .env est manquant."
  exit 1
fi

# Nom du fichier de sauvegarde
BACKUP_FILENAME="backup-$(date +'%d-%m-%Y').sql"

# Commande de sauvegarde de la base de données
mysqldump -h$DB_HOST -u$DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_FILENAME

# Connexion au serveur FTP et transfert du fichier
ftp -n $FTP_SERVER <<END_SCRIPT
quote USER $FTP_USERNAME
quote PASS $FTP_PASSWORD
cd $FTP_UPLOAD_DIR
put $BACKUP_FILENAME
quit
END_SCRIPT

# Suppression du fichier de sauvegarde local
rm $BACKUP_FILENAME

echo "Sauvegarde et envoi sur FTP terminés avec succès."
