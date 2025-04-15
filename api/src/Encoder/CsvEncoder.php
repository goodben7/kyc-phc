<?php

namespace App\Encoder;

use League\Csv\Writer;
use SplTempFileObject;
use App\Exception\NotAcceptableDataFormatException;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class CsvEncoder implements EncoderInterface
{
    public const FORMAT = 'csv';

    public function encode(mixed $data, string $format, array $context = []): string
    {
        if (!isset($data['datasets'])) {
            throw new NotAcceptableDataFormatException('the underlying object is not a valid dataset.');
        }

        /** @var iterable $datasets */
        $datasets = $data['datasets'];

        $csv = Writer::createFromFileObject(new SplTempFileObject());

        /** @var array $row */
        foreach ($datasets as $row) {
            if (is_iterable($row)) {
                $csv->insertOne($row);
            }
            else{
                $csv->insertOne([$row]);
            }
        }

        return $csv->toString(); 
    }

    public function supportsEncoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}