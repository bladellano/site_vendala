## Desafio Vendala frontend - site_vendala
Criação de produtos KIT via API:
Este teste visa conhecer um pouco mais da forma em que você programa e como você vai se organizar para montar um produto do tipo KIT
###Resumo do teste
Você precisa criar uma API em Laravel que vai receber informações de um front-end desacoplado e cadastrar um produto no banco de dados.
#### Dependências
* PHP 7.3.11
* Apache Server 2.4 +
* 8.0.18 MySQL Community Serve - GPL
#### Instalação
A instalação do sistema pode ser feita seguindo os seguintes passos:
> ATENÇAO: Os passos para instalação descritos nesta documentação, assumem que a aplicação rodará em uma estação Windows e que contenha todas as dependências instaladas e configuradas.

* Clonar ou Baixar o projeto diretamente no `localhost` do usuário
```bash
C:\xampp\htdocs
```
* Dentro da aplição a partir de um terminal `C:\xampp\htdocs\site_vendala`
```bash
bower install
```
* Dentro da aplição a partir de um terminal `C:\xampp\htdocs\site_vendala`
```bash
composer update
```

* Configurar o arquivo hosts `C:\Windows\System32\drivers\etc\hosts`. Adicionar mais uma linha
```bash
127.0.0.1       vendala.local.com
```
* Configurar o arquivo dentro da sua estação `C:\xampp\apache\conf\extra\httpd-vhosts.conf`. Adicionar conteúdo no final do arquivo, salvar. Em seguida reiniciar o serviço apache.
```bash
#### vendala.local.com VirtualHost ####			

<VirtualHost 127.0.0.1:80>
<Directory "C:/xampp/htdocs/site_vendala">
Options FollowSymLinks Indexes
AllowOverride All
Order deny,allow
allow from All
</Directory>
ServerName vendala.local.com
ServerAlias vendala.local.com
ScriptAlias /cgi-bin/ "C:/xampp/htdocs/site_vendala/cgi-bin/"
DocumentRoot "C:/xampp/htdocs/site_vendala"
</VirtualHost>
```

* A partir de um browser `http://vendala.local.com/` usuario: admin senha: admin
### Considerações
Repositório para consumir api_vendala.
### Creditos
Esta aplicação foi desenvolvida por [Caio Dellano Nunes da Silva](mailto:bladellano@gmail.com).