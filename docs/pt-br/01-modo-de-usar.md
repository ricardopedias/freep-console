# Modo de Usar

[◂ Voltar ao índice](indice.md) | [Script de Terminal ▸](02-script-de-terminal.md)
-- | --

## 1. Implementar comandos

A primeira coisa a fazer é criar os comandos necessários e alocá-los em algum diretório. Um comando deve ser implementado com base na classe abstrata `Freep\Console\Command`, conforme o exemplo abaixo:.

```php
class DizerOla extends Command
{
    /**
     * Pelo menos o método "setName" deverá ser invocado para determinar a palavra 
     */
    protected function initialize(): void
    {
        $this->setName("dizer-ola");
        $this->setDescription("Exibe a mensagem 'olá' no terminal");
        $this->setHowToUse("./example dizer-ola [opcoes]");

        // Uma opção obrigatória e valorada.
        // Quando especificada no terminal, deverá vir acompanhada de um valor
        $this->addOption(
            new Option(
                '-l',
                '--ler-arquivo',
                'Lê a mensagem a partir de um arquivo texto',
                Option::REQUIRED | Option::VALUED
            )
        );

        // Uma opção não-obrigatória
        $this->addOption(
            new Option(
                '-d',
                '--destruir',
                'Apaga o arquivo texto após usá-lo',
                Option::OPTIONAL
            )
        );
    }

    /**
     * É neste método que a rotina do comando deverá ser implementada.
     */ 
    protected function handle(Arguments $arguments): void
    {
        $message = "Olá";

        if ($arguments->getOption('-l') !== '1') {
            $this->line("Lendo o arquivo texto contendo a mensagem de olá");
            // ... rotina para ler o arquivo texto
            $message = "";
        }

        if ($message === "") {
            $this->error("Não foi possível ler o arquivo texto");
        }

        if ($arguments->getOption('-d') === '1') {
            $this->warning("Apagando o arquivo texto usado");
            // ... rotina para apagar o arquivo texto
        }

        $this->info($message);
    }
}
```

Mais informações em [Criando Comandos](04-criando-comandos.md).

## 2. Criando o terminal

Com os comandos implementados no diretório desejado, é preciso criar uma instância de `Freep\Console\Terminal` e dizer para ela quais são os diretórios contendo os comandos implementados.

Por fim, basta mandar o Terminal executar os comandos através do método `Terminal->executar()`:

```php
// Cria uma instância do Terminal. 
$terminal = new Terminal("raiz/da/super/aplicacao");

// Uma dica sobre como o terminal pode ser utilizado
$terminal->setHowToUse("./superapp comando [opcoes] [argumentos]");

// Adiciona dois diretórios contendo comandos
$terminal->loadCommandsFrom(__DIR__ . "/comandos");
$terminal->loadCommandsFrom(__DIR__ . "/mais-comandos");

// Executa o comando a partir de uma lista de argumentos
$terminal->run([ "dizer-ola", "-l", "mensagem.txt", "-d" ]);

```

Mais informações em [Instanciando o Terminal](03-instanciando-o-terminal.md).

[◂ Voltar ao índice](indice.md) | [Script de Terminal ▸](02-script-de-terminal.md)
-- | --
