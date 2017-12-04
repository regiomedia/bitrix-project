<?php

namespace Local\Util;

/**
 * Class Assets
 * @package Local\Util
 *
 * Хелпер для работы с манифест-файлом Вебпака
 */
class Assets
{
    /**
     * @var string
     */
    private $base;
    /**
     * @var string
     */
    private $manifestFile;
    /**
     * @var array
     */
    private $manifest;

    /**
     * Assets constructor.
     *
     * @param string $base         Расположение директории ассетов относительно
     *                             DOCUMENT_ROOT.
     * @param string $manifestFile Имя манифест-файла.
     * @throws \Exception Стандартное исключение.
     */
    public function __construct(string $base = 'local/build/', string $manifestFile = 'manifest.json')
    {
        $this->base = $base;
        $this->manifestFile = $manifestFile;

        $this->loadManifest();
    }

    /**
     * Генерирует массив ассетов на основе файла манифеста.
     *
     * @return void
     * @throws \Exception Стандартное исключение.
     */
    private function loadManifest()
    {
        $manifest = json_decode(file_get_contents(
            $_SERVER['DOCUMENT_ROOT'] . '/' . $this->base . $this->manifestFile
        ), true);

        if (! (bool) $manifest) {
            throw new \Exception('Manifest file not found!');
        }

        $this->manifest = $manifest;
    }

    /**
     * Получить путь до файла-ассета по его имени
     *
     * @param string $entryName Имя файла-ассета.
     * @return string Путь до ассета.
     * @throws \Exception Стандартное исключение.
     */
    public function getEntry(string $entryName)
    {
        $entryPath = $this->base . $entryName;
        $entry = $this->manifest[ $entryPath ];

        if (is_null($entry)) {
            throw new \Exception('Entry `' . $entryPath .'` not found in manifest file!');
        }

        return $entry;
    }
}
