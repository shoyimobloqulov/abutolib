<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class GenerateQuizTemplate extends Command
{
    protected $signature = 'quiz:generate-template';
    protected $description = 'Quiz import uchun Excel shablon yaratish';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header qo'shish
        $headers = [
            'A1' => 'Fan',
            'B1' => 'Mavzu',
            'C1' => 'Savol',
            'D1' => 'Javob_A',
            'E1' => 'Javob_B',
            'F1' => 'Javob_C',
            'G1' => 'Javob_D',
            'H1' => 'Javob_E',
            'I1' => 'Javob_F',
            'J1' => 'Togri_Javob',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Misol ma'lumot qo'shish
        $exampleData = [
            [
                'Matematika',
                'Tenglamalar',
                '2x + 5 = 15 tenglamasini yeching',
                'x = 5',
                'x = 10',
                'x = 7',
                'x = 3',
                '',
                '',
                'A'
            ],
            [
                'Matematika',
                'Geometriya',
                'Uchburchakning ichki burchaklari yig\'indisi nechaga teng?',
                '180°',
                '360°',
                '90°',
                '270°',
                '',
                '',
                'A'
            ],
        ];

        $row = 2;
        foreach ($exampleData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Column kengligi
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(30);
        $sheet->getColumnDimension('J')->setWidth(15);

        // Instruction sheet
        $instructionSheet = $spreadsheet->createSheet();
        $instructionSheet->setTitle('Ko\'rsatmalar');

        $instructions = [
            ['QUIZ IMPORT QO\'LLANMASI'],
            [''],
            ['1. Fan - Mavjud fan nomini to\'liq kiriting (masalan: Matematika, Fizika)'],
            ['2. Mavzu - Mavzu nomini kiriting (agar mavjud bo\'lmasa avtomatik yaratiladi)'],
            ['3. Savol - Test savolini kiriting'],
            ['4. Javob_A, Javob_B, Javob_C, Javob_D - 4 ta javob varianti (majburiy)'],
            ['5. Javob_E, Javob_F - Qo\'shimcha javoblar (ixtiyoriy)'],
            ['6. Togri_Javob - To\'g\'ri javob harfini kiriting (A, B, C, D, E yoki F)'],
            [''],
            ['MUHIM:'],
            ['- Fan bazada mavjud bo\'lishi kerak'],
            ['- Kamida 4 ta javob bo\'lishi shart'],
            ['- To\'g\'ri javob harfi katta harf bilan yozilishi kerak'],
            ['- Barcha majburiy maydonlar to\'ldirilishi shart'],
        ];

        $row = 1;
        foreach ($instructions as $instruction) {
            $instructionSheet->setCellValue('A' . $row, $instruction[0]);
            $row++;
        }

        $instructionSheet->getColumnDimension('A')->setWidth(80);
        $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Fayl saqlash
        $directory = storage_path('app/templates');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $directory . '/quiz_import_template.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        $this->info('Excel shablon muvaffaqiyatli yaratildi: ' . $filename);
        return 0;
    }
}
