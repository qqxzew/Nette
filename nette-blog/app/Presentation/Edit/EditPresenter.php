<?php
namespace App\Presentation\Edit;

use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ){}
    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()){
            $this->redirect('Sign:in');
        }
    }

    protected function createComponentForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Titel')
            ->setRequired();
        $form->addTextArea('content', 'Inhalt')
            ->setRequired();

        $form->addSubmit('send', 'Speichern');
        $form->onSuccess[] = $this->postFormSucceeded(...);

        return $form;
    }

    private function postFormSucceeded(array $data): void
    {
        $id = $this->getParameter('id');
        if($id){
            $post = $this->database->table('posts')->get($id);
            $post->update($data);
        }else {$post = $this->database->table('posts')->insert($data);}
        $this->flashMessage('Post wurde erfolgreich gespeichert', 'success');
        $this->redirect('Post:show', $post->id);

    }

    public function renderEdit(int $id): void
    {
        $post = $this->database
            ->table('posts')
            ->get($id);

        if(!$post){
            $this->error('Seite nicht gefunden');
        }

        $this->getComponent('form')
            ->setDefaults($post->toArray());
    }


}