<?php

namespace App\Model;

use Nette;
use App\Model\Cart;

class Order
{
    use Nette\SmartObject;

    private $db;
    private $cart;

    public function __construct(Nette\Database\Context $db, Cart $cart)
    {
        $this->db = $db;
        $this->cart = $cart;
    }

    /**
    * save item to session
    *
    * @param int $cl_id item id
    * @return void
    * @author hydroxid
    */
    public function save(int $cl_id) : ?int
    {
        // get items from cart
        $cartItems = $this->cart->getItems();
        // get cart summary from session
        $cartSummary = $this->cart->getCartSummary();

        // process if cart contain items
        if (count($cartItems) > 0) {
            // insert order to table orders
            $order = $this->db->table('orders')->insert([
                'ord_cl_id' => $cl_id,
                'ord_price' => $cartSummary['totalPrice']
            ]);

            // insert all items from cart to table orders_items
            $cartItems = $this->cart->getItems();
            foreach ($cartItems as $key => $qty) {
                $orderItem = $this->db->table('orders_items')->insert([
                    'orid_ord_id' => $order,
                    'orid_it_id' => $key,
                    'orid_qty' => $qty
                ]);

                // delete item from session
                $orderItem
                    ? $this->cart->deleteItem($key)
                    : $key;
            }

            return $order->ord_id;
        }

        return null;
    }
}
