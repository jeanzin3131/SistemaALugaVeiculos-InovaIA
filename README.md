# Sistema de Locação de Veículos - DirigeAí

DirigeAí é um sistema de locação de veículos focado em locatários e locadores. Com uma interface simples e intuitiva, o sistema permite que locatários busquem e reservem veículos aprovados por locadores, além de gerenciar suas reservas de forma eficiente.

## Funcionalidades

- **Cadastro de Locatário e Locador**: Sistema de login para ambos os tipos de usuários.
- **Reserva de Veículos**: Locatários podem visualizar e reservar veículos disponíveis.
- **Gestão de Reservas**: Locatários podem ver o status de suas reservas e fazer pagamentos.
- **Pagamento via Mercado Pago**: Integração para pagamento de reservas.
- **Busca de Veículos**: Locatários podem filtrar veículos disponíveis por modelo, marca, ano e valor de diária.

## Tecnologias Utilizadas

- **Backend**: PHP
- **Banco de Dados**: MySQL
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Integrações**: Mercado Pago para pagamentos

## Como Utilizar

1. Clone o repositório para sua máquina local:

    ```bash
    git clone https://github.com/jeanzin3131/dirigeai.git
    ```

2. Acesse o diretório do projeto:

    ```bash
    cd dirigeai
    ```

3. Crie seu banco de dados MySQL e importe as tabelas necessárias.
4. Copie o arquivo `.env.example` para `.env` e preencha com suas credenciais de banco de dados e token do Mercado Pago.
5. A aplicação lerá essas variáveis automaticamente ao iniciar.

## Instalação do Mercado Pago

Para a integração com Mercado Pago, siga os passos abaixo:

1. Baixe o SDK do Mercado Pago utilizando Composer:

    ```bash
    composer require mercadopago/dx-php
    ```

2. Configure as credenciais da sua conta Mercado Pago no arquivo `config/mercadopago.php`.

## Vídeo de Demonstração

Assista ao vídeo abaixo para ver como o sistema de locação funciona:

[![Vídeo do Sistema](https://img.youtube.com/vi/dbafjztDPMA/maxresdefault.jpg)](https://youtube.com/shorts/dbafjztDPMA?feature=share)

## Contato

Caso tenha interesse em utilizar o sistema, entre em contato pelo WhatsApp:

[**Clique aqui para entrar em contato**](https://wa.me/5513981628930)

## Licença

Este projeto é de código aberto e distribuído sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
