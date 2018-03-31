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

        return '';
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
