<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar                  responsive">
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <ul class="nav nav-list">

            <li class="">

                <a href="index.html">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> 控制台 </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="<?php if(isset($class) && $class == 'account') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list-alt"></i>
					<span class="menu-text">
						公众号管理
					</span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php if(isset($subClass) && $subClass == 'index') echo 'active'; ?>">
                        <a href="/admin/account/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            公众号列表
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
            <li class="<?php if(isset($class) && $class == 'follow') echo 'active'; ?>">
                <a href="/admin/follow/index">
                    <i class="menu-icon fa fa-list"></i>
					<span class="menu-text">
						粉丝管理
					</span>
                </a>
            </li>

            <li class="<?php if(isset($class) && $class == 'user') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list-alt"></i>
					<span class="menu-text">
						管理员管理
					</span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php if(isset($subClass) && $subClass == 'user_index') echo 'active'; ?>">
                        <a href="/admin/user/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            管理员列表
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="<?php if(isset($class) && $class == 'setting') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-users"></i>
					<span class="menu-text">
						微信基础管理
					</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">                    
                    <li class="<?php if(isset($subClass) && strtolower($subClass) == 'menu') echo 'active'; ?>">
                        <a href="/admin/menu/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            自定义菜单
                        </a>
                        <b class="arrow"></b>
                    </li>                    
                    <li class="<?php if(isset($subClass) && strtolower($subClass)=='reply') echo 'active'; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            自定义回复
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <ul class="submenu">
                            <li class="<?php if(isset($action) && $action == 'textlist') echo 'active'; ?>">
                                <a href="/admin/reply/textlist">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    自定义文字回复
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="<?php if(isset($action) && $action == 'newslist') echo 'active'; ?>">
                                <a href="/admin/reply/newslist">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    自定义图文回复
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>                    
                    <li class="<?php if(isset($subClass) && strtolower($subClass) == 'eventmsg') echo 'active'; ?>">
                        <a href="/admin/eventmsg/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            系统回复管理
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php if(isset($subClass) && strtolower($subClass) == 'template') echo 'active'; ?>">
                        <a href="/admin/template/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            微信消息模板管理
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php if(isset($subClass) && strtolower($subClass) == 'qrcode') echo 'active'; ?>">
                        <a href="/admin/qrcode/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            二维码管理
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php if(isset($subClass) && strtolower($subClass) == 'message') echo 'active'; ?>">
                        <a href="/admin/message/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            群发消息
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="#">
                            <i class="menu-icon fa fa-caret-right"></i>
                            客服管理
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="#">
                            <i class="menu-icon fa fa-caret-right"></i>
                            用户留言
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
            <li class="<?php if(isset($class) && $class == 'site') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list-alt"></i>
                    <span class="menu-text">
                        微站管理
                    </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php if(isset($subClass) && strtolower($subClass) == 'site') echo 'active'; ?>">
                        <a href="#">
                            <i class="menu-icon fa fa-caret-right"></i>
                            系统设置
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php if(isset($subClass) && isset($action) && strtolower($subClass)=='article' && $action == 'index') echo 'active'; ?>">
                        <a href="/admin/article/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            文章列表
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php if(isset($subClass) && isset($action) && strtolower($subClass)=='article' && $action == 'type') echo 'active'; ?>">
                        <a href="/admin/article/type">
                            <i class="menu-icon fa fa-caret-right"></i>
                            文章分类
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>