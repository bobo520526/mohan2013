<?php
return array(
	//'配置项'=>'配置值'
    'LOAD_EXT_FILE' =>'common',
    'AUTH_CODE' => "GAMES",
    //'URL_CASE_INSENSITIVE' => false, //URL大小写不敏感
    'LOAD_EXT_CONFIG'=>'db', // 加载数据库配置文件
    'URL_MODEL'=>2, // 如果需要 隐藏 index.php  打开这行"URL_MODEL"注释 同时在apache环境下 开启 伪静态模块，  如果在nginx 下需要另外配置，参考thinkphp官网手册
    /*
     * RBAC认证配置信息
     */

    'SESSION_AUTO_START'        => true,
    'USER_AUTH_ON'              => true,
    'USER_AUTH_TYPE'            => 1,         // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             => 'authId',  // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'            => 'administrator',
    'USER_AUTH_MODEL'           => 'User',    // 默认验证数据表模型
    'AUTH_PWD_ENCODER'          => 'md5',     // 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         => '/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           => 'Public',  // 默认无需认证模块
//     'REQUIRE_AUTH_MODULE'       => '',        // 默认需要认证模块
//     'NOT_AUTH_ACTION'           => '',        // 默认无需认证操作
//     'REQUIRE_AUTH_ACTION'       => '',        // 默认需要认证操作
    'GUEST_AUTH_ON'             => false,     // 是否开启游客授权访问
    'GUEST_AUTH_ID'             => 0,         // 游客的用户ID
    'DB_LIKE_FIELDS'            => 'title|remark',
    'RBAC_ROLE_TABLE'           => 'think_role',
    'RBAC_USER_TABLE'           => 'think_role_user',
    'RBAC_ACCESS_TABLE'         => 'think_access',
    'RBAC_NODE_TABLE'           => 'think_node',
    'SHOW_PAGE_TRACE'           =>0,         //显示调试信息
    //'RBAC_ERROR_PAGE'         => '/Public/tp404.html',
    'ERROR_PAGE'=>'/index.php/Manage/Public/errfour.html',
    // 表单令牌验证相关的配置参数
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
    'TAGLIB_LOAD'   => true,
    'APP_AUTOLOAD_PATH'  =>'@.TagLib',
    'TMPL_TEMPLATE_SUFFIX'  =>  '.html',     // 默认模板文件后缀
    'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置  默认为html  去除默认的 否则很多地址报错

);