# Flood Test

## Introdução

Uma das formas mais interessantes de se desenvolver as habilidades de programação é resolver testes de lógica. Existem milhares deles, em diversos sites. Só para citar alguns:
- [Exercism](https://exercism.org/)
- [HackerRank](https://www.hackerrank.com/)
- [Codewars](https://www.codewars.com/)
- [CodinGame](https://www.codewars.com/)

Nestes sites, podemos encontrar problemas de todos os níveis, dos mais simples aos quase impossíveis, que requerem habilidades com a linguagem e muitas vezes utilizam apenas conceitos elementares, como arrays, laços e condicionais, mas, em contrapartida, requerem bastante criatividade.

## O exercício

O **Flood Test** é um exemplo de problema que é resolvido com extrema facilidade por um humano, mas, quando é analisado sob o ponto de vista da programação, requer soluções engenhosas.

Existem várias formas de se resolver este problema, que entrega soluções com os mais diversos níveis de performance. Pode-se usar, por exemplo, a recursividade, pois trata-se de uma versão simplificada do clássico algoritmo [Flood Fill](https://en.wikipedia.org/wiki/Flood_fill).

Meu primeiro contato com o Flood Fill original ocorreu no início da década de 90, quando eu estudei o algoritmo do comando [PAINT](https://github.com/gseidler/The-MSX-Red-Book/blob/master/the_msx_red_book.md#59c5h) do BASIC residente do MSX. Ele é escrito em Assembly Z80, é incrivelmente lento mas cumpre seu propósito.

## A implementação

Optei por uma implementação bastante simples, que se baseia não exatamente no **fill**, mas no sentido inverso: o **leak**. Parti do pressuposto que inicialmente toda a área está alagada e analisei de forma procedural os possíveis pontos de escape do *líquido*, pela esquerda e pela direita, ajustando uma matriz bidimensional, que pode ser apresentada de forma *gráfica* na tela.

Encapsulei tudo numa pequena classe, contendo apenas cinco métodos públicos:

- **loadFile()**, responsável por carregar e interpretar o arquivo de casos, de acordo com a especificação apresentada;
- **getNumCases()**, que retorna apenas o número de casos carregados na instância;
- **getCase()**, que retorna os detalhes de um caso em especial, dado o seu índice;
- **flood()**, que *alaga* um dado caso, a partir de seu índice;
- **floodAll()**, que *alaga* todos os casos, retornando o resultado conforme solicitado na especificação.

Para o teste básico:
- **DADO** que existe um arquivo de casos chamado ```testcases.txt```
- **SENDO** este arquivo o exemplo apresentado na especificação
- **ENTÃO** eu devo *instanciar a classe*
- **E** *carregar o arquivo* ```testcases.txt```
- **E** *solicitar o alagamento* de todos os casos presentes no arquivo.

A implementação é escrita em ```PHP```, e o programa ```testFill.php``` com a atividade descrita acima é o seguinte:


```php
<?php

    require_once 'Fill.php';

    $fill   = new Fill();
    $result = $fill->loadFile('testcases.txt');

    if (!$result['status']) {
        echo $result['msg'];
    } else {
        echo($fill->floodAll());
    }
```

O programa de teste acima pressupõe, evidentemente, que haja o arquivo ```Fill.php``` no mesmo diretório dele e do arquivo de casos de teste descrito acima. O comando para a execução do teste é:
```bash
> php testFill.php
```
Este comando deve retornar:
```
16
214
0
0
0
0
0
```
tal qual solicitado na especificação.

O coração da lógica reside no método *protected leak* que analisa o "tabuleiro" localizando vazamentos à esquerda ou à direita, a depender dos parâmetros passados.

## Observações gerais e possíveis melhorias

- Vários métodos foram implementados para ilustrar algumas funcionalidades secundárias da classe.
- Apesar de implementar diversos testes de sanidade, é possível acrescentar ainda mais análises, tornando a classe mais resistente.
- *Testes unitários* podem ser implementados facilmente a partir do **PHPUnit**, o que faria mais sentido em um projeto do mundo real.
- Incluí um arquivo chamado ```basiccase.txt``` contendo o caso inicial da descrição da atividade, cujo resultado esperado é **36**.
- Seria ideal (caso eu tivesse mais tempo) incluir a infraestrutura **Docker** para executar esta aplicação de forma conteinerizada, indepentende do ambiente hospedeiro.
- Outro bom exercício seria implementar a solução sob a forma de uma **API**. Neste caso, eu escolheria o Lumen como o framework.
- Detalhes da atividade foram suprimidos a pedido do autor.