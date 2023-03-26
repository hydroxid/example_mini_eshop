<?php

namespace App\Presenters;


use Nette;
use App\Model\Cart;
use App\Forms\ClientForm;
use App\Forms\SearchForm;
use App\Util\CurrencyExchange;
use Nette\Application\UI\Form;
use App\Repository\ItemsRepository;

/**
* BasePresenter
*
* @author hydroxid
*/
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
    * @inject
    * @var ItemsRepository */
    public $itemsRepository;

    /**
    * @inject
    * @var CurrencyExchange */
    public $currencyExchange;

    /**
    * @inject
    * @var Cart */
    public $cart;

    /**
    * @inject
    * @var ClientForm */
    public $clientForm;

    /**
    * @inject
    * @var SearchForm */
    public $searchForm;

    /** @var Nette\Database\Context */
    protected $db;

    public function __construct(Nette\Database\Context $db)
    {
        $this->db = $db;
    }

    public function beforeRender()
    {
        parent::beforeRender();

        // get cart summary from session
        $this->template->cartSummary = $this->cart->getCartSummary();

        // currency exchange
        $this->template->currencyEur = $this->currencyExchange->getExchangeFor('EUR');
    }

    /**
    * CART
    * save item to session ()
    *
    * @param int $it_id item id
    * @return void
    * @author hydroxid
    */
    public function handleSaveItem(int $it_id) : void
    {
        // save item to session
        $this->cart->saveItem($it_id, 1);

        if ($this->isAjax()) {
            // get cart summary from session
            $this->template->cartSummary = $this->cart->getCartSummary();
            $this->redrawControl('cartHeaderArea');
            $this->redrawControl('cartHeader');
        } else {
            $this->redirect('this');
        }
    }

    /**
    * CART
    * delete item from session
    *
    * @param string $item item key in session
    * @return void
    * @author hydroxid
    */
    public function handleDeleteItem(string $item) : void
    {
        // delete item from session
        $this->cart->deleteItem($item);
        $this->redirect('this');
    }

    /**
     * ClientForm factory
     * @return Form
    */
    protected function createComponentClientForm() : Form
    {
        $form = $this->clientForm->create();
        $form->onSuccess[] = function (Form $form) {
            $this->redirect('this');
        };
        return $form;
    }

    /**
     * SearchForm factory
     * @return Form
    */
    protected function createComponentSearchForm() : Form
    {
        $form = $this->searchForm->create();
        $form->onSuccess[] = function (Form $form) {
            $this->redirect('this');
        };
        return $form;
    }
}
