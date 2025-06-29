<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BackpackProblemReportExport;

class GeneticController extends GenericController
{

    /**
     * Exibe a página inicial do jogo da mochila.
     * 
     * @Dablio-0
     * @Willians-Henrique
     * 
     * @method index
     * 
     * @return \Illuminate\View\View Retorna a view da página inicial com os dados necessários.
     */
    public function index()
    {
        return view('genetic.index', [
            'max_capacity' => null,
            'item_count' => null,
            'generated_problem' => [],
            'items' => [],
            'initial_solution' => [],
            'evaluation' => null,
        ]);
    }

    /**
     * Gera um problema aleatório para o jogo da mochila.
     * 
     * @Dablio-0 
     * @Willians-Henrique
     * 
     * @method generateProblem
     * 
     * @param int $itemCount O número de itens a serem gerados.
     * 
     * @return array Retorna um array contendo os valores dos itens gerados aleatoriamente.
     */
    private function generateProblem($itemCount, $max_capacity)
    {
        $problem = [];
        for ($i = 0; $i < $itemCount; $i++) {
            $problem[] = mt_rand(1, intval($max_capacity/2)-1) + floatval(mt_rand(1, intval($max_capacity/2)-1)/$max_capacity); // Gera valores aleatórios entre 0 e 1
        }
        return $problem;
    }

    /**
     * Gera uma solução inicial para o problema da mochila.
     * 
     * @Dablio-0
     * @Willians-Henrique
     * 
     * @method generateInitialSolution
     * 
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param array $items Um array contendo os valores dos itens.
     * 
     * @return array Retorna um array representando a solução inicial, onde 1 indica que o item está incluído e 0 indica que não está.
     */
    private function generateInitialSolution($max_capacity, $items)
    {
        $solution = array_fill(0, count($items), 0);
        $currentWeight = 0;

        $prioritizedItems = array_keys(array_filter($items, fn($w) => $w >= 1));
        foreach ($prioritizedItems as $index) {
            if ($currentWeight + $items[$index] <= $max_capacity) {
                $solution[$index] = 1;
                $currentWeight += $items[$index];
            }
        }

        $remainingItems = array_keys(array_filter($items, fn($w) => $w < 1));
        shuffle($remainingItems);

        foreach ($remainingItems as $index) {
            if ($currentWeight + $items[$index] <= $max_capacity) {
                $solution[$index] = 1;
                $currentWeight += $items[$index];
            }
        }

        return $solution;
    }

    /**
     * Avalia a solução dada com base nos itens.
     * 
     * @Dablio-0
     * @Willians-Henrique
     * 
     * @method evaluateSolution
     * 
     * @param array $solution A solução representada como um array de 0s e 1s.
     * @param array $items Um array contendo os valores dos itens.
     * 
     * @return float Retorna o valor total da solução avaliada.
     */
    private function evaluateSolution($solution, $items)
    {
        $totalValue = 0;

        foreach ($solution as $index => $included) {
            if ($included) {
                $totalValue += $items[$index];
            }
        }
        return $totalValue;
    }

