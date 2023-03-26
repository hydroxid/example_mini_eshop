<?php

namespace App\Repository;

use Nette;


/**
* ItemsRepository
*
* @author hydroxid
*/
class ItemsRepository
{
  	use Nette\SmartObject;

  	/**
    * @var Nette\Database\Explorer
    */
  	private $db;

  	public function __construct(Nette\Database\Explorer $db)
  	{
  		  $this->db = $db;
  	}

    /**
    * find one public item
    *
    * @param int $it_id item id
    * @return Nette\Database\Table\ActiveRow
    * @author hydroxid
    */
    public function findPublicItem(int $it_id) : ?Nette\Database\Table\ActiveRow
    {
        return $this->db->table('items')
        ->where('it_property', 'public')
        ->where('it_id', $it_id)
        ->fetch();
    }

    /**
    * find only public items
    *
    * @param string $name search by name
    * @param string $order order by
    * @return Nette\Database\Table\Selection
    * @author hydroxid
    */
  	public function findPublicItems(string $name = null, string $order = 'it_name ASC') : Nette\Database\Table\Selection
  	{
    		$rows = $this->db->table('items')
        ->where('it_property', 'public');

        if ($name) {
            $rows->where('it_name LIKE ?', '%'.$name.'%');
        }

        return $rows->order($order);
  	}

    /**
    * find only public items by ids
    *
    * @param array $ids ids of items
    * @param string $order order by
    * @return Nette\Database\Table\Selection
    * @author hydroxid
    */
    public function findPublicItemsByIds(array $ids, string $order = 'it_name ASC') : Nette\Database\Table\Selection
    {
        return $this->db->table('items')
        ->where('it_property', 'public')
        ->where('it_id IN (?)', $ids)
        ->order($order);
    }
}
