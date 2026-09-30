# merak-camacol

**Versión** 0.1.9  
**Descripción** Camacol  
**Colaboradores:** Alejandra Zerda <aleja533@gmail.com>  
**Requiere al menos:** WordPress 4.4  
**Probado hasta:** WordPress 5.4  
**Licencia:** GPLv3 or later  
**Enlace a la licencia:** http://www.gnu.org/licenses/gpl-3.0.html  
**Etiquetas:** timber, template  

Este tema ha sido creado para ser usado como plantilla base para la creación de un sitio que use Timber.

## Antes de activar

* Al inicio del archivo *style.css* encontrará el espacio para declarar la versión. Cambie el número cada vez que el desarrollo lo requiera.
* Cambie también el _Text Domain_ y el _Prefix_ por unos valores más acordes a la plantilla.
* Busque las definiciones al inicio del archivo *functions.php* y pónga nombres más acordes a la plantilla. Busque recursivamente y cambie por el nuevo prefijo para las variables.
* Cambie el nombre de la clase declarada en ese mismo archivo por uno más acorde a la plantilla. Busque recursivamente y cambie por el nuevo nombre. Con esto se estará aplicando el cambio a todos los espacios de nombres.

## Lando

Si usa lando como entorno de desarrollo deberá incluir _npm_ y _composer_ al interior del servicio.
Si está usando la receta **wordpress** agregue los siguientes elementos en el archivo **.lando.yml**:

```
services:
  appserver:
    build_as_root:
      - curl -sL https://deb.nodesource.com/setup_12.x | bash -
      - apt-get install -y nodejs
tooling:
  npm:
    service: appserver
```

## i18n

Los archivos de traducciones están configurados para extraer información de los archivos php, js y twig.

Para extraer de los datos del Tema es posible que necesite agregar en la configuración del editor PO un extractor específico. Instale [**wp-cli**](https://wp-cli.org/) y cree un extractor que use este comando. Para el caso particular de POEdit usar la siguiente configuración para un nuevo extractor:

- Lenguaje: Wordpress
- Extensiones: \*.pot
- Comando: wp i18n make-pot . %o --exclude="build,assets,vendor" --ignore-domain --skip-audit
- Palabras clave: -k %k
- Archivo de entrada: %f
- Conjunto de caracteres: --domain=%c

**Nota:** el script wp-i18n ejecuta por sí solo la revisión de todo el directorio del tema y por lo tanto solo será necesario llamarlo una única vez. Por esta razón se usa como referencia la extensión de plantilla de traducción que es una extensión poco usada. **Asegúrese** de hacer que quede disponible en la lista de rutas de fuentes el directorio donde se encuentra el archivo POT del tema.

Para extraer de los archivos Twig (estilo timber) es posible que necesite agregar en la configuración del editor PO un extractor específico para archivos de ese tipo. Instale [**xgettext-timber**](https://bitbucket.org/baxtian/xgettext-timber/) y cree un extractor que use este comando. Para el caso particular de POEdit usar la siguiente configuración para un nuevo extractor:

- Lenguaje: Timber
- Extensiones: \*.twig
- Comando: xgettext-timber --force-po -o %o %C %F
- Palabras clave: -k%k
- Archivo de entrada: %f
- Conjunto de caracteres: --from-code=%c

El script wp-i18n extrae las traducciones de los archivos php, pero en caso de tener un php.ini o un js este no será traducido y por lo tanto se deberá agregar en la configuración del editor PO un extractor específico para archivos de ese tipo. Para el caso particular de POEdit se puede usar replicar el extractor de **Timber** con la siguiente configuración:

- Lenguaje: Php Inclusions y JS
- Extensiones: \*.php.inc;build/gtmbrg/\*.js
- Comando: xgettext-timber --force-po -o %o %C %F
- Palabras clave: -k%k
- Archivo de entrada: %f
- Conjunto de caracteres: --from-code=%c

Para crear los archivos de traducción para bloques intalar *wp-cli* y ejecutar:

```
composer i18n
```

Como resultado habrá un archivo por cada script con sus traducciones requeridas.