    /**
     * Método para resolver o problema da mochila.
     * 
     * @Dablio-0
     * @Willians-Henrique
     * 
     * @method solve
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function solve(Request $request)
    {
        $request->validate([
            'max_capacity' => 'required|numeric',
            'item_count' => 'required|numeric',
        ]);

        $max_capacity = $request->input('max_capacity');
        $item_count = $request->input('item_count');

        for ($i = 0; $i < $item_count; $i++) {
            $items[$i] = mt_rand(1, intval($max_capacity/2)-1) + 
            floatval(mt_rand(1, intval($max_capacity/2)-1)/$max_capacity); // Gera valores aleatórios entre 0 e 1
        }
        
        
        $generatedProblem = $this->generateProblem($item_count, $max_capacity);
        $initialSolution = $this->generateInitialSolution($max_capacity, $items);
        $evaluation = $this->evaluateSolution($initialSolution, $items);

        return view('genetic.index', [
            'max_capacity' => $max_capacity,
            'item_count' => $item_count,
            'generated_problem' => $generatedProblem,
            'initial_solution' => $initialSolution,
            'evaluation' => $evaluation,
            'items' => $items,
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /******************************************** Aplicação de Melhoria - Algoritmos Genéticos ********************************************/
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Método para ordenar os itens da população com base na aptidão (fitness).
     * 
     * @Dablio-0
     * 
     * @method sortItems
     * @param array $population A população atual representada como um array de soluções.
     * @param array $fitness As avaliações (fitness) correspondentes à população.
     * 
     * @return array Retorna um array contendo a população ordenada e as avaliações correspondentes.
     */
    public function sortItems(array $population, array $fitness)
    {
        $paired = [];

        // Junta os dois arrays como pares
        for ($i = 0; $i < count($population); $i++) {
            $paired[] = ['individual' => $population[$i], 'fitness' => $fitness[$i]];
        }

        // Ordena os pares com base em 'fitness' de forma decrescente
        usort($paired, function ($a, $b) {
            return $b['fitness'] <=> $a['fitness'];
        });

        // Separa os pares novamente
        $sortedPopulation = [];
        $sortedFitness = [];
        foreach ($paired as $pair) {
            $sortedPopulation[] = $pair['individual'];
            $sortedFitness[] = $pair['fitness'];
        }

        return [$sortedPopulation, $sortedFitness];
    }

    /**
     * Método para gerar uma população inicial aleatória.
     * 
     * @Dablio-0
     * 
     * @method initialPopulation
     * @param int $item_count O número de itens no problema.
     * @param int $populationSize O tamanho da população a ser gerada.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $max_capacity A capacidade máxima da mochila.
     * 
     * @return array Retorna um array representando a população inicial gerada aleatoriamente.
     */
    public function initialPopulation(
        int $item_count,
        int $populationSize,
        array $items,
        int $max_capacity
    ){
        $population = [];
        for ($i = 0; $i < $populationSize; $i++) {
           $v = 0;
           $c = 0;
            while($v <= $max_capacity && $c != $item_count) {
                $j = mt_rand(0, $item_count - 1);
                if (!isset($population[$i][$j]) || $population[$i][$j] == 0) {
                    $population[$i][$j] = 1;
                    $v += $items[$j];
                    $c++;
                }
            }
            if($c != $item_count) {
                $population[$i][$j] = 0;
            }
        }
        return $population;
    }

    /**
     * Método para calcular a aptidão (fitness) de cada indivíduo na população.
     * 
     * @Dablio-0
     * 
     * @method fitness
     * @param int $item_count O número de itens no problema.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $populationSize O tamanho da população.
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param array $population A população atual representada como um array de soluções.
     * 
     * @return array Retorna um array contendo as avaliações (fitness) de cada indivíduo na população.
     */
    public function fitness(
        int $item_count,
        array $items,
        int $populationSize,
        int $max_capacity,
        array $population
    ){
        // fit = np.zeros(tp,float)
        $fitness = array_fill(0, $populationSize, 0.0);
        for ($i = 0; $i < $populationSize; $i++) {
            if($fitness[$i] == $max_capacity)
                $fitness[$i] = $max_capacity * 1000;
            else 
                $fitness[$i] = $this->evaluateSolution($population[$i], $items);
        }

        $sum = array_sum($fitness);

        if ($sum != 0) {
            // Normaliza os valores (equivalente ao fit = fit/soma)
            foreach ($fitness as $i => $value) {
                $fitness[$i] = $value / $sum;
            }
        }

        return $fitness;
    }

