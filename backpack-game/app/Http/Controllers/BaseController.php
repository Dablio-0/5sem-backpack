<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends GenericController
{
    public function index()
    {
        return view('base.index', compact($this->getData()));
    }

    public function generate(Request $request){

        $request->validate([
            'max_capacity' => 'required|numeric',
            'itens' => 'required|array',
            'itens.*.value' => 'required|numeric',
        ]);

        $max_capacity = $request->max_capacity;
        $itens = $request->itens;

        $generatedProblem = $this->generateBackpack($max_capacity);
        $initialSolution = $this->generateInitialSolution($max_capacity, $itens);

        return [
            'generated_problem' => $generatedProblem,
            'initial_solution' => $initialSolution,
        ];
    }

    public function generateProblem($itens){

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
