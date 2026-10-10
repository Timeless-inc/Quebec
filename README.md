# Quebec

O Quebec é o projeto que dá vida ao Sistema de Requerimento Estudantil (SRE), uma plataforma web criada para organizar, digitalizar e dar mais transparência aos pedidos acadêmicos do IFPE Campus Igarassu.

Na prática, o sistema foi pensado para reduzir a dependência de formulários dispersos, planilhas e trocas manuais de e-mail, reunindo em um só lugar o envio de solicitações, o acompanhamento de status, os encaminhamentos internos e o histórico de movimentações.

## Sobre o projeto

O SRE nasce como uma resposta a um problema cotidiano da comunidade acadêmica: o caminho para abrir, acompanhar e concluir um requerimento ainda era fragmentado e pouco intuitivo. A proposta do sistema é deixar esse processo mais simples para o estudante e mais organizado para os setores responsáveis.

O projeto é desenvolvido com base em levantamento com discentes, reuniões com a CRADT e validação em ambiente controlado com estudantes do campus. O resultado é uma plataforma que busca aproximar a experiência digital da rotina real da instituição, com foco em clareza, agilidade e autonomia.

## O que o sistema oferece

- Para o Estudante: criação de requerimentos, acompanhamento do andamento, acesso ao histórico e recebimento de notificações.
- Para a CRADT: análise das solicitações, complementações, encaminhamentos e gestão do fluxo dos pedidos.
- Para a Coordenadores e Responsáveis: processamento de encaminhamentos, consultas e avaliação de aprovação.
- Para a Instituição: centralização das informações, rastreabilidade dos processos e redução de retrabalho.

## Estrutura do projeto

A estrutura segue o padrão do Laravel e organiza o sistema da seguinte forma:

- `app/`: regras de negócio, modelos, controladores, serviços, eventos, notificações e mailables.
- `bootstrap/`: inicialização da aplicação.
- `config/`: configurações gerais do projeto.
- `database/`: migrações, factories e seeds.
- `public/`: ponto de entrada da aplicação e arquivos públicos.
- `resources/`: views, estilos e scripts da interface.
- `routes/`: definição das rotas do sistema.
- `storage/`: arquivos gerados pela aplicação, logs e uploads.
- `tests/`: testes automatizados.

## Tecnologias e recursos

- Laravel 12
- PHP 8.4+
- Vite e Tailwind CSS para a interface
- Laravel Breeze para autenticação
- Geração de relatórios e documentos em PDF
- Notificações e atualização de status
- Recursos de gráficos e painéis administrativos

## Requisitos

Antes de rodar o projeto, verifique se você tem instalado:

- PHP 8.4 ou superior (requisito do OpenSpout 5 e das dependências atuais)
- Composer
- Node.js 22 ou superior com npm

## Como executar localmente

1. Clone o repositório:

    ```bash
    git clone https://github.com/Timeless-inc/Quebec.git
    cd Quebec
    ```

2. Instale as dependências do PHP:

    ```bash
    composer install
    ```

3. Instale as dependências do frontend:

    ```bash
    npm install
    ```

4. Configure o ambiente:

    Copie o arquivo `.env.example` para `.env` e ajuste as configurações do seu ambiente, principalmente banco de dados, e-mail e demais serviços usados pela aplicação.

5. Gere a chave da aplicação:

    ```bash
    php artisan key:generate
    ```

6. Execute as migrações e os seeds:

    ```bash
    php artisan migrate --seed
    ```

7. Inicie o projeto em modo desenvolvimento:

    ```bash
    composer run dev
    ```

    Esse comando sobe a aplicação, o Vite, os logs e o listener de filas em um único passo.

Se preferir iniciar manualmente, você também pode usar `php artisan serve` e `npm run dev` em terminais separados.

Ao atualizar dependências do frontend, encerre o Vite antes de executar `npm install` ou `npm ci` e inicie `npm run dev` novamente ao terminar. Um processo antigo pode continuar referenciando módulos removidos pela atualização e retornar erro 500 para o CSS, deixando as páginas sem estilos.

