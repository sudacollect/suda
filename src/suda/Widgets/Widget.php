<?php

namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\AbstractWidget;


class Widget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];
    
    
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
    
}
