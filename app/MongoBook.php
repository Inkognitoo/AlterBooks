<?php

namespace App;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use MongoDB;
use MongoDB\BSON\ObjectID;

/**
 * Класс для работы непосредственно с MongoDB
 *
 * @package App
 */
class MongoBook
{
    /** @var Book Книга */
    private $book;

    /**
     * MongoBook constructor.
     * @param Book $book
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Сохраняем книгу в mongoDB
     *
     * @param UploadedFile $text_file Текст книги
     * @return void
     * @throws FileNotFoundException
     */
    public function setText(UploadedFile $text_file)
    {
        $collection = MongoDB::get()->alterbooks->books;

        if (filled($this->book->mongodb_book_id)) {
            $collection
                ->deleteOne(['_id' => new ObjectID($this->book->mongodb_book_id)])
            ;
        }

        $raw_text = File::get($text_file);
        $text = $this->toEncoding($raw_text);

        $pages = $this->separateIntoPages($text);
        $result = $collection->insertOne([
            'pages' => $pages,
        ]);

        $this->book->mongodb_book_id = $result->getInsertedId();
    }

    /**
     * Получить конкретную страницу книги
     *
     * @param int $page_number Номер запрашиваемой страницы
     * @param bool $format Нужно ли форматировать возвращаемый текст в html
     * @return string
     * @throws Exception
     */
    public function getPage(int $page_number, bool $format = true): string
    {
        if (blank($this->book->mongodb_book_id)) {
            throw new Exception('For getting page, book\'s text must be present');
        }

        $document = MongoDB::get()->alterbooks->books->findOne(
            [
                '_id' => new ObjectID($this->book->mongodb_book_id),
                'pages' => [
                    '$elemMatch' => [
                        'page' => $page_number
                    ]
                ]
            ],
            [
                'projection' => [
                    'pages' => [
                        '$elemMatch' => [
                            'page' => $page_number
                        ]
                    ],
                ]
            ]
        );

        if (blank($document)) {
            throw new Exception('Book\'s text not found in mongoDB');
        }

        foreach ($document->pages as $page) {
            return $format ? $this->format($page->text) : $page->text;
        }
    }

    /**
     * Обновить конкретную страницу книги
     *
     * @param int $page_number Номер редактируемой страницы
     * @param string $text Новый текст страницы
     * @return void
     * @throws Exception
     */
    public function editPage(int $page_number, string $text)
    {
        if (blank($this->book->mongodb_book_id)) {
            throw new Exception('For updating page, book\'s text must be present');
        }

        MongoDB::get()->alterbooks->books->updateOne(
            [
                '_id' => new ObjectID($this->book->mongodb_book_id),
                'pages' => [
                    '$elemMatch' => [
                        'page' => $page_number
                    ]
                ]
            ],
            [
                '$set' => [
                    'pages.$.text' => $text
                ]
            ]
        );
    }

    /**
     * Преобразуем тектовый файл в кодировку UTF-8 и экранируем опасные символы
     *
     * @param string $raw_text
     * @return string
     */
    private function toEncoding(string $raw_text): string
    {
        $encoding = [
            'UTF-8',
            'cp1251',
        ];
        mb_detect_order($encoding);

        return htmlspecialchars(mb_convert_encoding($raw_text, 'UTF-8', mb_detect_encoding($raw_text)));
    }

    /**
     * Разбиваем текст на страницы
     *
     * @param string $text
     * @return array
     */
    private function separateIntoPages(string $text): array
    {
        $full_size = iconv_strlen($text);
        $page_size = 1800; //символы
        $pages = [];
        $start_symbol_number = 0;
        $i = 0;
        do {
            $i++;
            $current_page_size = $page_size;

            list($page, $current_page_size) = $this->extractPage($text, $start_symbol_number, $current_page_size);
            $start_symbol_number += $current_page_size;
            $pages[] = [
                'page' => $i,
                'text' => $page,
            ];
        } while ($start_symbol_number < $full_size);

        $this->book->page_count = $i;

        return $pages;
    }

    /**
     * Извлечь отдельную страницу из текста
     *
     * @param string $text
     * @param int $start_symbol_number
     * @param int $page_size
     * @return array
     */
    private function extractPage(string $text, int $start_symbol_number, int $page_size): array
    {
        $attempt_count = 5;
        for ($i = 0; $i < $attempt_count; $i++) {
            $divide_symbol = mb_substr($text, $start_symbol_number + $page_size + $i, 1);
            if ($divide_symbol == ' ') {
                $page = mb_substr($text, $start_symbol_number, $page_size + $i);
                return [$page, $page_size + $i];
            }
        }

        $page = mb_substr($text, $start_symbol_number, $page_size - $attempt_count);
        return [($page . '-'), $page_size - $attempt_count];
    }

    /**
     * Форматируем страницу в html код
     *
     * @param string $text
     * @return string
     */
    private function format(string $text): string
    {
        return preg_replace("/(\r\n)/","<br/>", $text);
    }
}
