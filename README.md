# Finance Management System

Este é um sistema de gerenciamento financeiro construído com Laravel 11 e PHP 8.

## Requisitos

- PHP >= 8.2
- Composer
- SQLite ou outro banco de dados suportado pelo Laravel

## Instalação

Siga estas instruções para configurar o projeto em seu ambiente local.

### Passo 1: Clonar o Repositório

```bash
git clone https://github.com/lucas-moraes/php8-laravel11-finance.git
cd php8-laravel11-finance
```

### Passo 2: Instalar Dependências
```bash
composer install
```

### Passo 3: Configurar o Arquivo .env
Copie o arquivo de exemplo .env.example para .env e configure as variáveis de ambiente conforme necessário.

```bash
cp .env.example .env
```

Edite o arquivo .env para configurar o banco de dados:
```bash
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/para/seu/banco_de_dados/banco_de_dados.db
```

### Passo 4: Executar Migrações

```bash
php artisan migrate
```
### Passo 5: Iniciar o Servidor de Desenvolvimento

```bash
php artisan serve
```

## Contribuindo
Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

## Licença
Este projeto está licenciado sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

