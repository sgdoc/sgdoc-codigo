<?php

namespace grid;

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 * @version 0.0.0.
 */
interface Grideable
{

    /**
     * @return void
     * @param GridAbstract $grid
     */
    public function filtering (GridAbstract $grid);

    /**
     * @return void
     * @param GridAbstract $grid
     */
    public function result (GridAbstract $grid);

    /**
     * @return void
     * @param GridAbstract $grid
     */
    public function totalRecords (GridAbstract $grid);

    /**
     * @return void
     * @param GridAbstract $grid
     */
    public function totalDisplayRecords (GridAbstract $grid);
}