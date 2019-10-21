#!/bin/bash

SUCMD='sudo -u www-data '

WEB_ROOT=$PWD
REL_DIR=$WEB_ROOT'/releases'
REL_DATE=$(date +%Y%m%d%H%M)
NEW_REL_DIR=$REL_DIR/$REL_DATE
BRANCH='master'
REPOSITORY_URL='git@git.geainternacional.com:GEA/notifyit.git'
NPM_RUN='prod'
OUTPUT='actualizando'

echo 'verificando si ya existe un proceso de actualización corriendo'
[ -f $WEB_ROOT/$OUTPUT ] && exit 0
touch $OUTPUT
echo $REL_DATE > $OUTPUT
echo 'Inicio la actualizacion'

echo 'Actualizando el servidor'
echo 'Verificando estructura de directorios...'
[ -d $WEB_ROOT/le/.well-known ] || $SUCMD mkdir -p $WEB_ROOT/le/.well-known
[ -d $WEB_ROOT/storage/app/public ] || $SUCMD mkdir -p $WEB_ROOT/storage/app/public
[ -d $WEB_ROOT/storage/framework/cache ] || $SUCMD mkdir -p $WEB_ROOT/storage/framework/cache
[ -d $WEB_ROOT/storage/framework/sessions ] || $SUCMD mkdir -p $WEB_ROOT/storage/framework/sessions
[ -d $WEB_ROOT/storage/framework/testing ] || $SUCMD mkdir -p $WEB_ROOT/storage/framework/testing
[ -d $WEB_ROOT/storage/framework/views ] || $SUCMD mkdir -p $WEB_ROOT/storage/framework/views
[ -d $WEB_ROOT/storage/logs ] || $SUCMD mkdir -p $WEB_ROOT/storage/logs

echo 'Directorios listos'
echo 'Clonando repositorio...'
[ -d $REL_DIR ] || $SUCMD mkdir $REL_DIR
$SUCMD git clone -b $BRANCH --depth 1 $REPOSITORY_URL $NEW_REL_DIR
echo 'clonación lista'

echo 'Copiando el archivo .env'
[ -f $WEB_ROOT/.env ] || $SUCMD cp $NEW_REL_DIR/.env.example $WEB_ROOT/.env
$SUCMD ln -nfs $WEB_ROOT/.env $NEW_REL_DIR/.env

echo 'Instalando las dependencias...'
cd $NEW_REL_DIR && $SUCMD composer install --no-dev --prefer-dist --no-scripts -q -o
cd $NEW_REL_DIR && $SUCMD npm install
if [[ $? -ne 0 ]] ; then
    exit 0
fi
echo 'Dependencias listas'

echo 'Compilando archivos JS...'
cd $NEW_REL_DIR
$SUCMD npm run $NPM_RUN
if [[ $? -ne 0 ]] ; then
    exit 0
fi

cd $NEW_REL_DIR && $SUCMD rm -rf node_modules

echo 'Migrando la base de datos...'
#cd $NEW_REL_DIR && php artisan migrate --force
cd $NEW_REL_DIR
$SUCMD composer dump-autoload
$SUCMD php artisan migrate --force

echo 'Enlazando el directorio storage'
$SUCMD rm -rf $NEW_REL_DIR/storage
$SUCMD ln -nfs $WEB_ROOT/storage $NEW_REL_DIR/storage

echo 'Enlazando el directorio actual ('$WEB_ROOT'/current)'
$SUCMD ln -nfs $NEW_REL_DIR $WEB_ROOT/current
cd $NEW_REL_DIR && $SUCMD php artisan storage:link

echo 'Conservo el release anterior'
cd $REL_DIR && $SUCMD ls -t1 $REL_DIR | tail -n +3 | xargs rm -rf

cd $NEW_REL_DIR && $SUCMD php artisan config:cache
cd $NEW_REL_DIR && $SUCMD php artisan route:cache
cd $NEW_REL_DIR && $SUCMD php artisan horizon:purge
cd $NEW_REL_DIR && $SUCMD php artisan horizon:terminate

echo 'libero el proceso'
rm $WEB_ROOT/$OUTPUT

unset WEB_ROOT
unset REL_DIR
unset REL_DATE
unset NEW_REL_DIR
unset BRANCH

echo 'actualizacion terminada con éxito'
