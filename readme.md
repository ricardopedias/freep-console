# Freep Console

## Sinopse

Este repositório contém as funcionalidades necessárias para implementar um gerenciador de 
comandos para terminal em uma aplicação PHP de forma fácil.

## Modo de Usar

A primeira coisa a fazer é criar os comandos necessários e alocá-los em algum diretório. Um comando deve ser implementado com base na classe abstrata `Freep\Console\Comando`:

```php
class DizerOla extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("dizer-ola");
        $this->setarDescricao("Exibe a mensagem 'olá' no terminal");
        $this->setarModoDeUsar("./superapp dizer-ola [opcoes]");

        $this->adicionarOpcao(
            new Opcao(
                '-l',
                '--ler-arquivo',
                'Lé a mensagem de um arquivo texto',
                Opcao::OPCIONAL | Opcao::COM_VALOR
            )
        );

        $this->adicionarOpcao(
            new Opcao(
                '-d',
                '--destruir',
                'Apaga o arquivo texto usá-lo',
                Opcao::OPCIONAL
            )
        );
    }

    protected function manipular(Argumentos $argumentos): void
    {
        if ($argumentos->opcao('-l') === '1') {
            $this->linha("Apagando o arquivo texto usado");
            // ... rotina para apagar o arquivo
        }

        // ... rotina para leitura do arquivo

        if ('algum erro ocorrer') {
            $this->erro("Não foi possível ler o arquivo json");
        }

        if ($argumentos->opcao('-d') === '1') {
            $this->linha("Apagando o arquivo texto usado");
            // ... rotina para apagar o arquivo
        }

        $this->info("Arquivo json lido com sucesso");
    }
}
```


Com os comandos implementados, é preciso criar uma instancia de `Freep\Console\Termnal` e dizer para ele quais os diretórios que contém comandos.

Por fim, basta mandar o Terminal executar os comandos através do método `executar()`:

```php
$terminal = new Terminal("raiz/da/super/aplicacao");
$terminal->setarModoDeUsar("./superapp comando [opcoes] [argumentos]");
$terminal->carregarComandosDe(__DIR__ . "/comandos");
$terminal->carregarComandosDe(__DIR__ . "/mais-comandos");

$terminal->executar([ "nome-comando", "--file", "config.json" ]);

```

## Desenvolvimento

### Ferramentas

Para o desenvolvimento, foram utilizadas ferramentas para testes de unidade e 
análise estática. Todas configuradas no nível máximo de exigência.

São as seguintes ferramentas:

- [PHP Unit](https://phpunit.de)
- [PHP Stan](https://phpstan.org)
- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP MD](https://phpmd.org)

### Infraestrutura

Se o [Docker](https://www.docker.com/) estiver instalado no computador, não será necessário ter o Composer, e nem mesmo o PHP, 
instalados na máquina do desenvolvedor. Para usar o Composer e as bibliotecas de qualidade de código, 
basta usar o script `./composer`, localizado na raiz deste repositório. 

Trata-se de uma ponte para todos os comandos do Composer, executados através do Docker.
