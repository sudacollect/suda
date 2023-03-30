@extends('view_path::component.modal',['modal_size'=>'lg'])



@section('content')
<style>
.ext-permissions .list-group-item:nth-of-type(2n){
    background:#f9fefe;
}
</style>
<form id="ext-permissions-form">
    <div class="modal-body">
        
        <div class="container-fluid ext-permissions-group">

            <button class="btn btn-success btn-sm me-2" id="permission-select-all">所有权限</button><button class="btn btn-light btn-sm" id="permission-deselect-all">取消全选</button>

            <div class="row ext-permissions-group">
            
            @foreach($menus as $menu_group_slug=>$menu_group)
            <div class="col-sm-4 ext-permissions-div">
            <ul class="ext-permissions">
                                    
                <li>
                    
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input permission-group" name="permission[{{ $menu_group['slug'] }}][_all_]" refer_check="check_{{ $menu_group['slug'] }}">
                        <label class="form-check-label extend-name font-weight-bold" for="permission-group" refer_check="check_{{ $menu_group['slug'] }}">
                            <i class="ion-reader-outline"></i>&nbsp;{{ $menu_group['title'] }}
                            {{-- <span class="badge bg-light text-dark">{{ $menu_group['slug'] }}</span> --}}
                        </label>
                    </div>
                    
                    @if(isset($menu_group['children']) && count($menu_group['children'])>0)

                    <ul class="list-group" id="check_{{ $menu_group['slug'] }}">
                        @foreach($menu_group['children'] as $menu_slug=>$menu)
                        <li class="list-group-item">
                            <div class="checkbox" style="margin:0;">

                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input single-permission" name="permission[{{ $menu_group['slug'] }}][{{ $menu['slug'] }}][__on__]" value="true">
                                    <label class="form-check-label" for="single-permission">
                                        {{ $menu['title'] }}
                                    </label>
                                </div>
                                <span class="help-block my-0">
                                    {{ $menu_group['slug'].'.'.$menu['slug'] }}
                                </span>

                            </div>

                            @if(isset($menu['methods']) && count((array)$menu['methods'])>0)
                            <div class="checkbox" style="margin:0;">
                                @foreach((array)$menu['methods'] as $method=>$method_text)
                                @if(!empty($method) && !empty($method_text))

                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input single-permission-item" name="permission[{{ $menu_group['slug'] }}][{{ $menu['slug'] }}][{{ $method }}]" value="true">
                                    <label class="form-check-label" for="single-permission-item" style="font-size:1rem;">
                                        {{ $method_text }}
                                    </label>
                                </div>

                                @endif
                                @endforeach
                            </div>
                            @endif
                            
                        </li>
                        @endforeach
                
                    </ul>

                    @endif
                    
                </li>
                
            </ul>
            </div>
            @endforeach
            
            </div>

            @if(count($extend_suda_menus)>0)
            
            <div class="row ext-permissions-group">
            <div class="col-sm-4 ext-permissions-div">
            <ul class="ext-permissions2" style="margin-top:10px">
                <li>
                    
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input permission-group" name="permission[#suda][_all_]" refer_check="check_#suda">
                        <label class="form-check-label extend-name" for="permission-group" refer_check="check_#suda">
                            扩展菜单
                        </label>
                    </div>

                    <ul class="list-group" id="check_#suda">
                    @foreach($extend_suda_menus as $extend_key=>$menu_group)
                        @foreach($menu_group['children'] as $menu_slug=>$menu)
                        <li class="list-group-item">
                            <div class="checkbox" style="margin:0;">

                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input single-permission" name="permission[#suda][{{ $extend_key }}][{{ $menu['slug'] }}]" value="true">
                                    <label class="form-check-label" for="single-permission">
                                        {{ $menu['title'] }}
                                    </label>
                                </div>
                                <span class="help-block my-0 text-muted">{{ $extend_key.'.'.$menu['slug'] }}</span>

                            </div>
                        </li>
                        @endforeach
                    @endforeach
                    </ul>
                    
                </li>
                
            </ul>
            
            @endif


            @if($auth_setting && count($auth_setting)>0)
            
            <div class="row ext-permissions-group">
            @foreach($auth_setting as $key=>$auth)
            
            <div class="col-sm-4 ext-permissions-div">
                <ul class="ext-permissions" style="margin-top:10px">
                    <li>

                        <strong class="extend-name" refer_check="check_#auth">
                            {{ $auth['name'] }}
                            {{-- <span style="font-weight:normal;color:#999;">#auth</span> --}}
                        </strong>

                        <select name="permission[#auth][{{ $key }}][]" multiple class="form-control select-permission" placeholder="选择权限">
                        @foreach($auth['values'] as $auth_key => $auth_value)
                            <option value="{{ $auth_value['id'] }}" @if(isset($auth_select_values[$key]) && in_array($auth_value['id'],(array)$auth_select_values[$key])) selected @endif>{{ $auth_value['name'] }}</option>
                        @endforeach
                        </select>
                    </li>
                    
                </ul>
            </div>

            @endforeach
            </div>
            @endif
            

        </div>
</div>

<div class="modal-footer">
    <button type="button" data-slug="{{ $ext['slug'] }}" data-role="{{ $role->id }}" class="save-permission btn btn-primary">保存设置</button>
</div>

</form>

<script>
    
    jQuery(function(){

        //init
        if($('input[name="select_permission[{{ $ext["slug"] }}]"]').length>0){

            var permission_uns = unserialize($('input[name="select_permission[{{ $ext["slug"] }}]"]').val());

            var keys = Object.keys(permission_uns);
            if(keys.length>0){

                $.each(permission_uns,function(index,e){

                    if($('input[name="'+index+'"]').length>0){
                        $('input[name="'+index+'"]').trigger('click');
                    }

                    if($('select[name="'+index+'"]').length>0){
                        $('select[name="'+index+'"]').find('option[value="'+e+'"]').prop("selected",true);

                    }

                });

            }

        }
        
        $('.ext-permissions').find('.extend-name').on('click',function(){
            
            var refer = $(this).attr('refer_check');
            
            $('#'+refer).toggle('fast','swing');
            
        });
        
        $('#permission-select-all').on('click', function(){
            $('ul.ext-permissions').find("input[type='checkbox']").prop('checked', true);
            return false;
        });

        $('#permission-deselect-all').on('click', function(){
            $('ul.ext-permissions').find("input[type='checkbox']").prop('checked', false);
            return false;
        });
        
        $('input.permission-group').on('change',function(){                
            $('ul[id="'+ $(this).attr('refer_check') +'"]').find("input[type='checkbox']").prop('checked', this.checked);
        });
        
        function parentChecked(elem){
            var permission=$('input.permission-group');
            if(elem){
                permission = $(elem).parents('.ext-permissions').find('input.permission-group');
            }
            permission.each(function(index,el){
                if($('ul[id="'+ $(el).attr('refer_check') +'"]').find("input[type='checkbox']").length>0){
                    var allChecked = true;
                    $('ul[id="'+ $(el).attr('refer_check') +'"]').find("input[type='checkbox']").each(function(){
                        if(!this.checked){
                            allChecked = false;
                        }
                    });
                    $(this).prop('checked', allChecked);
                }
            });
        }

        parentChecked();

        $('.single-permission').on('change', function(){
            $(this).parent('label').parent('div.checkbox ').siblings().find("input[type='checkbox']").prop('checked',$(this).prop('checked'));
            parentChecked(this);
        });

        $('.single-permission-item').on('change', function(){
            // var items = $(this).parents('.list-group').find('.single-permission-item');
            var itemall = $(this).parent('label').parent('div').find("input[type='checkbox']").length;
            var itemallChecked = $(this).parent('label').parent('div').find("input[type='checkbox']:checked").length;

            
            if(itemall===itemallChecked){
                $(this).parents('.list-group-item').find('.single-permission').prop('checked', itemallChecked);
            }else{
                //$(this).parents('.list-group-item').find('.single-permission').prop('checked', false);
            }
            

            parentChecked($(this).parents('.list-group').find('.single-permission-item'));
        });

        $('button.save-permission').on('click',function(e){

            e.preventDefault();

            var slug = $(this).attr('data-slug');
            var ext_form = $(this).parents('form#ext-permissions-form');

            $('#exts-form').find('input[name="select_permission['+slug+']"]').remove();
            $('#exts-form').append('<input type="hidden" name="select_permission['+slug+']" value="'+$(ext_form).serialize()+'">');

            $(this).parents('.modal').modal('hide');
        });


        function unserialize(serialize) {
            let obj = {};
            serialize = serialize.split('&');
            for (let i = 0; i < serialize.length; i++) {
                thisItem = serialize[i].split('=');
                obj[decodeURIComponent(thisItem[0])] = decodeURIComponent(thisItem[1]);
            };
            return obj;
        };

        $('select.select-permission').select2({
            theme: 'bootstrap-5',
            placeholder: '选择权限',
            containerCssClass: 'select2--small',
            dropdownCssClass: 'select2--small',
        });

    });

</script>

@endsection
