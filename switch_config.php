<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-9-25
 * Time: 下午4:44
 */

/**
 * #########################【 sdk版本切换配置信息 】##################################
 * 建议平台最多放入三个版本 为了方便维护。
 * */
return [
    'versions' => [
        '1.0.0' => [ //版本
            'default' => false ,//开关 true 为 【开】，false 为 【关】
            'dir' => 'sdk_v1',//此版本拥有的目录
            'namespace' => 'sdk_v1',//【此处一般不用修改】此版本的namespace 名称 可以在 Thirdparty 文件上面看到。
        ],
        '1.0.1' => [
            'default' => true,//开关 true 为 【开】，false 为 【关】
            'dir' => 'sdk_beta1',//此版本拥有的目录
            'namespace' => 'sdk_beta1',//【此处一般不用修改】此版本的namespace 名称 可以在 Thirdparty 文件上面看到。
        ],
        /*
          //再加一个版本时配置如下添加即可
          'x.x.x' => [      //版本号
          'default' => true,//开关
          'dir' => 'sdk_beta1',//目录
           'namespace' => 'sdk_beta1',//namespace
          ],
        */
    ],
];