<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends GenericController
{
    public function index()
    {
        return view('base.index', [
            'max_capacity' => null,
            'item_count' => null,
            'generated_problem' => [],
            'initial_solution' => [],
            'evaluation' => null,
            // 'successors_count' => 0,
            // 'successors' => [],
            // 'attemps' => 0,
            // 'initial_temperature' => null,
            // 'final_temperature' => null,
            // 'reducing_factor' => null,
            // 't_max' => null,

        ]);
    }

    private function generateProblem($itemCount)
    {
        $problem = [];
        for ($i = 0; $i < $itemCount; $i++) {
            $problem[] = mt_rand(1, 100) / 100; // Gera valores aleatórios entre 0 e 1
        }
        return $problem;
    }

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
    /*********************************************** Soluções Iniciais e Avaliações *******************************************************/
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function improve(Request $request)
    {
        $request->validate([

            if ($request->has('metodo_melhoria')) {

                if ($request->input('metodo_melhoria') == '1') {
                    // Implementar lógica de busca local
                } elseif ($request->input('metodo_melhoria') == '2') {
                    // Implementar lógica de recozimento simulado
                } elseif ($request->input('metodo_melhoria') == '3') {
                }
            }
            
            else {
                return redirect()->back()->withErrors(['metodo_melhoria' => 'Método de melhoria não selecionado.']);
            }
        ]);

        $max_capacity = $request->input('max_capacity');
        $item_count = $request->input('item_count');
        $items = $request->input('items');
        $initialSolution = $request->input('initial_solution');

        // Aqui você pode implementar a lógica de melhoria da solução inicial
        // Por exemplo, aplicar um algoritmo de busca local ou outro método de otimização

        // Para fins de exemplo, vamos apenas recalcular a avaliação da solução inicial
        $evaluation = $this->evaluateSolution($initialSolution, $items);

        return view('base.index', [
            'max_capacity' => $max_capacity,
            'item_count' => $item_count,
            'generated_problem' => [],
            'initial_solution' => $initialSolution,
            'evaluation' => $evaluation,
            'items' => $items,
        ]);
    }
}

//         import random
// import numpy as np


// def gerar_problema_caixeiro_viajante(n):
//     print("Caixeiros Viajantes")
//     matriz = [[0 for _ in range(n)] for _ in range(n)]
    
//     for i in range(n):
//         for j in range(n):
//             if i == j:
//                 print(f"p{i} p{j} 0")
//                 matriz[i][j] = 0
//             else:
//                 valor_aleatorio = random.uniform(0, 1)
//                 print(f"p{i} p{j} {valor_aleatorio}")
//                 matriz[i][j] = valor_aleatorio
    
//     return matriz

// # j = número de itens
// def gerar_problema_mochila(j):
//     print("Mochilas")
    
//     array = [0 for _ in range(j)] 
//     print(array)    
    
//     for i in range(j):
//         array[i] = random.uniform(0, 1)
//         print(f"p{i} {array[i]}")

//     return array

// # N = número de itens
// # L_min = peso mínimo
// # L_max = peso máximo
// def gerar_problema_mcohila_multi(n, q, l_min, l_max):
//     print("Mochilas Multi")
    
//     vets = []
//     for i in range(q):
//         array = [0 for _ in range(n)]
//         print(array)
        
//         for j in range(n):
//             array[j] = random.uniform(l_min, l_max)
//             print(f"p{j} {array[j]}")
        
//         vets.append(array)
//     return vets
    
// # Exemplos de usos
// n = 3
// matriz_gerada = gerar_problema_caixeiro_viajante(n)
// print("Matriz gerada:")
// for linha in matriz_gerada:
//     print(linha) 
// print("\n")
// j = 5
// array_gerado = gerar_problema_mochila(j)
// print("Array gerado:")
// for linha in array_gerado:
//     print(linha)
// print("\n")
// n = 2
// q = 3
// l_min = 0.5
// l_max = 1.5
// array_gerado = gerar_problema_mcohila_multi(n, q, l_min, l_max)
// print("Vetores gerados:")
// for vet in array_gerado:
//     print(vet)

// # Soluções Iniciais

// def solucao_inicial_caixeiro_viajante(n):
//     print("Solução Inicial Caixeiro Viajante")
    
//     w = [0 for _ in range(n)]
//     print(w)
    
//     for i in range(n):
//         w[i] = i
    
//     print(w)
//     random.shuffle(w)
    
//     return w


// def solucao_inicial_mochila(j,cmax,p):
//     print("Solução Inicial Mochila Prioritária")
//     s = [0] * j
//     y = 0

//     # Adiciona primeiro os itens com peso maior ou igual a 1
//     indices_prioritarios = [i for i in range(j) if p[i] >= 1]

//     for i in indices_prioritarios:
//         if y + p[i] < cmax:
//             s[i] = 1
//             y += p[i]

//     # Se ainda houver espaço, tenta adicionar outros itens menores
//     indices_restantes = [i for i in range(j) if p[i] < 1 and s[i] == 0]
//     random.shuffle(indices_restantes)

//     for i in indices_restantes:
//         if y + p[i] < cmax:
//             s[i] = 1
//             y += p[i]

//     return s



// # Exemplos de usos
// n = 3
// resultado = solucao_inicial_caixeiro_viajante(n)
// print("Resultado:")
// print(resultado)
// print("\n")
// j = 5
// cmax = 2.4
// p = [1.34, 0.25, 1.5, 0.2, 0.6]
// resultado = solucao_inicial_mochila(j,cmax,p)
// print("Resultado:")
// print(resultado)
// print("\n")

// # Funções Avalia
// def avaliar_caixeiro_viajante(w, matriz):
//     print("Avaliar Caixeiro Viajante")
//     custo = 0
//     for i in range(len(w) - 1):
//         custo += matriz[w[i]][w[i+1]]
//     custo += matriz[w[-1]][w[0]]
//     print(custo)
//     return custo

// def avaliar_mochila(n, s, p):
//     v = 0
//     for i in range(n):
//         v += s[i] * p[i]
//     print(v)
//     return v
