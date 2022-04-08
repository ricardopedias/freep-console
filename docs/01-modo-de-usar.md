# Modo de Usar

[Voltar ao índice](indice.md)

## 1. Implementar comandos

A primeira coisa a fazer é criar os comandos necessários e alocá-los em algum diretório. Um comando deve ser implementado com base na classe abstrata `Freep\Console\Comando`, conforme o exemplo abaixo:.

```php
class DizerOla extends Comando
{
    /**
     * Pelo menos o método "setarNome" deverá ser invocado para determinar a palavra 
     */
    protected function inicializar(): void
    {
        $this->setarNome("dizer-ola");
        $this->setarDescricao("Exibe a mensagem 'olá' no terminal");
        $this->setarModoDeUsar("./superapp dizer-ola [opcoes]");

        // Uma opção obrigatória e valorada.
        // Quando especificada no terminal, deverá vir acompanhada de um valor
        $this->adicionarOpcao(
            new Opcao(
                '-l',
                '--ler-arquivo',
                'Lê a mensagem a partir de um arquivo texto',
                Opcao::OBRIGATORIA | Opcao::COM_VALOR
            )
        );

        // Uma opção não-obrigatória
        $this->adicionarOpcao(
            new Opcao(
                '-d',
                '--destruir',
                'Apaga o arquivo texto após usá-lo',
                Opcao::OPCIONAL
            )
        );
    }

    /**
     * É neste método que a rotina do comando deverá ser implementada.
     */ 
    protected function manipular(Argumentos $argumentos): void
    {
        $mensagem = "Olá";

        if ($argumentos->opcao('-l') !== '1') {
            $this->linha("Lendo o arquivo texto contendo a mensagem de olá");
            // ... rotina para ler o arquivo texto
            $mensagem = "";
        }

        if ($mensagem === "") {
            $this->erro("Não foi possível ler o arquivo texto");
        }

        if ($argumentos->opcao('-d') === '1') {
            $this->alerta("Apagando o arquivo texto usado");
            // ... rotina para apagar o arquivo texto
        }

        $this->info($mensagem);
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
$terminal->setarModoDeUsar("./superapp comando [opcoes] [argumentos]");

// Adiciona dois diretórios contendo comandos
$terminal->carregarComandosDe(__DIR__ . "/comandos");
$terminal->carregarComandosDe(__DIR__ . "/mais-comandos");

// Executa o comando a partir de uma lista de argumentos
$terminal->executar([ "dizer-ola", "-l", "mensagem.txt", "-d" ]);

```

Mais informações em [Instanciando o Terminal](03-instanciando-o-terminal.md).

- [Script de terminal](02-script-de-terminal.md)
- [Voltar ao índice](indice.md)