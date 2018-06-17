<?php

namespace App\Traits\Admin;

/**
 * Трейт для добавления методов работы с атрибутами в админке
 *
 * Trait Attributes
 * @package App\Admin\Traits
 */
trait Attributes {

    /**
     * Вернуть список атрибутов (ключ/значение) в переданном порядке
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributesList(array $attributes): array
    {
        $response = [];

        foreach ($attributes as $attribute) {
            $response[$attribute] = $this->getAttribute($attribute);
        }

        return $response;
    }

    /**
     * Вернуть html для отображения текущего атрибута
     *
     * @param $attribute
     * @return string
     */
    public function getHtmlViewForAttribute($attribute): string
    {
        $method_name = 'getHtmlViewFor' . ucfirst(camel_case($attribute));
        if (method_exists($this, $method_name)) {
            $response = $this->$method_name();
        } else {
            $response = $this->getDefaultHtmlViewForAttribute($attribute);
        }

        return $response;
    }

    /**
     * Вернуть дефолтный html для отображения текущего атрибута
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlViewForAttribute($attribute): string
    {
        return '<p>' . $this->getAttribute($attribute) . '</p>';
    }
}