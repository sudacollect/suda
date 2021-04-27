<?php

namespace Gtd\Suda\Contracts;

interface Extension
{
    
    public function refresh();
    
    public function getInfo();
    
    public function getExtensionMenu();
    
}