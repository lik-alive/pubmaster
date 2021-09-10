echo 'pubmaster backup started'
ROOT=$1/pubmaster
mkdir $ROOT

# Backup web files
WEBPATH=$ROOT/web
mkdir $WEBPATH
rsync -a --info=progress2 . $WEBPATH --exclude .git --exclude "files/temp"

# Backup db
DBPATH=$ROOT/db
mkdir $DBPATH
mysqldump -u$2 -p$3 --databases pubdb > $DBPATH/dump.sql 2> /dev/null
echo 'pubmaster backup finished'