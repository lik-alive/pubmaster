echo 'pubmaster backup started'
ROOT=$1/pubmaster
mkdir $ROOT

# Backup web files
WEBPATH=$ROOT/web
mkdir $WEBPATH
rsync -a . $WEBPATH --exclude .git --exclude "files/temp"

# Backup db
DBPATH=$ROOT/db
mkdir $DBPATH
mysqldump -u$2 -p$3 --databases pubdb > $DBPATH/dump.sql
echo 'pubmaster backup finished'