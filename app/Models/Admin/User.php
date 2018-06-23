<?php

namespace App\Models\Admin;

use App\Models\User as BaseUser;
use App\Traits\Admin\Attributes;

/**
 * Class User
 *
 * @package App\Models\Admin
 */
class User extends BaseUser
{
    use Attributes;

    /**
     * @var array $datetime_edit_fields Список атрибутов, которым нужно отображать поля для редактирования даты
     */
    protected $datetime_edit_fields = ['updated_at', 'created_at', 'birthday_date'];

    /**
     * @var array $number_edit_fields Список атрибутов, которым нужно отображать поля редактирования целочисленных данных
     */
    protected $number_edit_fields = ['id'];

    /**
     * @var array $disabled_edit_fields Список атрибутов, которым нужно отображать неактивные поля редактирования
     */
    protected $disabled_edit_fields = ['password', 'remember_token', 'api_token'];

    /**
     * @var array $area_edit_fields Список атрибутов, которым нужно отображать широкие поля редактирования
     */
    protected $area_edit_fields = ['about'];

    /**
     * @var array $checkbox_edit_fields Список атрибутов, которым нужно отображать checkbox-ы
     */
    protected $checkbox_edit_fields = ['is_admin'];

    /**
     * @var array $file_edit_fields Список атрибутов, которым нужно отображать поля для ввода файлов
     */
    protected $file_edit_fields = ['avatar'];

    /**
     * @var array $list_edit_fields Список атрибутов, которым нужно отображать выпадающий список
     */
    protected $list_edit_fields = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->list_edit_fields = [
            'gender' => [
                'Мужской' => self::GENDER_MALE,
                'Женский' => self::GENDER_FEMALE,
                'Не указан' => self::GENDER_NOT_INDICATED,
            ],
            'timezone' => array_flip(config('app.timezones'))
        ];
    }

    /**
     * Вернуть html для отображения гендера
     *
     * @return string
     */
    protected function getHtmlViewForGender(): string
    {
        $gender_string = '';

        switch ($this->gender) {
            case self::GENDER_MALE:
                $gender_string = 'Мужской';
                break;
            case self::GENDER_FEMALE:
                $gender_string = 'Женский';
                break;
            case self::GENDER_NOT_INDICATED:
                $gender_string = 'Не выбран';
                break;
        }

        return sprintf('<p>%s (%s)</p>', $this->gender, $gender_string);
    }

    /**
     * Вернуть html для отображения аватары
     *
     * @return string
     */
    protected function getHtmlViewForAvatar(): string
    {
        return sprintf('<p>%s</p><img src="%s">', $this->avatar, $this->avatar_url);
    }

    /**
     * Вернуть html для отображения атрибута is_admin
     *
     * @return string
     */
    protected function getHtmlViewForIsAdmin(): string
    {
        return '<p>' . ($this->is_admin ? 'true' : 'false') . '</p>';
    }

    /**
     * Вернуть html для отображения поля редактирования email
     *
     * @return string
     */
    protected function getHtmlEditForEmail(): string
    {
        $attribute = 'email';

        return sprintf('
                <input type="email" 
                    class="form-control m-input" 
                    placeholder="Введите данные..."
                    id="%s"
                    name="%s"
                    value="%s">', $attribute, $attribute, $this->getAttribute($attribute));
    }

}