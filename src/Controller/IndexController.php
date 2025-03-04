<?php

namespace App\Controller;

use App\Service\RssFeed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ActuRepository;
use App\Repository\FunFactRepository;
use App\Repository\ScheduleRepository;



final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ActuRepository $actuRepository, FunFactRepository $funFactRepository, ScheduleRepository $scheduleRepository, RssFeed $rssService): Response
    {
        $rssItems = $rssService->getRssItems();

        return $this->render('index/index.html.twig', [
            'actus' => $actuRepository->findAll(),
            'fun_facts' => $funFactRepository->findAll(),
            'schedules' => $scheduleRepository->findAll(),
            'rss_items' => $rssItems,
            
        ]);
    }
}
