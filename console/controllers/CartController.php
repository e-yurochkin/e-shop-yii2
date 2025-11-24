<?php

namespace console\controllers;

use backend\models\Cart;
use backend\models\CartItem;
use backend\models\Product;
use yii\console\Controller;
use yii\db\Exception;

class CartController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $this->createCart(1);
        $this->createCart(2);
    }

    /**
     * @throws Exception
     */
    private function createCart(int $userId): void
    {
        $cart = new Cart(['user_id' => $userId]);
        $cart->save();

        $totalCost = 0;

        for ($i = 0; $i < 5; $i++) {
            $productId = rand(1, 50);
            $product = Product::findOne($productId);
            $productQuantity = rand(1, min($product->stock_quantity, 3));

            if ($cartItem = CartItem::findOne(['product_id' => $productId, 'cart_id' => $cart->id])) {
                $cartItem->quantity += $productQuantity;
                $cartItem->save();
                continue;
            }

            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $productQuantity,
            ]);
            $cartItem->save();

            $totalCost += $product->price * $productQuantity;
        }

        $cart->total_cost = $totalCost;
        $cart->save();
    }
}
