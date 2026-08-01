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
    }
}