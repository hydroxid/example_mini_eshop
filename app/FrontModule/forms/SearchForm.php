<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;

class SearchForm extends Nette\Application\UI\Form
{
    use Nette\SmartObject;

    /**
     * search items by name
     *
     * @return Form
     */
    public function create()
    {
				$form = new Form;

				$form->addText('sf_string', 'Hledané slovo')
				->setHtmlAttribute('class', 'form-control')
				->addRule(Form::MIN_LENGTH, 'Min. jsou %d znaky', 3)
        ->addRule(Form::MAX_LENGTH, 'Max. jsou %d znaky', 250)
				->setRequired('Zadejte hledané slovo');

        $form->addProtection();

				$form->addSubmit('send', 'Vyhledat')
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
				if ($values->sf_string) {
            $form->getPresenter()->redirect('Homepage:', 1, $values->sf_string);
				} else {
            $form->getPresenter()->flashMessage('Chyba', 'danger');
						$form->getPresenter()->redirect('this');
				}
		}
}