8. Instale e inicie o Reverb:

    ```bash
    php artisan reverb:install 
    ```
    e depois rode o comando para iniciar o Reverb:

    ```bash
    php artisan reverb:start
    ```

9. Execute o a fila de jobs do Laravel:

    ```bash
    php artisan queue:work
    ```

Aqui é apenas caso queira testar o envio de emails e notificações, caso contrário, não é necessário. (Faltando setar apenas um Sandbox de email para testes, como Mailtrap ou Mailhog no .env).

## Backup do banco de dados

O comando `php artisan db:backup` gera um dump compactado do banco (`mysqldump`) em `storage/app/private/backups` e o envia para um remoto do [rclone](https://rclone.org/), como o Google Drive. Ele já está agendado para rodar todos os dias às 02:00 pelo scheduler do Laravel.

1. Instale o `mysqldump` (cliente do MySQL) e o `rclone` no servidor.
2. Configure o remoto do Google Drive uma única vez, com o mesmo usuário que executa o Laravel:

    ```bash
    rclone config
    ```

    Crie um remoto do tipo `drive` (por exemplo, com o nome `gdrive`) e autorize a conta do Drive.

3. Informe o destino no `.env`:

    ```env
    BACKUP_RCLONE_REMOTE=gdrive:SRE/backups
    BACKUP_KEEP_DAYS=7
    BACKUP_RCLONE_KEEP_DAYS=30
    ```

4. Teste manualmente e garanta que o scheduler está no cron do servidor:

    ```bash
    php artisan db:backup
    ```

    ```cron
    * * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
    ```

Use `php artisan db:backup --no-upload` para gerar apenas o dump local. Para restaurar: `gunzip < sre-AAAAMMDD-HHMMSS.sql.gz | mysql -u usuario -p banco`.

## Envio de e-mails pela API do Gmail

Além do SMTP, o sistema envia e-mails pela API do Gmail (mailer `gmail`), usando OAuth em vez de senha.

1. No Google Cloud, ative a **Gmail API** e crie uma credencial OAuth do tipo "App para computador". Baixe o JSON e guarde-o fora do Git (a pasta `secret/` já é ignorada).
2. Informe os caminhos no `.env`:

    ```env
    GMAIL_CREDENTIALS_PATH=secret/client_secret.json
    GMAIL_TOKEN_PATH=secret/gmail-token.json
    MAIL_FROM_ADDRESS="conta-que-envia@gmail.com"
    ```

3. Autorize a conta que vai enviar os e-mails (uma única vez por servidor):

    ```bash
    php artisan gmail:authorize --test=seu-email@exemplo.com
    ```

    O comando mostra um endereço para abrir no navegador. Depois de permitir, copie o endereço para onde o navegador foi redirecionado (`http://localhost/?code=...`) e cole no terminal. O refresh token fica salvo em `GMAIL_TOKEN_PATH`.

4. Ative o mailer: `MAIL_MAILER=gmail`.

Enquanto o app do Google Cloud estiver em modo de teste, só as contas cadastradas como usuários de teste conseguem autorizar, e o refresh token expira em 7 dias. Publique o app (ou use uma conta do Google Workspace da instituição) para o acesso não expirar. O `MAIL_FROM_ADDRESS` precisa ser a conta autorizada ou um alias dela.

## Contribuição

Contribuições são bem-vindas. Se você quiser colaborar, siga este fluxo:

1. Faça um fork do repositório.
2. Crie uma branch para sua alteração.
3. Implemente as mudanças e registre o commit.
4. Envie a branch para o repositório remoto.
5. Abra um pull request.

## Licença

Este projeto está licenciado sob a AGPL-3.0. Consulte o arquivo [LICENSE](LICENSE) para os detalhes completos.

## Equipe

Projeto desenvolvido por:

- Anderson Gabriel
- Aristoteles Lins
- Brenno Victor
- João Pedro
- Joyce Kelle
- Kauê Luí
- Luís Eduardo

Se desejar, você também pode acompanhar o repositório da equipe em [Timeless-inc](https://github.com/Timeless-inc).
