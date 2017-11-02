<?php
    /*
     * 刘潇的个人简历
     * Say
     * 2017-11-01
     */
    $resume = [
        'info' => [
            'name' => '刘潇',
            'mobile' => '13651325235',
            'email' => 'whoam163@163.com',
            'age' => 27,
            'exp' => 6,
            'blog' => 'http://blog.sayphp.cn',
            'github' => 'https://github.com/sayphp',
            'job' => 'PHP工程师/架构师',
            'hobby' => '看书/游戏/篮球',
        ],
        'skill' => [
            'language' => 'PHP/Java/C/Shell/SQL/Lua/HTML/CSS/JavaScript',
            'database' => 'MySQL/Redis/Memcache',
            'server' => 'Nginx/Apache',
            'os' => 'Centos/Ubuntu/Windows',
            'ide' => 'Netbeans/Phpstorm/CLion/InterlliJ',
            'editor' => 'EditPlus/NotePad++/Vim',
            'other' => [
                'http/mqtt/websocket/rtmp',
                'Pcntl/Shmop/Sockets/Swoole',
                'ActiveMQ/Docker/WebGL/PhoneGap',
                'hls/ffmpeg/mmseg/openresty',
            ],
        ],
        'work' => [
            '2014.2-至今' => [
                'company' => '北京三好互动教育科技有限公司',
                'job' => '技术负责人/架构师/联合创始人',
                'desc' => '负责三好网业务设计、系统架构、程序开发、新人培训',
            ],
            '2013.9-2014.1' => [
                'company' => '北京普科国际有限公司',
                'job' => '技术负责人',
                'desc' => '负责网站开发、运维、SEO、SEM、APP开发、微信公众号开发',
            ],
            '2013.5-2013.8' => [
                'company' => '北京指点无限有限公司',
                'job' => 'H5前端工程师/PHP高级工程师',
                'desc' => '负责移动站开发、重构及APP内嵌H5页面开发',
            ],
            '2012.4-2013.4' => [
                'company' => '北京微普科技有限公司',
                'job' => 'PHP工程师/项目经理',
                'desc' => '负责项目开发、需求沟通、新人培训',
            ],
            '2011.6-2012.3' => [
                'company' => '北京海天国际有限公司',
                'job' => 'PHP程序员',
                'desc' => '负责网站维护、需求分析、功能开发',
            ],
            '2010.6-2011.5' => [
                'company' => '北京众惠信息咨询公司',
                'job' => '团队成员',
                'desc' => '创业项目，负责功能设计、网站维护、内容编辑，第一次接触PHP',
            ],
        ],
        'project' => [
            '三好网' => [
                'http://www.sanhao.com/',
                '',
            ],
            '抠电影' =>  [
                'http://www.komovie.cn/',
                '',
            ],
            '中影巴可' => [
                'http://www.cfg-barco.com/',
                '',
            ],
            '北京协和医学院研究生院选课系统' => [
                '内部系统',
                '',
            ],
            '其他' => [
                '中国人民大学MBA、EMBA、EDP等一系列网站及系统',
                '',
            ],
        ],
    ];
    echo json_encode($resume);