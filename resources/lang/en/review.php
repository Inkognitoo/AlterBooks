<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы рецензии
    |--------------------------------------------------------------------------
    |
    | Следующие языковые ресурсы используются с сущностью рецензии для
    | различных сообщений которые мы должны вывести пользователю на экран.
    |
    */

    'Рецензии' => 'Reviews',
    'Тут пока нет ни одной рецензии. Оставьте отзыв первым!' => 'There no any reviews yet. Be first!',
    'Новая рецензия' => 'New review',
    'Оценка' => 'Estimate',
    'Текст' => 'Text',
    'Оценка: :estimate' => 'Estimate: :estimate',

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы рецензии для кнопок
    |--------------------------------------------------------------------------
    |
    | Различные тексты кнопок, связанные с сущностью рецензии
    |
    */

    'button' => [
        'Добавить рецензию' => 'Add review',
        'Отправить' => 'Send',
        'Удалить' => 'Delete',
        'Закрыть' => 'Close',
    ],

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы рецензии для модальных окон
    |--------------------------------------------------------------------------
    |
    | Различные тексты модальных окон, связанные с сущностью рецензии
    |
    */

    'modal' => [
        'Подтвердите удаление' => 'Approve delete',
        'Вы уверены, что хотите удалить рецензию к книге <strong>:book</strong>?' => 'Do you really want delete review for <strong>:book</strong> book?'
    ],

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы рецензии для api сообщений
    |--------------------------------------------------------------------------
    |
    | Различные тексты сообщений api, связанные с сущностью рецензии
    |
    */

    'api' => [
        'Рецензия была успешно удалена' => 'Review was successfully deleted',
        'Рецензии не существует' => 'Review not found',
        'Рецензии для текущей книги не существует' => 'Review for current book not found',
    ],

];