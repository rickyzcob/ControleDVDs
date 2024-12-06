
# API RESTfull em Laravel para o gerenciamento produtos e pedidos.

Sistema para cadastro de produtos e clientes com gerenciamento do estoque dos Produtos, pedidos e alteração dos preços de forma automatica.


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

#### Primeiro Cadastar os cliente e produtos para utilização dos pedidos.

## Documentação da API Clientes

#### Retorna todos os clientes

```http
  GET /clientes
```


#### Retorna um cliente 

```http
  GET /clientes/id
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | `Lista todos os cadastros de clientes` |


#### Cadastrar Cliente

```http
  POST /clientes
```

| Parâmetro   | Tipo       |Descrição.                           |
| :---------- | :--------- | :---------------------------------- |
|  | `json` | `Exemplo abaixo do json para cadastro`|

```http
{
    "name":"Ricardo Oliveira Lima", 
    "email":"rickyzbr@gmail.com", 
    "phone":"11990037413"
}
```

#### Atualização do cliente

```http
  PUT /clientes/id
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `json` | `Exemplo abaixo do json para atualização` |

```http
{
    "name":"Ricardo Oliveira Lima", 
    "email":"rickyzbr@gmail.com", 
    "phone":"11990037413"
}
```


#### Deletar cliente

```http
  DELETE /clientes/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id`  | `string` | |


## Documentação da API Produtos
#### Retorna todos os produtos

```http
  GET /produtos
```

#### Retorna um produto 

```http
  GET /produtos/id
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | `Retorna o produto específico solicitado` |

#### Cadastrar um produto

```http
  POST /produtos
```

| Parâmetro   | Tipo       |Descrição.                           |
| :---------- | :--------- | :---------------------------------- |
|  | `json` | `Exemplo abaixo do json para cadastro` |

```http
{
    "title":"Harry Potter e a pedra filosofal", 
    "gender":"Fantasia", 
    "availability":"yes",
    "price":"20.00",
    "quantity":"6"
}
```

#### Atualização do produto

```http
  PUT /produtos/id
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | `Exemplo abaixo do json para atualização` |

```http
{
    "title":"Harry Potter e a pedra filosofal", 
    "gender":"Fantasia", 
    "availability":"yes",
    "price":"20.00",
    "quantity":"6"
}
```

#### Deletar produto

```http
  DELETE /produtos/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id`  | `string` | |



## Documentação da API Pedidos
#### Retorna todos os pedidos

```http
  GET /pedidos
```

#### Retorna um pedido 

```http
  GET /pedidos/id
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | `Retona dados do pedido solicitado` |

#### Cadastra pedido 
```http
  POST /pedidos
```

| Parâmetro   | Tipo       |Descrição                           |
| :---------- | :--------- | :---------------------------------- |
|  | `json` |`Exemplo abaixo do json para cadastro` |

```http
  {
    "client_id":"2",
        "products": [{
            "product_id":"3", 
            "quantity":"3"
        },{
            "product_id":"1", 
            "quantity":"3"
        },{
            "product_id":"2", 
            "quantity":"2"
        }
    ]
}
```


#### Atualização do pedido

```http
  PUT /pedidos/id
```

| Parâmetro   | Tipo       | Descrição.                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `string` | `Exemplo abaixo do json para atualização`|

```http
  {
    "client_id":"2",
        "products": [{
            "product_id":"3", 
            "quantity":"3"
        },{
            "product_id":"1", 
            "quantity":"3"
        },{
            "product_id":"2", 
            "quantity":"2"
        }
    ]
}
```

#### Deletar pedido

```http
  DELETE /pedidos/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id`  | `string` | |
