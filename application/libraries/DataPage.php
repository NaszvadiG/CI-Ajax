<?php

class DataPage {

    /**
     * @var int
     */
    public $pageCount;

    /**
     * @var int
     */
    public $pageSize;

    /**
     * @var int
     */
    public $currentPage;

    /**
     * @var int
     */
    public $itemCount;

    /**
     * @var object[]
     */
    public $data;
    public $statistics;
    public $dataStats;

    public function DataPage($pageCount = null, $pageSize = null, $currentPage = null, $itemCount = null, $data = null, $statistics = null, $dataStats = null) {
        $this->pageCount = $pageCount;
        $this->pageSize = $pageSize;
        $this->currentPage = $currentPage;
        $this->itemCount = $itemCount;
        $this->data = $data;
        $this->dataStats = $dataStats;
        $this->statistics = $statistics;
    }

}