    /**
     * Método para realizar a seleção por roleta entre os indivíduos da população.
     * 
     * @Dablio-0
     * 
     * @method roulette
     * @param array $fitness As avaliações da população atual.
     * @param int $populationSize O tamanho da população.
     * 
     * @return int Retorna o índice do indivíduo selecionado pela roleta.
     */
    public function roulette(array $fitness, int $populationSize)
    {
        $random = mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1
        $index = 0;
        $sum = $fitness[$index];
        while ($sum < $random && $index < $populationSize - 1) {
            $index += 1;
            $sum += $fitness[$index];
        }
        return $index;
    }

    /**
     * Método para realizar o torneio entre dois indivíduos da população.
     * 
     * @Dablio-0
     * 
     * @method tournament
     * @param array $fitness As avaliações da população atual.
     * @param int $populationSize O tamanho da população.
     * 
     * @return int Retorna o índice do indivíduo vencedor do torneio.
     */
    public function tournament(array $fitness, int $populationSize){
        $p1 = mt_rand($populationSize);
        $p2 = mt_rand($populationSize);
        if ($fitness[$p1] > $fitness[$p2]) {
            return $p1;
        } else {
            return $p2;
        }
    }

    /**
     * Realiza o crossover entre dois pais, retornando dois descendentes.
     * 
     * @Dablio-0
     * 
     * @method crossover
     * @param array $parent1 O primeiro pai.
     * @param array $parent2 O segundo pai.
     * @param int $pointOfCrossover O ponto de crossover onde os pais serão divididos.
     * 
     * @return array Retorna um array contendo os dois descendentes gerados a partir do crossover.
     */
    public function crossover(
        array $parent1, array $parent2,
        int $pointOfCrossover
    ){
        $d1 = array_merge(array_slice($parent1, 0, $pointOfCrossover), array_slice($parent2, $pointOfCrossover));
        $d2 = array_merge(array_slice($parent2, 0, $pointOfCrossover), array_slice($parent1, $pointOfCrossover));

        return [$d1, $d2];
    }

    public function mutation(array $individual, int $item_count) {
        $pos = mt_rand(0, $item_count - 1);
        $individual[$pos] = !$individual[$pos];

        return $individual;
    }

    /**
     * Método para gerar descendentes a partir da população atual.
     * 
     * @Dablio-0
     * 
     * @method descendants
     * @param int $item_count O número de itens no problema.
     * @param array $population A população atual.
     * @param array $fitness As avaliações da população atual.
     * @param int $populationSize O tamanho da população.
     * @param float $crossoverTax A taxa de crossover, representando a probabilidade de realizar o crossover entre dois indivíduos.
     * @param float $mutationTax A taxa de mutação, representando a probabilidade de realizar uma mutação em um indivíduo.
     * 
     * @return array Retorna um array contendo os descendentes gerados e o número de descendentes criados (qd).
     */
    public function descendants(
        int $item_count,
        array $population,
        array $fitness,
        int $populationSize,
        float $crossoverTax,
        float $mutationTax
    ){
        $qd = 3 * $populationSize;
        $descendants = [];
        $pointOfCrossover = mt_rand(1, $item_count - 1);
        $i = 0;
        while($i < $qd){
            $p1 = $this->roulette($fitness, $populationSize);
            $p2 = $this->roulette($fitness, $populationSize);

            if ($p1 == $p2) {
                continue; // Evita selecionar o mesmo indivíduo
            }

            if (mt_rand() / mt_getrandmax() < $crossoverTax) {
                // Realiza o crossover
                [$d1, $d2] = $this->crossover($population[$p1], $population[$p2], $pointOfCrossover);
                $descendants[] = $d1;
                $descendants[] = $d2;
            } else {
                // Apenas copia os pais
                $descendants[] = $population[$p1];
                $descendants[] = $population[$p2];
            }

            // Aplica mutação
            if (mt_rand() / mt_getrandmax() < $mutationTax) {
                foreach ($descendants as &$descendant) {
                    $descendant = $this->mutation($descendant, $item_count);
                }
            }
            $i += 2; // Incrementa por dois porque adicionamos dois descendentes
        }

        return [$descendants, $qd];
    }

