<?php

namespace App\Rules;

use DB;
use Illuminate\Contracts\Validation\Rule;

class CaseInsensitiveUnique implements Rule
{
    protected $attribute;

    protected $table;

    /**
     * The ID that should be ignored.
     *
     * @var mixed
     */
    protected $ignore;

    /**
     * The name of the ID column.
     *
     * @var string
     */
    protected $idColumn = 'id';

    /**
     * The text of error.
     *
     * @var string
     */
    protected $message = '';

    /**
     * Create a new rule instance.
     *
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Ignore the given ID during the unique check.
     *
     * @param  mixed  $id
     * @param  string  $idColumn
     * @return $this
     */
    public function ignore($id, $idColumn = 'id')
    {
        $this->ignore = $id;
        $this->idColumn = $idColumn;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        $queue = DB::table($this->table)->where(DB::raw('LOWER( ' . $attribute . ')'), '=', mb_strtolower($value));
        if (filled($this->ignore)) {
            $queue->where($this->idColumn, '<>', $this->ignore);
        }

        return !$queue->exists();
    }

    /**
     * Get custom error message.
     *
     * @param  string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (filled($this->message)) {
            return __($this->message, ['attribute' => $this->attribute]);
        }

        return trans('validation.unique', ['attribute' => $this->attribute]);
    }
}
