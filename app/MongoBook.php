<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use MongoDB;
use MongoDB\BSON\ObjectID;
use Mockery\Exception;

/**
 * Класс для работы непосредственно с MongoDB
 *
 * @package App
 */
class MongoBook
{
    /** @var Book */
    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Сохраняем книгу в mongoDB
     *
     * @param UploadedFile $text Текст книги
     * @return string id книги в рамках MongoDB
     */
    public function setText(UploadedFile $text): string
    {
        if (filled($this->book->mongodb_book_id)) {
            $delete_result = MongoDB::get()
                ->alterbooks
                ->books
                ->findOne(['_id' => new ObjectID($this->book->mongodb_book_id)])
            ;
        }

        $encoding = [
            'UTF-8',
            'cp1251',
        ];
        mb_detect_order($encoding);

        $text = File::get($text);
        $text = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text));

        $collection = MongoDB::get()->alterbooks->books;
        $full_size = iconv_strlen($text);
        $page_size = 1800; //килобайты
        $pages = [];
        $start_byte = 0;
        $i = 0;
        do {
            $i++;
            $page = mb_substr($text, $start_byte, $page_size);
            $start_byte += $page_size;
            $pages[] = [
                'page' => $i,
                'text' => $page,
            ];
        } while ($start_byte < $full_size);
        $result = $collection->insertOne([
            'pages' => $pages,
        ]);

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
            return $page->text;
        }

        return null;
    }
}
