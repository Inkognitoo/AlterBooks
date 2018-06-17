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
     * Вернуть html для отображения поля редактирования текущего атрибута
     *
     * @param $attribute
     * @return string
     */
    public function getHtmlEditForAttribute($attribute): string
    {
        $response = '';
        $method_name = 'getHtmlEditFor' . ucfirst(camel_case($attribute));
        if (method_exists($this, $method_name)) {
            $response = $this->$method_name();
        }

        if (\in_array($attribute, $this->datetime_edit_fields ?? [], true)) {
            $response = $this->getDefaultHtmlEditForDatetimeAttribute($attribute);
        }

        if (\in_array($attribute, $this->number_edit_fields ?? [], true)) {
            $response = $this->getDefaultHtmlEditForNumberAttribute($attribute);
        }

        if (\in_array($attribute, $this->disabled_edit_fields ?? [], true)) {
            $response = $this->getDefaultHtmlEditForDisabledAttribute($attribute);
        }

        if (\in_array($attribute, $this->area_edit_fields ?? [], true)) {
            $response = $this->getDefaultHtmlEditForAreaAttribute($attribute);
        }

        if (\in_array($attribute, $this->checkbox_edit_fields ?? [], true)) {
            $response = $this->getDefaultHtmlEditForCheckboxAttribute($attribute);
        }

        if (blank($response)) {
            $response = $this->getDefaultHtmlEditForAttribute($attribute);
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

    /**
     * Вернуть дефолтный html для отображения поля редактирования текущего атрибута
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForAttribute($attribute): string
    {
        return sprintf('
                <input type="text" 
                    class="form-control m-input" 
                    placeholder="Введите данные..."
                    id="%s"
                    name="%s"
                    value="%s">', $attribute, $attribute, $this->getAttribute($attribute));
    }

    /**
     * Вернуть дефолтный html для отображения поля редактирования datetime атрибута
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForDatetimeAttribute($attribute): string
    {
        return sprintf('
                <input type="datetime-local"
                    class="form-control m-input"
                    id="%s"
                    name="%s"
                    value="%s">', $attribute, $attribute, $this->getAttribute($attribute)->format('Y-m-d\TH:i:s'));
    }

    /**
     * Вернуть дефолтный html для отображения поля редактирования целочисленного атрибута
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForNumberAttribute($attribute): string
    {
        return sprintf('
                <input type="number" 
                    class="form-control m-input" 
                    placeholder="Введите данные..."
                    id="%s"
                    name="%s"
                    value="%s">', $attribute, $attribute, $this->getAttribute($attribute));
    }

    /**
     * Вернуть дефолтный html для отображения поля неактивных атрибутов
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForDisabledAttribute($attribute): string
    {
        return sprintf('
                <input type="text"
                    disabled 
                    class="form-control m-input" 
                    placeholder="Введите данные..."
                    id="%s"
                    name="%s"
                    value="%s">', $attribute, $attribute, $this->getAttribute($attribute));
    }

    /**
     * Вернуть дефолтный html для отображения поля редактирования многострочных данных
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForAreaAttribute($attribute): string
    {
        return sprintf('
                <textarea class="form-control m-input" 
                    id="%s"
                    name="%s" 
                    rows="8">%s</textarea>', $attribute, $attribute, $this->getAttribute($attribute));
    }

    /**
     * Вернуть дефолтный html для отображения поля редактирования true/false данных
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForCheckboxAttribute($attribute): string
    {
        $checked = $this->getAttribute($attribute) ? 'checked' : '';

        return sprintf('
                <label class="m-checkbox">
                    <input type="checkbox" id="%s" name="%s" %s>
                    <span></span>
                </label>', $attribute, $attribute, $checked);
    }
}