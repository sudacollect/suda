<?php

namespace Gtd\Suda\Traits;

use Response;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Gtd\Suda\Models\Taxable;
use Gtd\Suda\Models\Taxonomy;
use Gtd\Suda\Models\Term;
use Gtd\Suda\TaxableUtils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

trait TaxonomyTrait
{
    // public $taxonomy_name = '';
    // public $multiple_level = false;
    // public $taxonomy_title = 'Category';
    
    //分类列表
    public function getList(Request $request)
    {
        if(!$this->taxonomy_name){
            return redirect('error');
        }
        
        $taxonomyObj = new Taxonomy;
        $categories = $taxonomyObj->listAll($this->taxonomy_name);

        $this->setData('categories',$categories);
        
        $this->getActions();
        return $this->display($this->getViews('list'));
    }

    //新建分类
    public function create(Request $request,$parent_id=0)
    {
        if(!$this->taxonomy_name){
            return $this->responseAjax('error','error');
        }
        

        $this->title((property_exists($this,'taxonomy_title')?$this->taxonomy_title:__('suda_lang::press.category')));
        $this->setData('modal_title',__('suda_lang::press.add'));
        $this->setData('modal_icon_class','ion-add-circle');

        $taxonomyObj = new Taxonomy;
        $categories = $taxonomyObj->listAll($this->taxonomy_name);

        $this->setData('categories',$categories);
        $this->setData('taxonomy_name',$this->taxonomy_name);
        $this->setData('parent_id',$parent_id);
        
        $this->getActions();
        return $this->display($this->getViews('create'));
    }

    //更新分类
    public function update(Request $request,$id=0)
    {
        $this->setData('modal_title',__('suda_lang::press.btn.edit'));
        $this->setData('modal_icon_class','ion-add-circle');
        $this->title('编辑');

        $id = intval($id);
        
        $term = Taxonomy::where('id',$id)->with('term')->first();
        
        if(!$term){
            return $this->responseAjax('error','数据不存在');
        }
        
        $this->setData('term',$term);
        
        $taxonomyObj = new Taxonomy;
        $catgories = $taxonomyObj->listAll($term->taxonomy);
        $this->setData('categories',$catgories);

        $this->getActions();
        return $this->display($this->getViews('update'));
    }

