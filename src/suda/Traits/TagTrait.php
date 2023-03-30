<?php

namespace Gtd\Suda\Traits;

use Response;
use Illuminate\Http\Request;
use Gtd\Suda\Models\Taxable;
use Gtd\Suda\Models\Taxonomy;
use Gtd\Suda\Models\Term;
use Gtd\Suda\TaxableUtils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

trait TagTrait
{
    // public $taxonomy_name = '';
    // public $multiple_level = false;
    
    //分类列表
    public function getList(Request $request,$view='list')
    {
        if(!$this->taxonomy_name){
            return redirect('error');
        }
        
        $taxonomyObj = new Taxonomy;
        $tags = $taxonomyObj->listAll($this->taxonomy_name);

        $this->setData('tags',$tags);
        
        $this->setData('tab_view','active');

        $this->getActions();
        return $this->display($this->getViews('list'));
    }

    public function deletedList(Request $request,$view='list')
    {
        $this->title('标签');
        $this->setMenu('article','article_tag');
        
        $taxonomyObj = new Taxonomy;
        $tags = $taxonomyObj->onlyTrashed()->where('taxonomy',$this->taxonomy_name)->with('term')->get();
        
        $this->setData('tags',$tags);

        $this->setData('tab_view','deleted');
        
        $this->getActions();
        return $this->display($this->getViews('deleted_list'));
    }

    //新建标签
    public function create(Request $request)
    {
        $this->title('增加标签');
        $this->setData('modal_title',__('suda_lang::press.btn.add'));
        $this->setData('modal_icon_class','ion-add-circle');
        
        $this->getActions();
        return $this->display($this->getViews('create'));
    }

    //更新分类
    public function update(Request $request,$id=0)
    {
        $this->setData('modal_title',__('suda_lang::press.btn.edit'));
        $this->setData('modal_icon_class','ion-add-circle');
        $this->title('编辑标签');

        $id = intval($id);
        
        $term = Taxonomy::where('id',$id)->where('taxonomy',$this->taxonomy_name)->with('term')->first();
        
        if(!$term){
            return $this->responseAjax('error','标签不存在');
        }
        
        $this->setData('term',$term);

        $this->getActions();
        return $this->display($this->getViews('update'));
    }

