<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'delete_comment', methods: ['GET'])]
    public function delete(Comment $comment, CommentRepository $commentRepository, Request $request): Response
    {
        $commentRepository->remove($comment, true);
        $this->addFlash('danger', 'the comment has been deleted');
        return $this->redirectToRoute('app_episode_index');
    }
}