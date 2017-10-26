<?php

namespace App;

use Exception;
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

    /** @var string id книги в рамках mongoDB */
    private $mongodb_book_id;

    /** @var integer Количество страниц в книге */
    private $page_count;

    /** @var array Поля доступные через геттер */
    private $accessible_fields = ['mongodb_book_id', 'page_count'];

    /**
     * MongoBook constructor.
     * @param Book $book
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Геттер для получения приватных свойств
     *
     * @param $property
     * @return mixed
     * @throws Exception
     */
    public function __get($property) {
        if (property_exists($this, $property) && in_array($property, $this->accessible_fields, true)) {
            return $this->$property;
        }

        throw new Exception('Undefined property ' . $property . ' referenced.');
    }

    /**
     * Сохраняем книгу в mongoDB
     *
     * @param UploadedFile $text_file Текст книги
     * @return string id книги в рамках MongoDB
     */
    public function setText(UploadedFile $text_file): string
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

        $this->mongodb_book_id = $result->getInsertedId();

        return $result->getInsertedId();
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
        $start_symbol = 0;
        $i = 0;
        do {
            $i++;
            $page = mb_substr($text, $start_symbol, $page_size);
            $start_symbol += $page_size;
            $pages[] = [
                'page' => $i,
                'text' => $page,
            ];
        } while ($start_symbol < $full_size);

        $this->page_count = $i;

        return $pages;
    }
}