    //保存分类
    public function save(Request $request)
    {
        
        $id = $request->id;

        if(!$request->has('taxonomy_name')){
            return $this->responseAjax('fail','数据不存在','self.refresh');
        }

        $roles=[];
        
        if($id){
            
            $taxonomy = Taxonomy::where('id',$request->id)->first();
            
            $roles = [
                'name' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query) use ($request){
                        return $query->where('taxonomy',$request->taxonomy_name);
                    })->ignore($taxonomy->term_id)
                ],
                'slug' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query) use ($request){
                        return $query->where('taxonomy',$request->taxonomy_name);
                    })->ignore($taxonomy->term_id)
                ],
            ];
            $messages = [
                'name.required'=>'请填写名称',
                'name.unique'=>'名称已存在',
                'slug.required'=>'请填写别名',
                'slug.unique'=>'别名已存在',
            ];
            
        }else{
            
            $roles = [
                'name' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query) use ($request){
                        return $query->where('taxonomy',$request->taxonomy_name);
                    })
                ],
                'slug' => [
                    'required',
                    'min:2',
                    'max:64',
                    Rule::unique('terms')->where(function($query) use ($request){
                        return $query->where('taxonomy',$request->taxonomy_name);
                    })
                ],
            ];
            $messages = [
                'name.required'=>'请填写名称',
                'name.unique'=>'名称已存在',
                'slug.required'=>'请填写别名',
                'slug.unique'=>'别名已存在',
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

        $logo_media = 0;
        if($request->has('images')){
            //$request->request->add(['sort'=>0]);
            $logo_media = Arr::first($request->images);
        }
        
        if(!$request->sort){
            $request->sort = 0;
        }
        
        if(!$response_msg){
            
            if($id){
                
                
                $taxonomy = Taxonomy::where('id',$request->id)->first();
                
                $taxonomy->where('id',$taxonomy->id)->update([
                    'term_id'   => $taxonomy->term_id,
                    'taxonomy'  => $request->taxonomy_name,
                    'parent'    => $request->parent,
                    'desc'      => $request->desc,
                    'sort'      => $request->sort,
                    'color'     => $request->color?$request->color:'',
                    'logo'     => $logo_media,
                ]);
                
                Term::where('id','=',$taxonomy->term_id)->update([
                    'name'=>$request->name,
                    'slug'=>$request->slug,
                    'taxonomy'=>$request->taxonomy_name,
                ]);
                
                return $this->responseAjax('success',__('suda_lang::press.msg.success'),'self.refresh');
                
            }else{
                
                $term = new Term;
                $term->fill([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'taxonomy' => $request->taxonomy_name
                ])->save();
                
                $taxonomy = Taxonomy::where('taxonomy',$request->taxonomy_name)->where('term_id',$term->id)->first();
                if(!$taxonomy){
                    $taxonomy = new Taxonomy;
                    $taxonomy->fill([
                        'term_id'=>$term->id,
                        'taxonomy'=>$request->taxonomy_name,
                        'parent'=>$request->parent,
                        'desc'=>$request->desc,
                        'sort'=>$request->sort,
                        'color'     => $request->color?$request->color:'',
                        'logo'     => $logo_media,
                    ])->save();
                }else{
                    $taxonomy->where('id',$taxonomy->id)->update([
                        'term_id'=>$term->id,
                        'taxonomy'=>$request->taxonomy_name,
                        'parent'=>$request->parent,
                        'desc'=>$request->desc,
                        'sort'=>$request->sort,
                        'color'     => $request->color?$request->color:'',
                        'logo'     => $logo_media,
                    ]);
                }
                
                return $this->responseAjax('success',__('suda_lang::press.msg.success'),'self.refresh');
                
            }
        }
        
        return $this->responseAjax('fail',$response_msg,'self.refresh');
        
    }

    //删除分类
    public function delete(Request $request,$id)
    {
        
        if($request->id && !empty($request->id) && intval($id)==$request->id){
            $taxonomy = Taxonomy::where('id',$request->id)->with('term')->first();
            if($taxonomy->term->slug=='default'){
                return $this->responseAjax('warning','默认'.(property_exists($this,'taxonomy_title')?$this->taxonomy_title:'分类').'不能删除','self.refresh');
            }
            if($taxonomy){
                
                if(Taxable::where('taxonomy_id',$taxonomy->id)->first()){
                    return $this->responseAjax('warning','存在关联内容，无法删除','self.refresh');
                }
                
                Taxonomy::where('id',$request->id)->forceDelete();
                Term::where('id',$taxonomy->term_id)->forceDelete();
                Taxable::where('taxonomy_id',$taxonomy->id)->delete();
                
                return $this->responseAjax('success','删除成功');
                
            }else{
                return $this->responseAjax('warning','数据不存在,请重试','self.refresh');
            }
        }else{
            return $this->responseAjax('warning','数据不存在,请重试','self.refresh');
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
        
        return $this->responseAjax('success','排序已保存','self.refresh');
    }

    //修改显示
    public function toggle(Request $request,$id){
        
        
        $toggle = intval($request->toggle);
        
        $cate = Taxonomy::where(['id'=>$id])->first();
        
        if(!$cate){
            return $this->responseAjax('error','数据不存在，请重新检查');
        }
        
        Taxonomy::where(['id'=>$id])->update([
            'toggle'=>$toggle
        ]);
        
        return $this->responseAjax('success','收起已保存');
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

        $this->setData('taxonomy_title',property_exists($this,'taxonomy_title')?$this->taxonomy_title:__('suda_lang::press.category'));

        $views = $this->viewConfig();

        if(count($views)>0 && array_key_exists($type,$views)){
            return $views[$type];
        }
    }

    public function viewConfig(): array
    {
        return [

            'list'=>'view_suda::taxonomy.category.list',
            'create'=>'view_suda::taxonomy.category.add',
            'update'=>'view_suda::taxonomy.category.edit',
        ];
    }

    protected function getActions()
    {

        $buttons = $this->actionConfig();

        $this->setData('buttons',$buttons);
    }

    //设置自定义的链接
    public function actionConfig(): array
    {

        $buttons = [];

        $buttons['create']  = admin_url('article/category/add');
        $buttons['update']  = admin_url('article/category/update');
        $buttons['save']    = admin_url('article/category/save');
        $buttons['delete']  = admin_url('article/category/delete');
        $buttons['sort']    = admin_url('article/category/editsort');
        
        $buttons['modal_url'] = admin_url('medias/modal');
        $buttons['upload_url'] = admin_url('medias/upload/image');

        return $buttons;
    }
}