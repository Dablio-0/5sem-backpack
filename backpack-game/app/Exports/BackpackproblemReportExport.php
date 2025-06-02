<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BackpackproblemReportExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithCustomStartCell
{
    
    protected $data;
    protected $allMethods = [];

    public function __construct(Collection $data)
    {
        $this->data = $data;

        // Descobre todos os métodos utilizados nos dados
        $this->allMethods = collect($data)->flatMap(function ($item) {
            return array_keys($item['results'] ?? []);
        })->unique()->values()->all();
    }

    
    public function startCell(): string {
        return 'A3';
    }

    public function columnWidths(): array {
        return [
            
        ];
    }

    /**
    * Cabeçalhos das Colunas
    */
    public function headings(): array
    {
        $staticHeadings = [
            'Capacidade Máx.',
            'Qtd. Itens',
            'Problema Gerado',
            'Itens',
            'Solução Inicial',
            'Avaliação Inicial',
            'Método',
        ];

        $dynamicHeadings = [];

        foreach ($this->allMethods as $method) {
            $dynamicHeadings[] = "[$method] Solução Final";
            $dynamicHeadings[] = "[$method] Avaliação Final";
            $dynamicHeadings[] = "[$method] Sucessores Gerados";
        }

        return array_merge($staticHeadings, $dynamicHeadings);
    }

    /**
    * @var Collection $data
    */
    public function map($data): array
    {
        $row = [
            $data['max_capacity'],
            $data['item_count'],
            implode(', ', $data['generated_problem']),
            implode(', ', $data['items']),
            implode(', ', $data['primary_solution']),
            $data['primary_evaluation'],
            $data['method'],
        ];

        foreach ($this->allMethods as $method) {
            $methodData = $data['results'][$method] ?? null;

            if ($methodData) {
                $row[] = implode(', ', $methodData['final_solution'] ?? []);
                $row[] = $methodData['final_evaluation'] ?? 'N/A';
                $row[] = collect($methodData['successors_generated'] ?? [])->map(function ($s) {
                    return '[' . implode(', ', $s['solution']) . '] = ' . $s['evaluation'];
                })->implode(' | ');
            } else {
                $row[] = 'N/A';
                $row[] = 'N/A';
                $row[] = 'N/A';
            }
        }

        return $row;
    }

    
    /**
    * @return Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = chr(65 + count($this->headings()) - 1); // A, B, ..., Z, AA...

        $sheet->getStyle("A3:{$lastColumn}3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A3:{$lastColumn}3")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A3:{$lastColumn}3")->getFont()->setBold(true);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle("A4:{$lastColumn}100")->getAlignment()->setWrapText(true);
    }

}
