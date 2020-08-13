<?php

namespace App;

use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Symfony\Component\HttpFoundation\File\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Класс для чтения из файлов разных расширений.
 */
class FileReader
{
    public const  EXEL_EXTENSION            = 'xlsx';
    private const EXEL_EXTENTION_PHP_OFFICE = 'Xlsx';

    private string $extension;
    private File $file;

    public function __construct(File $filePath, string $extension)
    {
        $this->extension = $extension;
        $this->file      = $filePath;
        $this->validateExtension();
    }

    /**
     * Возравщает контент файла массивом.
     */
    public function getContent(): array
    {
        $result = [];
        if ($this->extension === self::EXEL_EXTENSION) {
            $reader = IOFactory::createReader(self::EXEL_EXTENTION_PHP_OFFICE);
            $spreadsheet = $reader->load($this->file->getRealPath());
            foreach ($spreadsheet->getAllSheets() as $list) {
                $result[] = $list->toArray();
            }
        }
        return $result;
    }

    /**
     * Проверяет соответсвие расширения файла переданному значению в конструктор.
     */
    private function validateExtension(): void
    {
        $fileExtension = $this->file->getClientOriginalExtension();
        if ($fileExtension !== $this->extension) {
            throw new ExtensionFileException("Ожидаемое расширение: $this->extension, фактическое: $fileExtension");
        }
    }
}
