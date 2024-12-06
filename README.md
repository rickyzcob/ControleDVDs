<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## API RESTful em Laravel para o gerenciamento de uma loja de aluguéis de DVDs

O sistema para cadastro de DVDs e clientes, gerenciar o estoque de DVDs e alterar os preços dos aluguéis de forma automática.

## Instruções para instalação

Nessessário rodar os comandos abaixo para utilização do projeto.
1. Alterar o arquivo .env.example para .env
2. Colocar as informações do banco de dados.
3. php artisan migrate
4. php artisan queue:work
5. php artisan schedule:run

Caso queria rodar o projeto localmente execute o comando
php artisan serve
   

## Rotas API para gerenciamento.

Rotas para gerenciamento dos clientes.

Rotas para gerenciamento dos produtos e estoque.

Rotas para adicionar os pedidos.
   



