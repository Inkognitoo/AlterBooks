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
     * Вернуть html для отображения гендера
     *
     * @return string
     */
    protected function getHtmlViewForGender(): string
    {
        $gender = $this->getAttribute('gender');
        $gender_string = '';

        switch ($gender) {
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

        return sprintf('<p>%s (%s)</p>', $gender, $gender_string);

    }

    /**
     * Вернуть html для отображения аватары
     *
     * @return string
     */
    protected function getHtmlViewForAvatar(): string
    {
        return sprintf('<p>%s</p><img src="%s">', $this->getAttribute('avatar'), $this->avatar_url);
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
}