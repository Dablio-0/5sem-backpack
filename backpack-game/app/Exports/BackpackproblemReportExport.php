<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
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
    
    public function __construct(Collection $data)
    {
        $this->data = $data;
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
    public function headings(): array {
        return [

        ];
    }
    
    /**
    * @var Collection $data
    */
    public function map($data): array
    {
        return [
            
        ];
    }
    
    /**
    * @return Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet) {

        
    }
}
