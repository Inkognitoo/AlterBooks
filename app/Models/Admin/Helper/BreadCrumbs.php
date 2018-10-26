<?php

namespace App\Models\Admin\Helper;

/**
 * Класс-хелпер для генерации хлебных крошек в админке
 *
 * Class BreadCrumbs
 * @package App\Models\Admin\Helper
 */
class BreadCrumbs
{
    /**
     * Сгенерировать html для хлебных крошек в админке
     *
     * @param array $attributes
     * @return string
     */
    public static function create(array $attributes): string
    {
        $content = '';
        $last_attribute = array_pop($attributes);

        foreach ($attributes as $attribute) {
            $title = $attribute[0];
            $link = $attribute[1] ?? null;
            $content .= self::generateHtml($title, $link);
        }

        $title = $last_attribute[0];
        $link = $last_attribute[1] ?? null;
        $content .= self::generateSingleCrumb($title, $link);

        return sprintf('
            <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
	            %s
            </ul>', $content);
    }

    /**
     * Сгенерировать html для конкретной хлебной крошки
     *
     * @param string $title
     * @param null $link
     * @return string
     */
    protected static function generateHtml(string $title, $link = null): string
    {
        $content = self::generateSingleCrumb($title, $link);

        return sprintf('
            <li class="m-nav__item">
		        %s
            </li>
            <li class="m-nav__separator">
                -
            </li>', $content);
    }

    /**
     * Сгенерировать отдельную хлебную крошку
     *
     * @param string $title
     * @param null $link
     * @return string
     */
    protected static function generateSingleCrumb(string $title, $link = null): string
    {
        $text = sprintf('
            <span class="m-nav__link-text">
                %s
            </span>', $title);
        if (filled($link)) {
            $content = sprintf('
                <a href="%s" class="m-nav__link">
                    %s
                </a>
            ', $link, $text);
        } else {
            $content = sprintf('
                <span class="m-nav__item">
                    %s
                </span>
            ', $text);
        }

        return $content;
    }
}