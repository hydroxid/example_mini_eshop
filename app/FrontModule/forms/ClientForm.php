<?php

namespace App\Forms;

use Nette;
use App\Model\Order;
use Nette\Utils\DateTime;
use Nette\Application\UI\Form;

class ClientForm extends Nette\Application\UI\Form
{
    use Nette\SmartObject;

    /**
    * @inject
    * @var Order */
    private $order;

    /** @var Nette\Database\Context */
    protected $db;

    public function __construct(Nette\Database\Context $db, Order $order)
    {
        $this->db = $db;
        $this->order = $order;
    }

    /**
    * save client data to db
    *
    * @return Form
    */
    public function create() : Form
    {
				$form = new Form;

        $form->addText('cl_name', 'Jméno')
        ->addRule(Form::MIN_LENGTH, 'Jméno musí mít alespoň %d znaků', 2)
        ->addRule(Form::PATTERN, 'Jméno obsahuje nepovolené znaky.', '[a-zA-ZáčďéěíňóřšťůúýžÁČĎÉĚÍŇÓŘŠŤŮÚÝŽäöüÄÖÜ]+[ \-]?')
        ->setHtmlAttribute('class', 'form-control')
        ->setRequired('Jméno je povinné');

        $form->addText('cl_surname', 'Příjmení')
        ->addRule(Form::MIN_LENGTH, 'Příjmení musí mít alespoň %d znaků', 2)
        ->addRule(Form::PATTERN, 'Příjmení obsahuje nepovolené znaky.', '[a-zA-ZáčďéěíňóřšťůúýžÁČĎÉĚÍŇÓŘŠŤŮÚÝŽäöüÄÖÜ]+[ \-]?')
        ->setHtmlAttribute('class', 'form-control')
        ->setRequired('Příjmení je povinné');

        $form->addEmail('cl_email', 'Email')
        ->setHtmlAttribute('class', 'form-control')
        ->addRule(Form::MIN_LENGTH, 'Email musí mít min %d znaků', 5)
        ->addRule(Form::MAX_LENGTH, 'Email má max %d znaků', 150)
        ->setRequired('Email je povinný');

        $form->addText('cl_phone', 'Telefon')
        ->setHtmlAttribute('class', 'form-control')
        ->setDefaultValue('+420')
        ->addRule(Form::MIN_LENGTH, 'Telefon musí mít %d znaků', 13)
        ->addRule(Form::MAX_LENGTH, 'Telefon musí mít %d znaků', 13)
        ->addRule(Form::PATTERN, 'Telefon obsahuje nepovolené znaky.', '^(\+420)? ?[1-9][0-9]{2} ?[0-9]{3} ?[0-9]{3}$')
        ->setRequired('Telefon je povinný');

        $form->addProtection();

        $form->addSubmit('send', 'Vytvořit objednávku')
        ->setHtmlAttribute('class', 'btn btn-primary');

				$form->onSuccess[] = [$this, 'processForm'];

				return $form;
    }

    /**
    * process form
    *
    * @author hydroxid
    */
		public function processForm(Form $form, Nette\Utils\ArrayHash $values) : void
		{
        // save client
        $client = $this->db->table('clients')->insert($values);

        // save order
        $order = $this->order->save($client->cl_id);

        $order
            ? $form->getPresenter()->flashMessage('Objednávka uložena', 'success')
            : $form->getPresenter()->flashMessage('Chyba', 'danger');

        $form->getPresenter()->redirect('this');
		}
}