    /**
     * Método para criar uma nova população a partir dos descendentes.
     * 
     * @Dablio-0
     * 
     * @method newPopulation
     * @param array $population A população atual.
     * @param array $descendants Os descendentes gerados.
     * @param int $populationSize O tamanho da população.
     * @param float $generationInterval O intervalo de geração, que determina quantos indivíduos da população serão mantidos na próxima geração.
     * 
     * @return array Retorna a nova população formada pelos melhores indivíduos e os descendentes.
     */
    public function newPopulation(
        array $population,
        array $descendants,
        int $populationSize,
        float $generationInterval
    ){
        $elite = ceil($generationInterval * $populationSize);
        for ($i = 0; $i < $populationSize - $elite; $i++) {
            $population[$i + $elite] = $descendants[$i];
        }
        return $population;
    }

    /**
     * Método para corrigir as soluções descendentes que excedem a capacidade máxima da mochila.
     * 
     * @Dablio-0
     * 
     * @method restrictionFix
     * @param int $item_count O número de itens no problema.
     * @param array $items Um array contendo os valores dos itens.
     * @param array $descendants Um array contendo as soluções descendentes.
     * @param int $qd O número de descendentes.
     * @param int $max_capacity A capacidade máxima da mochila.
     * 
     * @return array Retorna um array contendo as soluções descendentes corrigidas.
     * @return array mixed
     */
    public function restrictionFix(
        int $item_count,
        array $items,
        array $descendants,
        int $qd,
        int $max_capacity
    ){
        for($i = 0; $i < $qd; $i++) {
            $weight = $this->evaluateSolution($descendants[$i], $items);
            while ($weight > $max_capacity) {
                $j = mt_rand(0, $item_count);
                if($descendants[$i][$j] == 1) {
                    $descendants[$i][$j] = 0;
                    $weight -= $items[$j];
                }
            }
        }
        return $descendants;
    }

    /**
     * Método principal que executa o algoritmo genético para resolver o problema da mochila.
     * 
     * @Dablio-0
     * 
     * @method geneticAlgorithm
     * @param int $item_count O número de itens no problema.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param int $populationSize O tamanho da população inicial.
     * @param int $generationNum O número de gerações a serem executadas.
     * @param float $crossoverTax A taxa de crossover, representando a probabilidade de realizar o crossover entre dois indivíduos.
     * @param float $mutationTax A taxa de mutação, representando a probabilidade de realizar uma mutação em um indivíduo.
     * @param int $generationInterval O intervalo de geração, que determina quantos indivíduos da população serão mantidos na próxima geração.
     * @return array Retorna um array contendo a solução inicial, a melhor solução encontrada, as avaliações das soluções e o ganho percentual.
     * 
     * @return array mixed
     */
    public function geneticAlgorithm(
        int $item_count,
        array $items,
        int $max_capacity,
        int $populationSize,
        int $generationNum,
        float $crossoverTax,
        float $mutationTax,
        int $generationInterval
    ){
        $population = $this->initialPopulation($item_count, $populationSize, $items, $max_capacity);
        $fitness = $this->fitness($item_count, $items, $populationSize, $max_capacity, $population);
        $result1 = $this->sortItems($population, $fitness);
        $population = $result1[0];
        $fitness = $result1[1];
        $initialSolution = $population[0];
        for($i = 0; $i < $generationNum; $i++) {
            $result2 = $this->descendants(
                $item_count,
                $population,
                $fitness,
                $populationSize,
                $crossoverTax,
                $mutationTax,
            );

            $descendants = $result2[0];
            $qd = $result2[1];
            
            $descendants = $this->restrictionFix($item_count, $items, $descendants, $qd, $max_capacity);
            $fitness_d = $this->fitness($item_count, $items, $populationSize, $max_capacity, $descendants);

            $result3 = $this->sortItems($population, $fitness);
            $population = $result3[0];
            $fitness = $result3[1];

            $result4 = $this->sortItems($descendants, $fitness_d);
            $descendants = $result4[0];
            $fitness_d = $result4[1];

            $population = $this->newPopulation($population, $descendants, $populationSize, $generationInterval);
            $fitness = $this->fitness($item_count, $items, $populationSize, $max_capacity, $population); 
        }

        $result5 = $this->sortItems($population, $fitness);
        $population = $result5[0];
        $fitness = $result5[1];
        $bestSolution = $population[0];

        return [
            'initialSolution' => $initialSolution,
            'bestSolution' => $bestSolution,
            'evaluation_initialSolution' => $this->evaluateSolution($initialSolution, $items),
            'evaluation_bestSolution' => $this->evaluateSolution($bestSolution, $items),
            'gain' => $this->evaluateSolution($bestSolution, $items) - $this->evaluateSolution($initialSolution, $items),
            'gain_percentage' => 
                ($this->evaluateSolution($bestSolution, $items) - $this->evaluateSolution($initialSolution, $items)) 
                / $this->evaluateSolution($initialSolution, $items) * 100,
        ];
    }

