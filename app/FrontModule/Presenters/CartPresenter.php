<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class CartPresenter extends BasePresenter
{
    /**
    * index of cart content
    *
    * @return void
    * @author hydroxid
    */
    public function renderDefault() : void
    {
        // get cart items from session
        $this->template->cartItems = $cartItems = $this->cart->getItems();

        // get array of ids from cart items
        $ids = [];
        foreach ($cartItems as $key => $c) {
            $ids[] = $key;
        }

        // get items by ids
        $this->template->rows = $this->itemsRepository->findPublicItemsByIds($ids);
    }
}
