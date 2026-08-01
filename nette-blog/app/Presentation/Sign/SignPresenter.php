<?php
namespace App\Presentation\Sign;

use Nette;
use Nette\Application\UI\Form;
final class SignPresenter extends Nette\Application\UI\Presenter
{
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Username')
            ->setRequired();
        $form->addPassword('password', '')
            ->setRequired();
        $form->addSubmit('send', 'Sign in');
        $form->onSuccess[] = $this->signInFormSucceeded(...);
        return $form;
    }

    private function signInFormSucceeded(Form $form, \stdclass $data): void
    {
        try{
            $this-> getUser()->login($data->username, $data->password);
            $this->redirect('Home:');
        } catch (Nette\Security\AuthenticationException $e){
            $form->addError('Invalid username or password.');
        }
    }
     public function actionOut(): void
     {
         $this->getUser()->logout();
         $this->flashMessage('You have been logged out.');
         $this->redirect('Home:');
     }
}