    //保存分类
    public function save(Request $request)
    {
        
        $id = $request->id;
        
        $roles=[];
        
        if($id){
            
            $taxonomy = Taxonomy::where('id',$request->id)->first();
            
            $roles = [
                'name' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query){
                        return $query->where('taxonomy',$this->taxonomy_name);
                    })->ignore($taxonomy->term_id)
                ],
                'slug' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query){
                        return $query->where('taxonomy',$this->taxonomy_name);
                    })->ignore($taxonomy->term_id)
                ],
            ];
            $messages = [
                'name.required'=>'请输入标签名称',
                'name.unique'=>'标签名称已存在',
                'slug.required'=>'请输入标签别名',
                'slug.unique'=>'标签别名已存在',
            ];
            
        }else{
            
            $roles = [
                'name' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query){
                        return $query->where('taxonomy',$this->taxonomy_name);
                    })
                ],
                'slug' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query){
                        return $query->where('taxonomy',$this->taxonomy_name);
                    })
                ],
            ];
            $messages = [
                'name.required'=>'请输入标签名称',
                'name.unique'=>'标签名称已存在',
                'slug.required'=>'请输入标签别名',
                'slug.unique'=>'标签别名已存在',
            ];
        }
        
        $response_msg = '';
        $ajax_result = $this->ajaxValidator($request->all(),$roles,$messages,$response_msg);
        
        if(!$request->has('parent')){
            $request->request->add(['parent'=>0]);
        }
        if(!$request->has('desc')){
            $request->request->add(['desc'=>'']);
        }
        if(!$request->has('sort')){
            $request->request->add(['sort'=>0]);
        }
        
        if(!$request->sort){
            $request->sort = 0;
        }
        
        if(!$response_msg){
            
            if($id){
                
                
                $taxonomy = Taxonomy::where('id',$request->id)->first();
                
                $taxonomy->where('id',$taxonomy->id)->update([
                    'term_id'   => $taxonomy->term_id,
                    'taxonomy'  => $this->taxonomy_name,
                    'parent'    => $request->parent,
                    'desc'      => $request->desc,
                    'sort'      => $request->sort,
                ]);
                
                Term::where('id','=',$taxonomy->term_id)->update([
                    'name'=>$request->name,
                    'slug'=>$request->slug,
                    'taxonomy'=>$this->taxonomy_name,
                ]);
                
                return $this->responseAjax('success','保存成功','self.refresh');
                
            }else{
                
                $term = new Term;
                $term->fill([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'taxonomy' => $this->taxonomy_name
                ])->save();
                
                $taxonomy = Taxonomy::where('taxonomy',$this->taxonomy_name)->where('term_id',$term->id)->first();
                if(!$taxonomy){
                    $taxonomy = new Taxonomy;
                    $taxonomy->fill([
                        'term_id'=>$term->id,
                        'taxonomy'=>$this->taxonomy_name,
                        'parent'=>$request->parent,
                        'desc'=>$request->desc,
                        'sort'=>$request->sort,
                    ])->save();
                }else{
                    $taxonomy->where('id',$taxonomy->id)->update([
                        'term_id'=>$term->id,
                        'taxonomy'=>$this->taxonomy_name,
                        'parent'=>$request->parent,
                        'desc'=>$request->desc,
                        'sort'=>$request->sort,
                    ]);
                }
                
                return $this->responseAjax('success','保存成功','self.refresh');
                
            }
        }
        
        return $this->responseAjax('fail',$response_msg,'self.refresh');
        
    }

    //删除标签
    public function delete(Request $request,$id)
    {
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $taxonomy = Taxonomy::where('id',$request->id)->with('term')->first();
            if($taxonomy->term->slug=='default'){
                return $this->responseAjax('warning','默认标签不能删除','self.refresh');
            }
            if($taxonomy){
                
                if(Taxable::where('taxonomy_id',$taxonomy->id)->first()){
                    return $this->responseAjax('warning','标签关联内容，无法删除','self.refresh');
                }
                
                Taxonomy::where('taxonomy',$this->taxonomy_name)->where('id',$request->id)->forceDelete();
                Term::where('taxonomy',$this->taxonomy_name)->where('id',$taxonomy->term_id)->forceDelete();
                Taxable::where('taxonomy_id',$taxonomy->id)->delete();
                
                return $this->responseAjax('success','删除成功');
                
            }else{
                return $this->responseAjax('warning','标签不存在,请重试','self.refresh');
            }
        }else{
            return $this->responseAjax('warning','标签不存在,请重试','self.refresh');
        }
        
    }
    
    
    //修改排序
    public function editSort(Request $request){
        
        if(!intval($request->inedit_id)){
            return $this->responseAjax('error','请求异常，请重试');
        }
        
        $sort = intval($request->inedit_value);
        
        $cate = Taxonomy::where(['id'=>$request->inedit_id])->first();
        
        if(!$cate){
            return $this->responseAjax('error','数据不存在，请重新检查');
        }
        
        Taxonomy::where(['id'=>$request->inedit_id])->update([
            'sort'=>$sort
        ]);
        
        return $this->responseAjax('success','排序已保存');
    }

    public function restore(Request $request,$id){
        
        if(intval($id)){
            
            $taxonomy = Taxonomy::withTrashed()->where('id',$id)->with('term')->first();

            if($taxonomy){
                
                Taxonomy::withTrashed()->where('taxonomy',$this->taxonomy_name)->where('id',$taxonomy->id)->restore();
                Term::withTrashed()->where('taxonomy',$this->taxonomy_name)->where('id',$taxonomy->term_id)->restore();
                
                return $this->responseAjax('warning','恢复成功');
                
            }else{
                return $this->responseAjax('warning','标签不存在,请重试','self.refresh');
            }
        }else{
            return $this->responseAjax('warning','标签不存在,请重试','self.refresh');
        }
        
    }


    public function deleteForce(Request $request,$id){
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            
            $taxonomy = Taxonomy::withTrashed()->where('id',$request->id)->with('term')->first();
            
            if($taxonomy->term->slug=='default'){
                return $this->responseAjax('warning','默认标签不能删除',$this->self_url);
            }
            if($taxonomy){
                
                if(Taxable::where('taxonomy_id',$taxonomy->id)->first()){
                    return $this->responseAjax('warning','标签关联内容，无法删除',$this->self_url);
                }
                
                Term::withTrashed()->where('taxonomy',$this->taxonomy_name)->where('id',$taxonomy->term_id)->forceDelete();
                Taxonomy::withTrashed()->where('taxonomy',$this->taxonomy_name)->where('id',$request->id)->forceDelete();
                Taxable::where('taxonomy_id',$taxonomy->id)->forceDelete();
                
                return $this->responseAjax('success','删除成功');
                
            }else{
                return $this->responseAjax('warning','标签不存在,请重试','self.refresh');
            }
        }else{
            return $this->responseAjax('warning','标签不存在,请重试','self.refresh');
        }
        
    }

    public function ajaxValidator($data,$roles,$messages=array(),&$response_msg=''){
        
        $default_messages = [];
        
        $messages = array_merge($default_messages,$messages);
        
        $validator = $this->validator($data,$roles,$messages);
        
        if (!$validator->passes()) {
            $msgs = $validator->messages();
            foreach ($msgs->all() as $msg) {
                $response_msg .= $msg . '</br>';
            }
            $response_type = false;
        }else{
            $response_type = true;
        }
        return $response_type;
    }


    public function responseAjax($type='fail',$msg='',$url='',$data=[])
    {
        // ajax返回请求
        if($url){
            if(substr($url,0,4)!='http'){
                $url = in_array($url,['ajax.close','self.refresh'])?$url:admin_url($url);
            }
        }else{
            $url = '';
        }
        $arr = array(
            'response_type' => $type,
            'response_msg' => $msg,
            'response_url' => $url
        );
        
        if($data){
            $arr = array_merge($arr,$data);
        }
        
        $code=422;
        if($type=='success' || $type=='info'){
            $code=200;
        }
        
        return Response::json($arr, $code);
    }

    
    protected function getViews($type='list')
    {

        $views = (array)$this->viewConfig();

        if(count($views)>0 && array_key_exists($type,$views)){
            return $views[$type];
        }

        switch($type){
            case 'list':
                return 'view_suda::taxonomy.tag.list';
            break;
            case 'deleted_list':
                return 'view_suda::taxonomy.tag.list';
            break;
            case 'create':
                return 'view_suda::taxonomy.tag.add';
            break;
            case 'update':
                return 'view_suda::taxonomy.tag.edit';
            break;
        }
        

    }

    public function viewConfig(){

        return [

            'list'      => 'view_suda::taxonomy.tag.list',
            'create'    => 'view_suda::taxonomy.tag.add',
            'update'    => 'view_suda::taxonomy.tag.edit',
        ];

    }

    protected function getActions(){

        $buttons = (array)$this->actionConfig();

        $this->setData('buttons',$buttons);

    }

    //设置自定义的链接
    public function actionConfig(){

        $buttons = [];

        $buttons['create']  = admin_url('article/tag/add');
        $buttons['update']  = admin_url('article/tag/update');
        $buttons['save']    = admin_url('article/tag/save');
        $buttons['delete']  = admin_url('article/tag/delete');
        $buttons['sort']    = admin_url('article/tag/editsort');
        
        return $buttons;
    }
}