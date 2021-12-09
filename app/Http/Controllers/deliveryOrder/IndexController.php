<?php

namespace App\Http\Controllers\deliveryOrder;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderGoods;
use App\Models\DeliveryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller {

    const SUCCESS = 0;
    const ERROR = 1000;

    private $config = '';

    public function __construct() {
        $this->config = config('status');
    }

    public function placeOrder(Request $request) {
        $user_id         = $request['user_id'];
        $address_id      = $request['address_id'];
        $goods_list      = $request['goods_list'];
        $goods_amount    = $this->_calculateGoodsAmount($goods_list);
        $delivery_amount = $this->_calculateFee($goods_amount);
        try {
            DB::beginTransaction();
            $order_insert      = [
                'user_id'     => $user_id,
                'address_id'  => $address_id,
                'status'      => $this->config['delivery_order']['to_be_apply'],
                'create_time' => time()
            ];
            $delivery_order_id = DeliveryOrder::saveOrder($order_insert);
            $goods_insert      = $this->_handleGoodsList($goods_list, $delivery_order_id);
            // 配送单支付信息
            $payment_insert = [
                'delivery_order_id' => $delivery_order_id,
                'payment_type'      => $this->config['delivery_payment']['payment_type']['offline'],
                'delivery_fee'      => $delivery_amount,
                'goods_amount'      => $goods_amount,
                'total_amount'      => $goods_amount + $delivery_amount,
                'status'            => $this->config['delivery_payment']['status']['paid'],
                'create_time'       => time()

            ];
            DeliveryOrderGoods::saveGoods($goods_insert);
            DeliveryPayment::savePayment($payment_insert);
            DB::commit();
            return responseResult(self::SUCCESS, '派单成功');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info('派单失败' . $exception);
            return responseResult(self::ERROR, '派单失败');
        }

    }

    /**
     * 组装商品数组
     * @param $goods_list
     * @param $order_id
     * @return array
     */
    private function _handleGoodsList($goods_list, $order_id) {
        $goods_insert = [];
        foreach ($goods_list as $item) {
            $goods_insert[] = [
                'delivery_order_id' => $order_id,
                'goods_name'        => $item['goods_name'],
                'goods_detail'      => $item['goods_detail'],
                'goods_pic'         => $item['goods_pic'],
                'goods_quantity'    => $item['goods_quantity'],
                'goods_price'       => $item['goods_price'],
                'store_id'          => $item['store_id'],
                'type'              => $item['type']
            ];
        }
        return $goods_insert;
    }

    /**
     * 计算商品总额
     * @param array $goods_list
     * @return float|int
     */
    private function _calculateGoodsAmount(array $goods_list) {
        $amount = 0.00;
        foreach ($goods_list as $item) {
            $amount += $item['goods_price'] * $item['goods_quantity'];
        }
        return $amount;
    }

    private function _calculateFee($goods_amount) {
        // TODO 计算配送费
        return 0.00;
    }
}
