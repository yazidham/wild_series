<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs
        ]);
    }

   #[Route('/show/{id}', name: 'show', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function show(ProgramRepository $programRepository, int $id = 1, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id'=>$id]);
        $seasons = $seasonRepository->findBy(['program'=>$program], ['number'=>'ASC']);
        return $this->render('program/showProgram.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    #[Route('/{programId}/season/{seasonId}', name: 'season_show', methods: ['GET'])]
    public function showSeason(int $programId, int $seasonId, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $season = $seasonRepository->findOneBy(['id'=>$seasonId, 'program'=>$programId]);
        $episodes = $episodeRepository->findBy(['season'=>$seasonId]);
        return $this->render('program/season_show.html.twig', [
            'season' => $season,
            'episodes'=> $episodes
        ]);
    }
}