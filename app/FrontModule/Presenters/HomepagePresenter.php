<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class HomepagePresenter extends BasePresenter
{
    /**
    * index of items
    *
    * @param string $name search by string name
    * @param int $page paginator page number
    * @return void
    * @author hydroxid
    */
    public function renderDefault(int $page = 1, string $name = null) : void
    {

        // get items
        $rows = $this->itemsRepository->findPublicItems($name);

        $lastPage = 0;
    		$this->template->rows = $rows->page($page, 8, $lastPage);

        // paginator
    		$this->template->page = $page;
    		$this->template->lastPage = $lastPage;
    }

    /**
    * show item detail
    *
    * @param int $it_id item id
    * @return void
    * @author hydroxid
    */
    public function renderShow(int $it_id) : void
    {
        // get item
        $this->template->r = $this->itemsRepository->findPublicItem($it_id);
    }
}
