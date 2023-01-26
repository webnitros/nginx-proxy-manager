## nginx-proxy-manager

Уравление доменами через API для панели [Nginx Proxy Manager](https://nginxproxymanager.com/)

# API

```php
# клиент
use App\Rest;
use App\Token;

$Token = new Token();
$Client = new Rest();
```

```php
use App\Meta\Domain;

# создание домена
$Domain = Domain::create($Client);
$Domain
   ->addDomain('site.ru')
   ->forwardHost('127.0.0.1')
   ->forwardPort(80)
   ->forwardScheme('http')
   //->ssl('') // Покдлючение SSL сертификата (домен должен быть направлен на сервер)
;
$Domain->save();
```

```php
use App\Meta\Domain;

# Получить домена по id
$Domain = Domain::object(1,$Client);

# Удалить домена
$Domain = Domain::object(1,$Client);
$Domain->delete()

# поиск домена
$Domain = Domain::search('site.ru',$Client);

```

# Установка nginx-proxy-manager

Через **docker-compose.yml**

```bash
version: '3'

services:
  app:
    image: 'jc21/nginx-proxy-manager:latest'
    container_name: nginx-proxy-manager-app
    restart: unless-stopped
    ports:
      - '80:80'
      - '43013:81'
      - '443:443'
    environment:
      DB_MYSQL_HOST: "db"
      DB_MYSQL_PORT: 3306
      DB_MYSQL_USER: "npm"
      DB_MYSQL_PASSWORD: "npm"
      DB_MYSQL_NAME: "npm"
      # Uncomment this if IPv6 is not enabled on your host
      #DISABLE_IPV6: 'true'
    volumes:
      - ./data:/data
      - ./letsencrypt:/etc/letsencrypt

  db:
    image: 'jc21/mariadb-aria:latest'
    container_name: nginx-proxy-manager-db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: 'npm'
      MYSQL_DATABASE: 'npm'
      MYSQL_USER: 'npm'
      MYSQL_PASSWORD: 'npm'
    volumes:
      - ./data/mysql:/var/lib/mysql

```

### PHPUnit

Для тестов нужно создать два файла и прописать данные для авторизации

```bash
nano .env

# содержимое
NGINX_API_URL=http://{IP}:{PORT}
NGINX_API_INENTITY=name@site.ru
NGINX_API_SECRET=password
```

```php
nano nano bootstrap.php

require_once dirname(__FILE__, 1) . '/vendor/autoload.php';
\App\Helpers\Env::loadFile(dirname(__FILE__, 1) . '/.env');

```
