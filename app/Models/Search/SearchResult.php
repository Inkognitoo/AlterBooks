<?php

namespace App\Models\Search;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class SearchResult
 * @package App\Models\Search
 *
 * @property int $per_page
 * @property int $page_count
 * @property int $current_page
 * @property array $filtered
 * @property array $sorted
 * @property Collection $items
 * @property int $total
 */
class SearchResult extends Model
{
    protected $per_page;

    protected $page_count;

    protected $current_page;

    protected $filtered;

    protected $sorted;

    protected $items;

    protected $total;

    /**
     * @return mixed
     */
    public function getPerPage()
    {
        return $this->per_page;
    }

    /**
     * @param mixed $per_page
     */
    public function setPerPage($per_page): void
    {
        $this->per_page = $per_page;
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->page_count;
    }

    /**
     * @param mixed $page_count
     */
    public function setPageCount($page_count): void
    {
        $this->page_count = $page_count;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * @param mixed $current_page
     */
    public function setCurrentPage($current_page): void
    {
        $this->current_page = $current_page;
    }

    /**
     * @return mixed
     */
    public function getFiltered()
    {
        return $this->filtered;
    }

    /**
     * @param mixed $filtered
     */
    public function setFiltered($filtered): void
    {
        $this->filtered = $filtered;
    }

    /**
     * @return mixed
     */
    public function getSorted()
    {
        return $this->sorted;
    }

    /**
     * @param mixed $sorted
     */
    public function setSorted($sorted): void
    {
        $this->sorted = $sorted;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items): void
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }

}