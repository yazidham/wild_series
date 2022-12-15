<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Service\ProgramDuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(MailerInterface $mailer, Request $request, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);
            $email = (new Email())
//                ->from($this->getParameter('mailer_from')
                ->from('yazidhamzi@icloud.com')
                ->to('yazidhamzi@icloud.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);
            $this->addFlash('success', 'The new program has been created');
            return $this->redirectToRoute('program_index');
        }
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', name: 'show', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        $duration = $programDuration->calculate($program);
        $seasons = $program->getSeasons();
        return $this->render('program/showProgram.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'duration' => $duration,
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}', name: 'season_show', methods: ['GET'])]
    #[Entity('program', options: ['mapping' => ['programSlug' => 'slug']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    public function showSeason(Season $season, Program $program,EpisodeRepository $episodeRepository): Response
    {
        $episodes = $episodeRepository->findBy(['id'=>$season]);
        return $this->render('program/season_show.html.twig', [
            'season' => $season,
            'episodes'=> $episodes,
            'program' => $program
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}/episode/{episodeSlug}', name: 'episode_show', methods: ['GET'])]
    #[Entity('program', options: ['mapping' => ['programSlug'=> 'slug']])]
    #[Entity('season', options: ['mapping' => ['seasonId'=> 'id']])]
    #[Entity('episode', options: ['mapping' => ['episodeSlug'=> 'slug']])]
    public function showEpisode(Episode $episode, Program $program, Season $season): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'episode' => $episode,
            'program' => $program,
            'season' => $season
        ]);
    }
}