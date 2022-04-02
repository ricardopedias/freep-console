<?php

declare(strict_types=1);

namespace Freep\Console;

use Freep\Console\Comandos\Ajuda;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class Terminal
{
    private string $caminhoDaAplicacao;

    /** @var array<string> */
    private array $listaDeDiretorios = [];

    private string $comandoExecutado = "nao";

    private string $modoDeUsar = "";

    public function __construct(string $caminhoDaAplicacao)
    {
        $caminhoAnalizado = realpath($caminhoDaAplicacao);

        if ($caminhoAnalizado === false || is_dir($caminhoAnalizado) === false) {
            throw new InvalidArgumentException("O diretório de aplicação especificado não existe");
        }

        $this->caminhoDaAplicacao = $caminhoAnalizado;

        $this->carregarComandosDe(__DIR__ . '/Comandos');
    }

    public function setarModoDeUsar(string $texto): void
    {
        $this->modoDeUsar = $texto;
    }

    public function modoDeUsar(): string
    {
        return $this->modoDeUsar;
    }

    public function caminhoDaAplicacao(): string
    {
        return $this->caminhoDaAplicacao;
    }

    public function carregarComandosDe(string $caminhoComandos): self
    {
        $caminhoReal = realpath($caminhoComandos);

        if ($caminhoReal === false || is_dir($caminhoReal) === false) {
            throw new InvalidArgumentException("O diretórios especificado para comandos não existe");
        }

        $this->listaDeDiretorios[] = $caminhoComandos;
        return $this;
    }

    /**
     * @param array<int,string> $argumentos
     */
    public function executar(array $argumentos): void
    {
        if (isset($argumentos[0]) === false) {
            return;
        }

        if (in_array($argumentos[0], ['--ajuda', '-a']) === true) {
            $argumentos[0] = "ajuda";
        }

        $nomeComando = array_shift($argumentos);

        ini_set("display_errors", "1");

        try {
            $this->executarComando($nomeComando, $argumentos);
        } catch (Throwable $e) {
            echo "\033[0;31m✗  " . $e->getFile() . " on line " . $e->getLine() . "\033[0m\n";
            echo "\033[0;31m   " . $e->getMessage() . "\033[0m\n";
        }
    }

    /** @return array<int,string> */
    public function listaDeComandos(): array
    {
        $todosComandos = [];

        foreach ($this->listaDeDiretorios as $path) {
            $todosComandos = array_merge($todosComandos, $this->listaDeDiretorios($path));
        }

        return $todosComandos;
    }

    /**
     * @param array<int,string> $argumentos
     */
    private function executarComando(string $nome, array $argumentos): void
    {
        $nomeComando = $this->normalizarNomeDoComando($nome);

        $todosComandos = $this->listaDeComandos();

        if ($nomeComando === "Ajuda") {
            (new Ajuda($this))->executar($argumentos);
            $this->comandoExecutado = Ajuda::class;
            return;
        }

        foreach ($todosComandos as $arquivoComando) {
            $nomeCompletoClasse = $this->interpretarNomeDaClasse($arquivoComando);

            if (class_exists($nomeCompletoClasse) === false) {
                continue;
            }

            $objetoComando = (new $nomeCompletoClasse($this));
            if ($nomeComando !== $this->normalizarNomeDoComando($objetoComando->nome())) {
                continue;
            }

            $objetoComando->executar($argumentos);
            $this->comandoExecutado = $nomeCompletoClasse;
            return;
        }

        // dispara saída padrão para o bash capturar
        echo "comando nao encontrado";
    }

    /** @return array<int,string> */
    private function listaDeDiretorios(string $caminho): array
    {
        $caminhosContexto = array_diff(
            (array)scandir($caminho),
            ['.', '..', '.gitkeep']
        );

        return array_map(fn($comando) => "$caminho/$comando", $caminhosContexto);
    }

    private function extrairNamespace(string $umArquivo): string
    {
        if (is_file($umArquivo) === false) {
            return "";
        }

        $todasLinhas = (array)file($umArquivo);
        foreach ($todasLinhas as $linha) {
            $linha = (string)$linha;
            if (str_starts_with(trim($linha), "namespace") === true) {
                return trim(str_replace(["namespace ", ";"], "", $linha));
            }
        }

        throw new RuntimeException("Não é possível extrair o namespace do arquivo '{$umArquivo}'");
    }

    private function extrairNomeDaClasse(string $arquivoComando): string
    {
        return str_replace('.php', '', array_slice(explode("/", $arquivoComando), -1)[0]);
    }

    public function interpretarNomeDaClasse(string $arquivoComando): string
    {
        return $this->extrairNamespace($arquivoComando)
            . "\\" . $this->extrairNomeDaClasse($arquivoComando);
    }

    private function normalizarNomeDoComando(string $nomeKebabCase): string
    {
        // make:user-controller -> [Make, User-controller]
        $kebabCase = array_map(
            fn($noh) => ucfirst($noh),
            explode(":", $nomeKebabCase)
        );

        $nomeSemDoisPontos = implode("", $kebabCase); // MakeUser-controller

        // MakeUser-controller -> [MakeUser, Controller]
        $pascalCase = array_map(
            fn($noh) => ucfirst($noh),
            explode("-", $nomeSemDoisPontos)
        );

        return implode("", $pascalCase); // MakeUserController
    }

    public function comandoExecutado(): string
    {
        return $this->comandoExecutado;
    }
}
