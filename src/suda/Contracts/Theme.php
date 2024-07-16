<?php
/**
 * Base.php
 * description
 * date 2018-04-23 16:13:54
 * author suda <dev@gtd.xyz>
 * @copyright Suda. All Rights Reserved.
 */

namespace Gtd\Suda\Contracts;


use Exception;

interface Theme
{
    
    public function install();
    
    public function unInstall();
    
    public function checkInstalled();
    
    public function refresh();
    
    
    public function getInfo();
}
