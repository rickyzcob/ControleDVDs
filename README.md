
# API RESTful em Laravel para o gerenciamento de uma loja de aluguéis de DVDs

Sistema para cadastro de DVDs e clientes, gerenciar o estoque de DVDs e alterar os preços dos aluguéis de forma automática.


## Para rodar o projeto

Clone o projeto

```bash
  git clone https://github.com/rickyzcob/ControleDVDs.git
```

Entre no diretório do projeto

```bash
  cd my-project
```

Roda a migration

```bash
  php artisan migrate
```

Execute os comandos

```bash
  php artisan queue:work
  php artisan schedule:run
```

Inicie o servidor

```bash
  php artisan serve
```


## Documentação da API

#### Retorna todos os clientes

```http
  GET /clientes
```

| Parâmetro   | Tipo       |JSON                           |
| :---------- | :--------- | :---------------------------------- |
|  | `string` | { "title":"Harry Potter e a pedra filosofal", "gender":"Fantasia", "availability":"yes", "price":"20.00", "quantity":"6" } |

#### Retorna um cliente 

```http
  GET /clientes/id
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` |  |


#### Atualização do cliente

```http
  PUT /clientes/id
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` |  |

#### Deletar cliente

```http
  DELETE /clientes/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id`  | `string` | |


## Documentação

[Documentação](https://link-da-documentação)

