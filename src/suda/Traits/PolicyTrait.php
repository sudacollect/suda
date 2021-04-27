<?php

namespace Gtd\Suda\Traits;

use Gtd\Suda\Cache as SudaCache;

trait PolicyTrait
{

    //增加policy
    public function addPolicy($model,$policy){

        

    }

    //保存policies
    protected function savePolicies($policies){
        SudaCache::init()->forever('suda_policies',$policies);
    }
    protected function getPolicies(){
        return $suda_policies = SudaCache::init()->get('suda_policies',[]);
    }

}
