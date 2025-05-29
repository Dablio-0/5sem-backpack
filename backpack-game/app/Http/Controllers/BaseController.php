<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BackpackProblemReportExport;

class BaseController extends GenericController
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
        return view('base.index', [
            'max_capacity' => null,
            'item_count' => null,
            'generated_problem' => [],
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
    private function generateProblem($itemCount)
    {
        $problem = [];
        for ($i = 0; $i < $itemCount; $i++) {
            $problem[] = mt_rand(1, 100) / 100; // Gera valores aleatórios entre 0 e 1
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

        $items = array_map(fn() => mt_rand(1, 100) / 10, range(1, $item_count));

        $generatedProblem = $this->generateProblem($item_count);
        $initialSolution = $this->generateInitialSolution($max_capacity, $items);
        $evaluation = $this->evaluateSolution($initialSolution, $items);

        return view('base.index', [
            'max_capacity' => $max_capacity,
            'item_count' => $item_count,
            'generated_problem' => $generatedProblem,
            'initial_solution' => $initialSolution,
            'evaluation' => $evaluation,
            'items' => $items,
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /********************************************** Aplicação de Melhoria (Busca Local) ***************************************************/
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Gera os successors de uma solução inicial possíveis de melhorar a nota de avaliação.
     * 
     * @Dablio-0 
     * 
     * @method successors
     * 
     * @param array $initialSolution A solução inicial representada como um array de 0s e 1s.
     * @param float $evaluation A avaliação da solução inicial.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param int $item_count O número total de itens.
     * @param int|null $successors_num O número de sucessores a serem gerados. Se não for fornecido, será igual ao número de itens.
     * 
     * @return array Retorna um array contendo a melhor solução encontrada e sua avaliação.
     */
    public function successors(
                                array $initialSolution, 
                                float $evaluation, 
                                array $items, 
                                int $max_capacity,  
                                int $item_count, 
                                int $successors_num
                            )
    {
        // Inicializando valores
        $better = $initialSolution;
        $betterEvaluation = $evaluation;
        $numSuccessors = $successors_num;

        for ($i = 0; $i < $numSuccessors; $i++) {

            $aux = $initialSolution;

            while (true) {
                $p = mt_rand(0, $item_count - 1);
                if ($aux[$p] == 0) {
                    $aux[$p] = 1;
                    $$evaluation = $this->evaluateSolution($aux, $items);
                    break;
                }
            }

            $k = $p + 1;

            for ($j = 0; $j < $item_count - 1; $j++) {
                if ($k == $item_count) {
                    $k = 0;
                }

                $aux[$k] = 1;
                $evaluation = $this->evaluateSolution($aux, $items);
                if ($evaluation > $max_capacity) {
                    $aux[$k] = 0; // Reverte a mudança se exceder a capacidade
                }

                $k++;
            }

            $evaluation = $this->evaluateSolution($aux, $items);

            if ($evaluation > $betterEvaluation) {
                $better = $aux;
                $betterEvaluation = $evaluation;
            }
        }

        return [
            'final_solution' => $better,
            'final_evaluation' => $betterEvaluation,
        ];
    }

    /**
     * Implementa o algoritmo de subida de encosta.
     * 
     * @Dablio-0
     * 
     * @method hillClimbing
     * 
     * @param array $initialSolution A solução inicial representada como um array de 0s e 1s.
     * @param float $evaluation A avaliação da solução inicial.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param int $item_count O número total de itens.
     * 
     * @return array Retorna a melhor solução encontrada e sua avaliação.
     */
    public function hillClimbing(
                                array $initialSolution, 
                                float $evaluation, 
                                array $items, 
                                int $max_capacity, 
                                int $item_count, 
                                ?int $successors_num = null
                            )
    {
        // Inicializando valores
        $current = $initialSolution;
        $currentEvaluation = $evaluation;
        $successors_num = $successors_num ?? $item_count; // Se não for fornecido, usa o número total de itens

        while (true) {
            $successors = $this->successors($current, $currentEvaluation, $items, $max_capacity, $item_count, $successors_num);
            $newSolution = $successors['final_solution'];
            $newEvaluation = $successors['final_evaluation'];

            if ($newEvaluation > $currentEvaluation) {
                $current = $newSolution;
                $currentEvaluation = $newEvaluation;
            } else {
                break; // Se não houver melhoria, sai do loop
            }
        }

        return [
            'final_solution' => $current,
            'final_evaluation' => $currentEvaluation,
        ];
    }

    /**
     * Implementa o algoritmo de subida de encosta alterada.
     * 
     * @Dablio-0
     * 
     * @method changedHillClimbing
     * 
     * @param array $initialSolution A solução inicial representada como um array de 0s e 1s.
     * @param float $evaluation A avaliação da solução inicial.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param int $item_count O número total de itens.
     * @param int $successors_num O número de sucessores a serem gerados.
     * @param int $max_attemps O número máximo de tentativas para encontrar uma solução melhor.
     * 
     * @return array Retorna a melhor solução encontrada e sua avaliação.
     */
    public function changedHillClimbing(
                                        array $initialSolution, 
                                        float $evaluation, 
                                        array $items, 
                                        int $max_capacity, 
                                        int $item_count, 
                                        int $max_attemps, 
                                        ?int $successors_num = null
                                    )
    {
        // Inicializando valores
        $current = $initialSolution;
        $currentEvaluation = $evaluation;
        $attemps = 0;
        $max_attemps = $max_attemps;
        $successors_num = $successors_num ?? $item_count;

        while (true) {
            $successors = $this->successors($current, $currentEvaluation, $items, $max_capacity, $item_count, $successors_num);
            $newSolution = $successors['final_solution'];
            $newEvaluation = $successors['final_evaluation'];

            if ($newEvaluation > $currentEvaluation) {
                if ($attemps > $max_attemps) {
                    break; // Se o número de tentativas exceder o máximo, sai do loop
                } else {
                    $attemps++;
                }
            } else {
                $current = $newSolution;
                $currentEvaluation = $newEvaluation;
                $attemps = 0;
            }
        }

        return [
            'final_solution' => $current,
            'final_evaluation' => $currentEvaluation,
        ];
    }

    /**
     * Implementa o algoritmo de têmpera simulada
     * 
     * @Dablio-0
     * 
     * @method simulatedAnnealing
     * 
     * @param array $initialSolution A solução inicial representada como um array de 0s e 1s.
     * @param float $evaluation A avaliação da solução inicial.
     * @param array $items Um array contendo os valores dos itens.
     * @param int $max_capacity A capacidade máxima da mochila.
     * @param int $item_count O número total de itens.
     * @param int $successors_num O número de sucessores a serem gerados.
     * @param float $initial_temp A temperatura inicial.
     * @param float $final_temp A temperatura final.
     * @param float $reducing_factor O fator de redução da temperatura.
     * 
     * @return array Retorna a melhor solução encontrada e sua avaliação.
     */
    public function simulatedAnnealing( 
                                        array $initialSolution, 
                                        float $evaluation, 
                                        array $items, 
                                        int $max_capacity, 
                                        int $item_count, 
                                        ?int $successors_num = null, 
                                        float $initial_temp, 
                                        float $final_temp, 
                                        float $reducing_factor
                                    )
    {
        // Inicializando valores
        $current = $initialSolution;
        $currentEvaluation = $evaluation;
        $successors_num = $successors_num ?? $item_count;

        // Implementar a lógica de têmpera simulada aqui
        // Esta função deve retornar a melhor solução encontrada e sua avaliação
        return [
            'final_solution' => $initialSolution,
            'final_evaluation' => $evaluation,
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
    public function improve(Request $request, $initialSolution, $evaluation, $items)
    {
        // Primeiro, valida apenas o campo do método
        $request->validate([
            'metodo_melhoria' => 'required|in:1,2,3',
        ]);

        $method = $request->input('metodo_melhoria');

        // Regras condicionais por método
        $rules = [];

        switch ($method) {
            case '1': // Subida de Encosta
                $rules = [
                    'successors_num' => 'required|integer|min:1',
                ];
                break;
            case '2': // Subida de Encosta Alterada
                $rules = [
                    'successors_num' => 'required|integer|min:1',
                    'max_attemps' => 'required|integer|min:1',
                ];
                break;
            case '3': // Têmpera Simulada
                $rules = [
                    'successors_num' => 'required|integer|min:1',
                    'initial_temp' => 'required|numeric|gt:0',
                    'final_temp' => 'required|numeric|gt:0|lt:initial_temp',
                    'reducing_factor' => 'required|numeric|gt:0|lt:1',
                ];
                break;
        }

        // Valida os campos conforme o método
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $max_capacity = $request->input('max_capacity');
        $item_count = $request->input('item_count');

        // Desserializar os arrays
        $generatedProblem = json_decode($request->input('generated_problem'), true);
        $initialSolution = json_decode($request->input('initial_solution'), true);
        $evaluation = floatval($request->input('evaluation'));
        $items = json_decode($request->input('items'), true);

        $primarySolution = $initialSolution;
        $primaryEvaluation = $evaluation;

        $results = [];
        switch ($method) {
            case 1:

                // chama a funcao de subida de encosta
                $results['hillClimbing_results'] = $this->hillClimbing(
                                                                    $initialSolution, 
                                                                    $evaluation, 
                                                                    $items,
                                                                    $max_capacity, 
                                                                    $item_count
                                                                );
                break;  

            case 2:

                // lógica para subida de encosta alterada
                $successors_num = $request->input('successors_num');
                $max_attemps = $request->input('max_attemps');

                $results['changedHillClimbing_results'] = $this->changedHillClimbing( $initialSolution,
                                            $evaluation,
                                            $items, 
                                            $max_capacity, 
                                            $item_count, 
                                            $successors_num, 
                                            $max_attemps
                                        );
                break;

            case 3:

                // lógica para têmpera simulada
                $successors_num = $request->input('successors_num');
                $initial_temp = $request->input('initial_temp');
                $final_temp = $request->input('final_temp');
                $reducing_factor = $request->input('reducing_factor');

                $results['simulatedAnnealing_results'] = $this->simulatedAnnealing( $initialSolution,
                                            $evaluation,
                                            $items, 
                                            $max_capacity, 
                                            $item_count, 
                                            $successors_num,
                                            $initial_temp,
                                            $final_temp,
                                            $reducing_factor
                                        );
                break;
            
            case 4:

                // Execuçõo de todos os anteriores
                $results['hillClimbing_results'] = $this->hillClimbing($initialSolution, 
                                    $evaluation, 
                                    $items,
                                    $max_capacity, 
                                    $item_count
                                );

                $results['changedHillClimbing_results'] = $this->changedHillClimbing( $initialSolution,
                                            $evaluation,
                                            $items, 
                                            $max_capacity, 
                                            $item_count, 
                                            $request->input('successors_num'),
                                            $request->input('max_attemps')
                                        );

                $results['simulatedAnnealing_results'] = $this->simulatedAnnealing( $initialSolution,
                                            $evaluation,
                                            $items, 
                                            $max_capacity, 
                                            $item_count, 
                                            $request->input('successors_num'),
                                            $request->input('initial_temp'),
                                            $request->input('final_temp'),
                                            $request->input('reducing_factor')
                                        );

            default:
                return back()->withErrors(['improvement_method' => 'Método inválido']);
        }

        return $this->dataResults(
                                    $max_capacity, 
                                    $item_count, 
                                    $generatedProblem, 
                                    $primarySolution, 
                                    $primaryEvaluation, 
                                    $items, 
                                    $method, 
                                    $successors_num, 
                                    $max_attemps, 
                                    $initial_temp, 
                                    $final_temp, 
                                    $reducing_factor, 
                                    $results
                                );
        }

        public function exportImprovementResults(
                                    $max_capacity, 
                                    $item_count, 
                                    $generatedProblem, 
                                    $primarySolution, 
                                    $primaryEvaluation, 
                                    $items, 
                                    $method, 
                                    $successors_num = null, 
                                    $max_attemps = null, 
                                    $initial_temp = null, 
                                    $final_temp = null, 
                                    $reducing_factor = null, 
                                    array $results = []
                                )
        {
            
            $data = $this->dataResults(
                $max_capacity, 
                $item_count, 
                $generatedProblem, 
                $primarySolution, 
                $primaryEvaluation, 
                $items, 
                $method, 
                $successors_num, 
                $max_attemps, 
                $initial_temp, 
                $final_temp, 
                $reducing_factor, 
                $results
            );
            
            return Excel::download(new BackpackProblemReportExport($data), 'Relatório - Problema da Mochila - ' . date('Y-m-d_H-i-s') . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
        }

        /**
         * Exibe os resultados das melhorias aplicadas na solução inicial do problema da mochila.
         * 
         * @Dablio-0
         * 
         * @method dataResults
         * 
         * @param mixed $max_capacity
         * @param mixed $item_count
         * @param mixed $generatedProblem
         * @param mixed $primarySolution
         * @param mixed $primaryEvaluation
         * @param mixed $items
         * @param mixed $method
         * @param mixed $successors_num
         * @param mixed $max_attemps
         * @param mixed $initial_temp
         * @param mixed $final_temp
         * @param mixed $reducing_factor
         * @param array $results
         * 
         * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
         */
        public function dataResults(
                                    $max_capacity,
                                    $item_count,
                                    $generatedProblem,
                                    $primarySolution,
                                    $primaryEvaluation,
                                    $items,
                                    $method,
                                    $successors_num = null,
                                    $max_attemps = null,
                                    $initial_temp = null,
                                    $final_temp = null,
                                    $reducing_factor = null,
                                    array $results = []
                                ) 
        {
            return view('base.results', [

                    /* Dados do problema */
                    'max_capacity' => $max_capacity,
                    'item_count' => $item_count,
                    'generated_problem' => $generatedProblem,
                    'primary_solution' => $primarySolution,
                    'primary_evaluation' => $primaryEvaluation,
                    'items' => $items,

                    /* Dados do método de melhoria */
                    'method' => $method,

                    /* Dados Subida de Encosta e Subida de Encosta Alterada */
                    'successors_num' => $successors_num ?? null, // presente em todos os métodos
                    'max_attemps' => $max_attemps ?? null,

                    /* Dados de Têmpera Simulada */
                    'initial_temp' => $initial_temp ?? null,
                    'final_temp' => $final_temp ?? null,
                    'reducing_factor' => $reducing_factor ?? null,

                    /* Resultados das melhorias */
                    'results' => [
                        'hillClimbing' => $results['hillClimbing_results'] ?? null,
                        'changedHillClimbing' => $results['changedHillClimbing_results'] ?? null,
                        'simulatedAnnealing' => $results['simulatedAnnealing_results'] ?? null,
                    ],
                ]);
        }
}