    /**
     * Método geral para aplicar as melhorias possíveis na solução inicial do problema da mochila.
     * 
     * @Dablio-0
     * 
     * @method improve
     * 
     * @param Request $request A requisição contendo os dados necessários para a melhoria.
     * @param array $initialSolution A solução inicial representada como um array de 0s e 1s.
     * @param float $evaluation A avaliação da solução inicial.
     * @param array $items Um array contendo os valores dos itens.
     * 
     * @class Illuminate\Support\Facades\Validator
     * 
     * @return \Illuminate\View\View Retorna a view com a solução melhorada e sua avaliação.
     */
    public function improve(Request $request, $initialSolution, $generatedProblem, $items, $itemCount, $maxCapacity, $evaluation)
    {
        $request->validate([
            'populationSize' => 'nullable|numeric',
            'crossoverTax' => ['nullable', 'regex:/^\d+(\.\d+)?%?$/'],
            'mutationTax' => ['nullable', 'regex:/^\d+(\.\d+)?%?$/'],
            'generationInterval' => ['nullable', 'regex:/^\d+(\.\d+)?%?$/'],
            'generationNum' => 'nullable|numeric',
            'defaultParameters' => 'nullable|boolean',
        ]);

        /* Desserializar os arrays e capturar valores iniciais */
        $max_capacity = intval($maxCapacity);
        $item_count = intval($itemCount);
        $generatedProblem = is_string($generatedProblem) ? json_decode($generatedProblem, true) : ($generatedProblem ?? []);
        $initialSolution = is_string($initialSolution) ? json_decode($initialSolution, true) : ($initialSolution ?? []);
        $items = is_string($items) ? json_decode($items, true) : ($items ?? []);

        $primarySolution = $initialSolution;
        $primaryEvaluation = $evaluation;

        if (!$request->input('defaultParameters')) {
            
            // Tratar campos que podem vir como porcentagem (ex: "10%")
            $crossoverTax = $request->input('crossoverTax');
            $mutationTax = $request->input('mutationTax');
            $generationInterval = $request->input('generationInterval');

            $crossoverTax = is_string($crossoverTax) && str_contains($crossoverTax, '%')
            ? floatval(str_replace('%', '', $crossoverTax)) / 100
                : floatval($crossoverTax);

            $mutationTax = is_string($mutationTax) && str_contains($mutationTax, '%')
            ? floatval(str_replace('%', '', $mutationTax)) / 100
            : floatval($mutationTax);

            $generationInterval = is_string($generationInterval) && str_contains($generationInterval, '%')
                ? floatval(str_replace('%', '', $generationInterval)) / 100
                : floatval($generationInterval);

            $populationSize = intval($request->input('populationSize'));
            $generationNum = intval($request->input('generationNum'));

            $results = [];

            $results = $this->geneticAlgorithm(
                $item_count,
                $items,
                $max_capacity,
                intval($request->input('populationSize')),
                intval($request->input('generationNum')),
                $crossoverTax,
                $mutationTax,
                $generationInterval
            );

            $data = collect([
                $this->dataResults(
                    $request->input('defaultParameters'),
                    $max_capacity,
                    $item_count,
                    $generatedProblem,
                    $primarySolution,
                    $primaryEvaluation,
                    $items,
                )
            ]); 

            $data = $data->merge(collect($results));

            return view('genetic.index', compact('data'));

        } else {

            $N = 10; // Valor base para N

            $populationSize = [$N, 2 * $N];
            $generationNum = [$N, 10 * $N, 100 * $N];
            $crossoverTax = [0.4, 0.6, 0.8];
            $mutationTax = [0.0, 0.1, 0.2, 0.7];
            $generationInterval = [0.0, 0.15, 0.6];

            $results = [];
            // Simulação dos loops conforme o quadro
            foreach ($populationSize as $tp) {
                foreach ($generationNum as $ng) {
                    foreach ($crossoverTax as $tc) {
                        foreach ($mutationTax as $tm) {
                            foreach ($generationInterval as $ig) {

                                // Executa o algoritmo genético
                                $res = $this->geneticAlgorithm(
                                    $itemCount,
                                    $items,
                                    $maxCapacity,
                                    $tp,
                                    $ng,
                                    $tc,
                                    $tm,
                                    $ig
                                );

                                // Armazena os resultados junto com os parâmetros utilizados
                                $results[] = [
                                    'TP' => $tp,
                                    'NG' => $ng,
                                    'TC' => $tc,
                                    'TM' => $tm,
                                    'IG' => $ig,
                                    'generated_problem' => $generatedProblem,
                                    'items' => $items,
                                    'primary_solution' => $primarySolution,
                                    'primary_evaluation' => $primaryEvaluation,
                                    'best_solution' => $res['best_solution'] ?? null,
                                    'evaluation_bestSolution' => $res['evaluation_bestSolution'] ?? null,
                                    'evaluation_initialSolution' => $res['evaluation_initialSolution'] ?? null,
                                    'ganho' => $res['gain'] ?? null,
                                    'ganho_porcentagem' => $res['gain'] ?? null,
                                ];
                            }
                        }
                    }
                }
            }

            $data = collect([
                $this->dataResults(
                    $request->input('defaultParameters'),
                    $max_capacity,
                    $item_count,
                    $generatedProblem,
                    $primarySolution,
                    $primaryEvaluation,
                    $items,
                )
            ]);

            $data = $data->merge(collect($results));

            dd($data);

            return view('genetic.index', compact('data'));
        }
    }

    /**
     * Exibe os resultados das melhorias aplicadas na solução inicial do problema da mochila.
     * 
     * @Dablio-0
     * 
     * @method dataResults
     * 
     * @param int $max_capacity
     * @param int $item_count
     * @param array $generatedProblem
     * @param array $primarySolution
     * @param float $primaryEvaluation
     * @param array $items
     * @param array $results
     * 
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function dataResults(
        bool $defaultParameters,
        int $max_capacity,
        int $item_count,
        array $generatedProblem,
        array $primarySolution,
        float $primaryEvaluation,
        array $items
    ) {
        return [
            'default_parameters_used_for_execution' => $defaultParameters,

            /* Dados Inciais do problema */
            'max_capacity' => $max_capacity,
            'item_count' => $item_count,
            'generated_problem' => $generatedProblem,
            'primary_solution' => $primarySolution,
            'primary_evaluation' => $primaryEvaluation,
            'items' => $items,
        ];
    }
}
