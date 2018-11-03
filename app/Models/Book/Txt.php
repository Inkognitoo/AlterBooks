<?php

namespace App\Models\Book;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Page;
use File;

/**
 * Class Txt
 * @package App\Models\Book
 */
class Txt implements BookFormat
{
    protected const PAGE_SIZE = 10000; //символы

    /**
     * Паттерн для поиска глав
     */
    protected const CHAPTER_PATTERN = '/\n([гГ][лЛ][аА][вВ][аА].*)\n/mu';

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
    public function getBook(): Book
    {
        return $this->book;
    }

    /**
     * Конвертация текста книги в формат AlterBooks-а
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function convert(): void
    {
        $this->book->is_processing = true;
        $this->book->save();

        $text = $this->toEncoding(File::get($this->text_path));

        $this->book->purgeText();

        $chapter = Chapter::create([
            'book_id' => $this->book->id,
            'number' => 0,
            'name' => 'Пролог'
        ]);

        $page_number = 0;
        $chapter_number = 0;

        //TODO: переписать на что-то более изящное
        do {
            $page = $this->getPage($text);
            $page_number++;
            $chapter_id = $chapter->id;

            //TODO: глав может быть несколько. Сделать рекурсией
            if ($this->haveChapterIn($page)) {
                $page = $this->getTextBeforeChapter($page);

                Page::create([
                    'book_id' => $this->book->id,
                    'chapter_id' => $chapter_id,
                    'number' => $page_number++,
                    'text' => $page
                ]);
                $text = mb_substr($text, mb_strlen($page) - 1);

                $chapter_name = $this->getChapter($text);
                $chapter = Chapter::create([
                    'book_id' => $this->book->id,
                    'number' => ++$chapter_number,
                    'name' => $chapter_name
                ]);
                $chapter_id = $chapter->id;

                $text = mb_substr($text, mb_strpos($text, $chapter_name) + mb_strlen($chapter_name));
                $page = $this->getPage($text);
            }

            Page::create([
                'book_id' => $this->book->id,
                'chapter_id' => $chapter_id,
                'number' => $page_number,
                'text' => $page
            ]);

            $text = mb_substr($text, mb_strlen($page) - 1);

        } while (filled($text));

        $this->deleteTmpFile();


        $this->book->is_processing = false;
        $this->book->save();
    }

    /**
     * Преобразовать текстовый файл в кодировку UTF-8 и экранировать опасные символы
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

        //Для максимально точного определения кодировки используем python скрипт
        $command = escapeshellcmd(base_path('python/encoding.py') . ' ' . $path);
        $encoding = trim(shell_exec($command));

        fclose($temp);

        return $encoding;
    }

    /**
     * Получить отдельную страницу из текста
     *
     * @param string $text
     * @param int $size
     * @return string
     */
    private function getPage(string $text, int $size = self::PAGE_SIZE): string
    {
        return mb_substr($text, 0, $size);
    }

    /**
     * Получить текст до первой главы
     *
     * @param string $text
     * @return null|string
     */
    private function getTextBeforeChapter(string $text): string
    {
        preg_match(self::CHAPTER_PATTERN, $text, $chapter, PREG_OFFSET_CAPTURE);

        if (empty($text)) {
            return $text;
        }

        //Это просто позор, у меня слов нет. PHP за 20 с лишним лет так и не научился по нормальному работать с UTF-8
        //по этому расстояние в символах до начала главы получаем вот так.
        $size = (int)mb_strlen(substr($text, 0, $chapter[0][1]));

        return $this->getPage($text, $size);
    }

    private function getChapter(string $text): string
    {
        preg_match(self::CHAPTER_PATTERN, $text, $chapter, PREG_OFFSET_CAPTURE);

        if (empty($chapter)) {
            throw new \RuntimeException('Chapter not found');
        }

        return $chapter[1][0];
    }

    /**
     * Проверить, содержит ли текст признак главы
     *
     * @param string $page
     * @return bool
     */
    private function haveChapterIn(string $page): bool
    {
        preg_match(self::CHAPTER_PATTERN, $page, $chapter);

        return filled($chapter);
    }

    /**
     * Удалить временный файл с текстом
     */
    private function deleteTmpFile(): void
    {
        File::delete($this->text_path);
    }
}