title=pubmaster

echo $title backup started
ROOT=$1/$title
mkdir $ROOT

# Backup web files
WEBPATH=$ROOT/web
mkdir $WEBPATH
#rsync -a --info=progress2 . $WEBPATH --exclude .git --exclude "files/temp"
tar cf - --exclude ".git" --exclude "files/temp" . | pv -s $(du -sb . | awk '{print $1}') | gzip > $WEBPATH/$title.tar.gz

# Backup db
DBPATH=$ROOT/db
mkdir $DBPATH
mysqldump -u$2 -p$3 --databases pubdb > $DBPATH/dump.sql 2> /dev/null

echo $title backup finished
