<?php

namespace App\Model;

use Nette;
use Nette\Http\Session;
use App\Repository\ItemsRepository;

class Cart
{
    use Nette\SmartObject;

    private $db;
    private $session;
    private $itemsRepository;
    private CONST SESSION_NAME = 'cart_items';

    public function __construct(Nette\Database\Context $db, Session $session,
        ItemsRepository $itemsRepository)
    {
        $this->db = $db;
        $this->session = $session;
        $this->itemsRepository = $itemsRepository;
    }

    /**
    * save item to session
    *
    * @param int $it_id item id
    * @param int $quantity item quantity
    * @return void
    * @author hydroxid
    */
    public function saveItem(int $it_id, int $quantity) : void
    {
        $section = $this->session->getSection(self::SESSION_NAME);

        // section exists
        if ($this->session->hasSection(self::SESSION_NAME)) {
            $section = $this->session->getSection(self::SESSION_NAME);

            // get current quantity for item
            $currenctQuantity = $section->get($it_id);

            $currenctQuantity
                ? $quantity = $currenctQuantity + $quantity
                : $quantity;

        }

        // save to session
        $section->set($it_id, $quantity);
    }

    /**
    * delete item from session
    *
    * @param string $item item key in session
    * @return void
    * @author hydroxid
    */
    public function deleteItem(string $item) : void
    {
        $section = $this->session->getSection(self::SESSION_NAME);

        // section exists
        if ($this->session->hasSection(self::SESSION_NAME)) {
            $section = $this->session->getSection(self::SESSION_NAME);

            // remove item
            $section->remove($item);
        }
    }

    /**
    * get items from session
    *
    * @return array
    * @author hydroxid
    */
    public function getItems() : array
    {
        $result = [];
        if ($this->session->hasSection(self::SESSION_NAME)) {
            $section = $this->session->getSection(self::SESSION_NAME);
            foreach ($section as $key => $r) {
                $result[$key] = $r;
            }
        }
        return $result;
    }

    /**
    * get cart summary from session
    *
    * @return array
    * @author hydroxid
    */
    public function getCartSummary() : ?array
    {
        // get cart items from session
        $cartItems = $this->getItems();

        // process if cart contain items
        if (count($cartItems) > 0) {
          
            $totalPrice = 0;
            foreach ($cartItems as $key => $qty) {
                $item = $this->itemsRepository->findPublicItem($key);
                if ($item) {
                    $price = $item->it_price * $qty;
                    $totalPrice = $totalPrice + $price;
                }
            }

            return [
                'totalPrice' => $totalPrice,
                'totalItems' => count($cartItems)
            ];
        }
        return [
            'totalPrice' => 0,
            'totalItems' => 0
        ];
    }
}
