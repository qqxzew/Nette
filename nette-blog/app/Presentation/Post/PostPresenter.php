<?php
namespace App\Presentation\Post;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {}

    public function renderShow(int $id): void
    {
        $post = $this->database
            ->table('posts')
            ->get($id);

        if(!$post){
            $this->error('Seite nicht gefunden');
        }

        $this->template->post = $post;

        $this->template->post = $post;
        $this->template->comments = $post->related('comments')->order('created_at');
    }
    private function commentFormSucceeded(\stdClass $data): void
    {
        $id = $this->getParameter('id');

        $this->database->table('comments')->insert([
            'post_id' => $id,
            'name' => $data->name,
            'email' => $data->email,
            'content' => $data->content,
        ]);

        $this->flashMessage('Comment successfully added');
        $this->redirect('this');
    }
    protected function createComponentCommentForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'name:')
            ->setRequired();

        $form->addEmail('email', 'email:');

        $form->addTextArea('content', 'comment:')
            ->setRequired();

        $form->addSubmit('send', 'send');

        $form->onSuccess[] = $this->commentFormSucceeded(...);
        return $form;
    }


}