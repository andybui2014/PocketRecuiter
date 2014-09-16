<?php

/**
 * Class for sliced data pagging
 * @author Alexander Cheshchevik
 *
 */
class PR_Paginator_Sliced_Array extends Zend_Paginator_Adapter_Array
{

    /**
     * Constructor.
     *
     * @param array $array Array to paginate
     */
    public function __construct(array $array, $count)
    {
        $this->_array = $array;
        $this->_count = $count;
    }

    /**
     * Get items for pagginator support dashboard easy integration.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->_array;
    }

}