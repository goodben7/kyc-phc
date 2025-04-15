<?php

namespace App\Encoder;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exception\NotAcceptableDataFormatException;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class XlsxEncoder implements EncoderInterface
{
    public const FORMAT = 'xlsx';

    public function encode(mixed $data, string $format, array $context = []): string
    {
        if (!isset($data['datasets'])) {
            throw new NotAcceptableDataFormatException('the underlying object is not a valid dataset.');
        }

        /** @var iterable $datasets */
        $datasets = $data['datasets'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $row = 1;
        /** @var array $rowData */
        foreach ($datasets as $rowData) {
            if (is_iterable($rowData)) {
                $sheet->fromArray($rowData, null, 'A' . $row++);
            } else {
                $sheet->setCellValue('A' . $row++, $rowData);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $xlsx = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer->save($xlsx);

        return file_get_contents($xlsx);
    }

    public function supportsEncoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}