    -- Requisitos --
- PHP 8.2 ou superior
- MySQL 8 ou superior
- Composer


    -- Orientações para rodar o código --
- Executar o comando "composer install"
- Criar uma cópia do arquivo ".env.example", e renomeá-la para ".env"
- Alterar as credencias do banco de dados
- Gerar uma key utilizando o comando 
    php artisan key:generate
- Criar o arquivo de rotas para a API utilizando o comando
    php artisan install:api
- Executar as migrations com o comando
    php artisan migrate
- Executar as seeders se quiser povoar o BD utilizando
    php artisan db:seed
- Rodar o servidor utilizando o comando
    php artisan serve