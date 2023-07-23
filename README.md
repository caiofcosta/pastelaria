# Execute para instalação laravel com sail
# php 8.2
# mysql 8

#### 1- copie e renomei o arquivo .env.example ##
renomei para .env
para receber email usei mailtrap passe as credenciais da sua conta abaixo
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
caso não for usar nenhuma conta de email, tem que comentar a 54 do arquivo app\Http\PedidoController.php 
esta linha /* Mail::to($pedido->cliente->email)->send(new PedidoCriadoMail($pedido)); */

#### 2- iniciar instala composer #####
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

### 3 - link storage para as imagens###
vendor/bin/sail artisan storage:link

#### 2- subir banco de dados e gerar dados ######
vendor/bin/sail artisan migrate:refresh --seed

### para rodar testes #####
vendor/bin/sail artisan test 


