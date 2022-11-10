<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like', methods: ['POST'])]
    public function delete(Request $request, Article $article, LikeRepository $likeRepository): Response
    {
        if ($this->isCsrfTokenValid('like' . $article->getId(), $request->request->get('_token'))) {
            $like = $likeRepository->findOneBy([
                'article' => $article->getId(),
                'likeur' => $this->getUser()->getId(),
            ]);
            if (is_null($like)) {
                $like = (new Like())
                    ->setArticle($article)
                    ->setLikeur($this->getUser());
                $likeRepository->save($like, true);
            } else {
                $likeRepository->remove($like, true);
            }
        }
        $path = $request->request->get('_route_back');
        return $this->redirect($path);
    }
}
