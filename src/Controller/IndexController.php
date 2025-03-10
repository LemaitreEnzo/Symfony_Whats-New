<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ActuRepository;
use App\Repository\FunFactRepository;
use App\Repository\ScheduleRepository;
use App\Service\RssFeed;



final class IndexController extends AbstractController
{
    private RssFeed $rssFeed;

    public function __construct(RssFeed $rssFeed)
    {
        $this->rssFeed = $rssFeed;
    }
    #[Route('/', name: 'app_index')]
    public function index(ActuRepository $actuRepository, FunFactRepository $funFactRepository, ScheduleRepository $scheduleRepository): Response
    {
        $rssItems = $this->rssFeed->getRssItems();
        
        return $this->render('index/index.html.twig', [
            'actus' => $actuRepository->findAll(),
            'fun_facts' => $funFactRepository->findAll(),
            'schedules' => $scheduleRepository->findAll(),            
        ]);
    }
}
