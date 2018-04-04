<?php

namespace App\Models\Book;

use App\Book;
use MongoDB;
use MongoDB\BSON\ObjectID;
use File;

/**
 * Class Txt
 * @package App\Models\Book
 */
class Txt implements BookFormat
{

    /**
     * Книга для которой производим обработку
     *
     * @var Book
     */
    protected $book;

    /**
     * Путь к тексту книги
     *
     * @var string
     */
    protected $text_path;

    /**
     * Txt constructor.
     * @param Book $book
     * @param string $text_path
     */
    public function __construct(Book $book, string $text_path)
    {
        $this->book = $book;
        $this->text_path = $text_path;
    }

    /**
     * Вернуть экземпляр книги относящийся к текущему книжному формату
     *
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Конвертация текста книги в формат AlterBooks-а
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function convert()
    {
        $this->book->is_processing = true;
        $this->book->save();

        $text = $this->toEncoding(File::get($this->text_path));
        $pages = $this->separateIntoPages($text);

        $collection = MongoDB::get()->alterbooks->books;

        $result = $collection->insertOne([
            'pages' => $pages,
        ]);

        if (filled($this->book->mongodb_book_id)) {
            $collection
                ->deleteOne(['_id' => new ObjectID($this->book->mongodb_book_id)])
            ;
        }

        $this->book->mongodb_book_id = $result->getInsertedId();
        $this->book->is_processing = false;
        $this->book->save();
    }

    /**
     * Преобразовать тектовый файл в кодировку UTF-8 и экронировать опасные символы
     *
     * @param string $raw_text
     * @return string
     */
    private function toEncoding(string $raw_text): string
    {
        $encoding = $this->detectEncoding($raw_text);

        return htmlspecialchars(mb_convert_encoding($raw_text, 'UTF-8', $encoding));
    }

    /**
     * Узнать кодировку текущего текста
     *
     * @param string $text
     * @return string
     */
    private function detectEncoding(string $text): string
    {
        $temp = tmpfile();
        fwrite($temp, $text);
        $path = stream_get_meta_data($temp)['uri'];

        //Для максимально точного определения кодировки испльзуем python скрипт
        $command = escapeshellcmd(base_path('python/encoding.py') . ' ' . $path);
        $encoding = trim(shell_exec($command));

        fclose($temp);

        return $encoding;
    }

    /**
     * Разбиваем текст на страницы
     *
     * @param string $text
     * @return array
     */
    private function separateIntoPages(string $text): array
    {
        $page_size = 1800; //символы
        $pages = [];
        $start_symbol_number = 0;
        $i = 0;
        do {
            $i++;
            $current_page_size = $page_size;

            list($page, $current_page_size) = $this->extractPage($text, $start_symbol_number, $current_page_size);
            $start_symbol_number += $current_page_size;
            if ($page == '-') {
                break;
            }
            $pages[] = [
                'page' => $i,
                'text' => $page,
            ];
        } while (true);

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