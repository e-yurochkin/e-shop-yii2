<?php

namespace console\controllers;

use backend\models\Cart;
use backend\models\Order;
use backend\models\OrderItem;
use backend\models\OrderStatus;
use yii\console\Controller;
use yii\db\Exception;

class OrderController extends Controller
{
    private array $orderStatuses = [
        'Pending',
        'Payment Received',
        'Order Confirmed',
        'Failed',
        'Awaiting Shipment',
        'Shipped',
        'Completed',
    ];

    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $this->createOrderStatuses();
        $this->createOrder(1);
        $this->createOrder(2);
    }

    /**
     * @throws Exception
     */
    private function createOrderStatuses(): void
    {
        foreach ($this->orderStatuses as $status) {
            $orderStatus = new OrderStatus(['status_name' => $status]);
            $orderStatus->save();
        }
    }

    /**
     * @throws Exception
     */
    private function createOrder(int $cartId): void
    {
        $cart = Cart::findOne($cartId);

        $order = new Order([
            'user_id' => $cart->user_id,
            'total_cost' => $cart->total_cost,
        ]);
        $order->save();

        foreach ($cart->cartItems as $cartItem) {
            $orderItem = new OrderItem([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'price' => $cartItem->product->price,
                'quantity' => $cartItem->quantity,
            ]);
            $orderItem->save();
        }
    }
}
