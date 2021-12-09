<?php
/**
 * Created by PhpStorm.
 * User: shaobin
 * Date: 2021-12-08
 * Time: 22:55
 */

return [
    // 配送单状态 10待发布 20待审核 21审核失败 30已发布(审核通过、待接单) 40已接单(待配送) 41待重新派单 50配送中 60已送达(待评价) 70已完成(已评价) 80用户已取消 81管理员已取消
    'delivery_order'   => [
        'to_be_publish'   => 10,
        'to_be_apply'     => 20,
        'apply_failed'    => 21,
        'published'       => 30,
        'to_be_deliver'   => 40,
        'to_be_redeliver' => 41,
        'delivering'      => 50,
        'delivered'       => 60,
        'finished'        => 70,
        'user_canceled'   => 80,
        'admin_canceled'  => 81
    ],
    // 配送单商品
    'delivery_goods'   => [
        'type'   => [
            'merchant_goods' => 1, // 商家商品
            'self_goods'     => 2, // 自选商品
        ],
        'status' => [
            'deleted'    => 0, //已删除
            'not_delete' => 1 // 未删除
        ]
    ],
    // 配送单支付信息
    'delivery_payment' => [
        // 支付方式 10线下 20线上(微信支付) 21(支付宝) 22银行转账
        'payment_type' => [
            'offline'        => 10,
            'wechat_pay'     => 20,
            'ali_pay'        => 21,
            'back_translate' => 22
        ],
        'status'       => [
            'unpaid' => 0, // 未支付
            'paid'   => 1 // 已支付
        ]
    ]
];