Instalação do SGDOC no servidor
===============================================

Instrodução
-----------

É recomendado que este guia seja executado por um usu&aacute;rio com experi&ecirc;ncia em instala&ccedil;ão em servidor Linux e configura&ccedil;ão b&aacute;sica de Apache, PHP e PostgreSQL.  Caso voc&ecirc; n&atilde;o seja um profissional de infra instrutura de ti e queira fazer a instala&ccedil;ão do servidor mesmo assim siga os manuais:

A equipe de TI respons&aacute;vel em manter a wiki oficial da comunidade é a mesma que participa de todo o ciclo de vida do processo de desenvolvendo. (muita coisa ha fazer com pouco recursos ) .  O sistema  operacional adotado pela comunidade durante todo o ciclo de desenvolvimento incluindo a&nbsp; produ&ccedil;ão est&aacute; baseada na distribui&ccedil;ão Linux Centos. Cabe lembrar que o sgdoc roda&nbsp; em outras distribui&ccedil;ões Linux. Caso queira contribuir conosco com estes manuais inclusive este, acesse a  comunidade no github: https://github.com/sgdoc/sgdoc-codigo/wiki/

Pré requisitos
--------------

-   [CentOs](https://github.com/sgdoc/sgdoc-infra/wiki/CentOs-6.4)
-   [Postgresql](https://github.com/sgdoc/sgdoc-infra/wiki/Postgresql)
-   [Zend-server-ce](https://github.com/sgdoc/sgdoc-infra/wiki/Zend-server-ce)
-   [ImageMagick](https://github.com/sgdoc/sgdoc-infra/wiki/ImageMagick)

Código fonte
------------

### Verifique a ultima versão disponível:

https://github.com/sgdoc/sgdoc-codigo/releases/latest

### Download da versão disponível 

    wget https://github.com/sgdoc/sgdoc-codigo/archive/v4.2.29.zip

### Descompacte o arquivo

    unzip v4.2.29.zip

### Publicação do sgdoc

    mv sgdoc-codigo-4.2.29 /var/www/html/sgdoc


Iniciando o banco de dados posrgresql (caso não esteja rodando)
-------------------------------------------

### Alterando para o usuário postgresql

    su postgres

### Entrando na pasta do programa postgres

    cd /usr/local/pgsql/bin/

### Iniciando base de dados postgres.

    ./pg_ctl start -D ../data/ &
    exit;
    .
    
Criar Bando de dados e usuário de banco
----------------------------------------
#### Alterando para usuário root:

    sudo -s
    
#### Alterando para usuário postgres: 

    su postgres
ALTER ROLE postgres ENCRYPTED PASSWORD 'md53175bce1d3201d16594cebf9d7eb3f9d'
#### Entrar no terminal de comandos do banco: 

    psql

#### Digitar os comandos de criação do usuário e do banco

    CREATE ROLE usr_sgdoc4 LOGIN ENCRYPTED PASSWORD 'usr_sgdoc4' SUPERUSER VALID UNTIL 'infinity';
    CREATE DATABASE db_sgdoc4 WITH ENCODING='UTF8' OWNER=usr_sgdoc4;

#### comando para sair do terminal

    \q
    exit

Base da dados em PostgreSQL
---------------------------

### Entrar no diretório da aplicação do sgdoc

    cd /var/www/html/sgdoc/instalacao/database/

### Criar estrutura da base de dados rodando o seguinte comando

    gunzip -c Sgdoc_New_20140410.dump.gz | psql -U postgres -d db_sgdoc4
    
### Popular tabelas com dados

    psql -U postgres -d db_sgdoc4 < initial.sql
    
Configuração do virtual host do sgdoc
------------------------------------

### Cria arquivo de virtual host

    vi  /etc/httpd/conf.d/vhost.conf

### Adicione o conteúdo

    <VirtualHost *:80>
       ServerAdmin webmaster@icmbio.gov.br
       DocumentRoot /var/www/html/sgdoc
       ServerName localhost.sgdoc
       ErrorLog logs/localhost.sgdoc-error_log
       CustomLog logs/localhost.sgdoc-access_log common
       
       SetEnv APPLICATION_ENV dsv
       #SetEnv APPLICATION_ENV prd
       #SetEnv APPLICATION_ENV hmg
       
        <Directory "/var/www/html/sgdoc">
            php_value session.gc_maxlifetime 18050
            php_value session.gc_probability 1
            php_value session.gc_divisor 500
            php_value session.save_path "/var/www/html/sgdoc/cache/sessions"
            Options FollowSymLinks
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
        
    </VirtualHost>
  

Configurando aplicação
----------------------

### Edite arquivo de configuração

    vi /var/www/html/sgdoc/cfg/configuration.ini

### Edite as configurações, principalmente as abaixo:


    [dsv:prd]

    database.default.host       =   'localhost'
    database.default.database   =   'db_sgdoc4'
    database.default.user       =   'usr_sgdoc4'
    database.default.password   =   'usr_sgdoc4'
    

    config.url                  =   'https://local.sgdoc'
    config.basepath             = '/var/www/html/sgdoc' 
    config.appfiles             = '/var/www/html/sgdoc/documento_virtual'
    config.safemode             = 'true' ;; Ocultar erros e continuar a execucao se possivel, Disponivel somente no dsv


    ...


Alterando permissão
-------------------

### permissão de leitura para o apache

    chown -R -v apache:apache /var/www/html/sgdoc/

### Permissão de escrita para os diretórios

    chmod -R -v 775 /var/www/html/sgdoc/documento_virtual
    chmod -R -v 775 /var/www/html/sgdoc/documento_virtual/TMP
    chmod -R -v 775 /var/www/html/sgdoc/cache 

Reinicie o servidor web
-----------------------

    service httpd restart

Acessando o endereço do SGDOC através do navegador (A versão do sgdoc da comunidade está homologada para firefox)

    http://local.sgdoc

***


OBS: O script initial.sql efetua a carga dos usuários e unidades padrões abaixo:

* Unidades:

    1. Protocolo
    1. Arquivo
    1. Setor de Gerência da Informações
    1. Setor Comum

***
- Usuários:

    * Protocolo (usuário: protocolo senha:protocolo)
    * Administrador (usuário: admin senha: admin)
    * Arquivo (usuário: arquivo senha: arquivo)
    * Comum (usuário: comum senha: comum)

***

### BPM - Processo do SGDoc

http://sgdoc.github.io/sgdoc-processo/html/index.html#list

Link da Licença Júridica Creative Commons\
http://creativecommons.org/licenses/by-sa/2.5/br/legalcode
