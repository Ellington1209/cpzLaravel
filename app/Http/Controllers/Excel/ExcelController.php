<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Ods;

class ExcelController extends Controller
{
    private $maxColumn = 'B';


    
    public function import($filePath, $mimeType, $sheetNumber = 0)
    {
        // Define o leitor com base no MimeType
        $reader = null;
        if ($mimeType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $reader = new Xlsx();
        } elseif ($mimeType === 'application/vnd.oasis.opendocument.spreadsheet') {
            $reader = new Ods();
        } else {
            throw new \Exception('Formato de arquivo não suportado. Utilize XLSX ou ODS.');
        }
    
        // Configura o leitor para ignorar células vazias
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
    
        // Carrega a planilha
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getSheet($sheetNumber);
    
        // Identifica dinamicamente a última coluna preenchida
        $this->maxColumn = $worksheet->getHighestColumn();
    
        $rows = [];
        $batchSize = 1000;
        $totalRows = $this->getLastNonEmptyRow($worksheet);
    
        for ($startRow = 1; $startRow <= $totalRows; $startRow += $batchSize) {
            $endRow = min($startRow + $batchSize - 1, $totalRows);
            $rows = array_merge($rows, $this->processBatch($worksheet, $startRow, $endRow));
        }
    
        return $rows;
    }
    

    private function getLastNonEmptyRow($worksheet)
    {
        $highestRow = $worksheet->getHighestRow();

        for ($row = $highestRow; $row >= 1; $row--) {
            for ($col = 'A'; $col <= $this->maxColumn; $col++) {
                if ($worksheet->cellExists($col . $row) && $worksheet->getCell($col . $row)->getCalculatedValue() !== null) {
                    return $row;
                }
            }
        }
        return 1;
    }

    private function processBatch($worksheet, $startRow, $endRow)
    {
        $rows = [];
        for ($rowIndex = $startRow; $rowIndex <= $endRow; $rowIndex++) {
            $rowData = [];
            $row = $worksheet->getRowIterator($rowIndex)->current();

            foreach ($row->getCellIterator('A', $this->maxColumn) as $cell) {
                $rowData[] = $cell->getCalculatedValue();
            }

            $rows[] = $rowData;
        }

        return $rows;
    }
}
