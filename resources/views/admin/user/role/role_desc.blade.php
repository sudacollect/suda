@if(config('app.locale') == 'en')
<div class="card">
                
    <div class="card-body">
        <h5>Roles</h5>
        <p>
            <b>General</b><br>
            <span class="help-block">
                Lowest-level background users who access corresponding functions according to the set permissions.
            </span>
        </p>
        <p>
            <b>Operation</b><br>
            <span class="help-block">
                Operations are at a higher level than general administrators.
            </span>
            <span class="help-block">
                Different permission settings can be given to different levels of administrators on applications and functions.
            </span>
        </p>

        <p>
            <b>Extension</b><br>
            <span class="help-block">Extension management</span>
            <span class="help-block" style="color:#ff5668;">
                The system provides extension administrators with a console different from other administrators
            </span>
        </p>

        <p>
            <b>Superadmin</b><br>
            <span class="help-block">All administrative privileges of the system.</span>
        </p>

    </div>

    <div class="card-footer">
        Important Note: Any role level does not include any functions and menu permissions by default, and needs to be set manually.
    </div>

</div>
@elseif(config('app.locale') == 'zh_CN')
<div class="card">
                
    <div class="card-body">
        <h5>角色说明</h5>
        <p>
            <b>普通管理员 general</b><br>
            <span class="help-block">普通管理员是最低级别的后台用户，根据设置的权限访问相应功能。</span>
        </p>

        <p>
            <b>运营管理员 operation</b><br>
            <span class="help-block">运营主管级别高于普通管理员。</span>
            <span class="help-block">高于普通管理员，具有一些关键操作的权限。</span>
        </p>

        <p>
            <b>应用管理员 extension</b><br>
            <span class="help-block">应用管理主要适用于不同应用的管理和控制。</span>
            <span class="help-block" style="color:#ff5668;">系统为应用管理员提供了有别于其他管理员的控制台。</span>
        </p>

        <p>
            <b>超级管理员 superadmin</b><br>
            <span class="help-block">拥有系统所有管理权限。</span>
        </p>

    </div>

    <div class="card-footer">
    说明: 任一角色级别默认不包括任何功能和菜单权限，均需手动完成设置。
    </div>

</div>
@endif