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
     * Список атрибутов (ключ/значение) в переданном порядке
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributesList(array $attributes = null): array
    {
        $attributes = $attributes ?? $this->safe_attributes ?? $this->getAttributes();
        $response = [];

        foreach ($attributes as $attribute) {
            $response[$attribute] = $this->getAttribute($attribute);
        }

        return $response;
    }

    /**
     * html для отображения текущего атрибута
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

        if (\in_array($attribute, $this->boolean_fields ?? [], true)) {
            $response = $this->getDefaultHtmlViewForBooleanAttribute($attribute);
        }

        return $response;
    }

    /**
     * html для отображения поля редактирования текущего атрибута
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

        if (\in_array($attribute, $this->file_edit_fields ?? [], true)) {
            $response = $this->getDefaultHtmlEditForFileAttribute($attribute);
        }

        if (array_key_exists($attribute, $this->list_edit_fields ?? [])) {
            $response = $this->getDefaultHtmlEditForListAttribute($attribute);
        }

        if (blank($response)) {
            $response = $this->getDefaultHtmlEditForAttribute($attribute);
        }

        return $response;
    }

    /**
     * Дефолтный html для отображения текущего атрибута
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlViewForAttribute($attribute): string
    {
        return '<p>' . $this->getAttribute($attribute) . '</p>';
    }

    /**
     * Дефолтный html для отображения булева поля
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlViewForBooleanAttribute($attribute): string
    {
        return '<p>' . ($this->getAttribute($attribute) ? 'true' : 'false') . '</p>';
    }

    /**
     * Дефолтный html для отображения поля редактирования текущего атрибута
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
     * Дефолтный html для отображения поля редактирования datetime атрибута
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
     * Дефолтный html для отображения поля редактирования целочисленного атрибута
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
     * Дефолтный html для отображения поля неактивных атрибутов
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
     * Дефолтный html для отображения поля редактирования многострочных данных
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
     * Дефолтный html для отображения поля редактирования true/false данных
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

    /**
     * Дефолтный html для отображения поля загрузки файла
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForFileAttribute($attribute): string
    {
        return sprintf('
                <input type="file"
                    class="form-control m-input" 
                    id="%s"
                    name="%s">', $attribute, $attribute);
    }

    /**
     * Дефолтный html для отображения выпадающего списка
     *
     * @param $attribute
     * @return string
     */
    protected function getDefaultHtmlEditForListAttribute($attribute): string
    {
        $options = [];

        foreach ((array)$this->list_edit_fields[$attribute] as $key => $value) {
            $selected = $this->getAttribute($attribute) === $value ? 'selected' : '';

            $options[] = sprintf('<option %s value="%s">%s</option>', $selected, $value, $key);
        }

        return sprintf('
                <select
                    class="form-control m-input" 
                    id="%s"
                    name="%s">%s</select>', $attribute, $attribute, implode(PHP_EOL, $options));
    }
}