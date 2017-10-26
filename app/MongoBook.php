<?php

namespace App;

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
     * @return null|string
     */
    public function getPage(int $page_number)
    {
        if (blank($this->book->mongodb_book_id)) {
            return null;
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
            return null;
        }

        foreach ($document->pages as $page) {
            return preg_replace("/[\n\r]+/s","<br/>", $page->text);
        }

        return null;
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
}
