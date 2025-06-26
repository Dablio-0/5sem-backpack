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
    /************************************** Aplicação de Melhoria - Algoritmos Genéticos (Busca Local) ************************************/
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function initial_population(
        
    )
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
            'populationSize' => 'required|numeric',
            'crossoverTax' => ['required', 'regex:/^\d+(\.\d+)?%?$/'],
            'mutationTax' => ['required', 'regex:/^\d+(\.\d+)?%?$/'],
            'generationInterval' => ['required', 'regex:/^\d+(\.\d+)?%?$/'],
            'generationNum' => 'required|numeric',
        ]);

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

        /* Desserializar os arrays e capturar valores iniciais */
        $max_capacity = intval($maxCapacity);
        $item_count = intval($itemCount);
        $generatedProblem = is_string($generatedProblem) ? json_decode($generatedProblem, true) : ($generatedProblem ?? []);
        $initialSolution = is_string($initialSolution) ? json_decode($initialSolution, true) : ($initialSolution ?? []);
        $items = is_string($items) ? json_decode($items, true) : ($items ?? []);

        $primarySolution = $initialSolution;
        $primaryEvaluation = $evaluation;

        $results = [];



        $data = collect([
            $this->dataResults(
                $max_capacity,
                $item_count,
                $generatedProblem,
                $primarySolution,
                $primaryEvaluation,
                $items,
                $results
            )
        ]); 

        $data = $data->first();
        return view('genetic.index', compact('data'));
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
        $max_capacity,
        $item_count,
        $generatedProblem,
        $primarySolution,
        $primaryEvaluation,
        $items, 
        array $results = []
    ) {
        return [
            /* Dados do problema */
            'max_capacity' => $max_capacity,
            'item_count' => $item_count,
            'generated_problem' => $generatedProblem,
            'primary_solution' => $primarySolution,
            'primary_evaluation' => $primaryEvaluation,
            'items' => $items,

            /* Resultados das melhorias */
            'results' => [
                
            ],
        ];
    }
}
