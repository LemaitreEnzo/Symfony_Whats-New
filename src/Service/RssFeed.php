<?php

namespace App\Service;

use App\Entity\Actu;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Exception;

class RssFeed
{
    private string $rssUrl = 'https://lamanu.fr/feed/';
    private EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function getRssItems(): array
    {
        $rssContent = @file_get_contents($this->rssUrl);
        if ($rssContent === false) {
            throw new Exception('Impossible de lire le flux RSS');
        }

        $rss = new SimpleXMLElement($rssContent);
        $items = [];

        foreach ($rss->channel->item as $item) {
            $description = mb_convert_encoding((string) $item->description, 'UTF-8');
            $content = (string) $item->children('content', true);
            $imageUrl = $this->extractImageUrl($content);

            $items[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'date' => (string) $item->pubDate,
                'description' => strip_tags($description),
                'image' => $imageUrl
            ];

            $this->createAndPersistActu(
                (string) $item->title,
                (string) $item->pubDate,
                strip_tags($description),
                $imageUrl
            );
        }

        return $items;
    }

    private function extractImageUrl(string $content): ?string
    {
        preg_match('/<img.*?src=["\'](.*?)["\']/', $content, $matches);
        return $matches[1] ?? null;
    }

    private function createAndPersistActu(string $title, string $date, string $description, ?string $imageUrl): void
    {

        // Vérifiez si une actu avec le même titre et la même date existe déjà
        $existingActu = $this->entityManager->getRepository(Actu::class)->findOneBy([
            'title' => $title,
            'date' => new \DateTime($date),
        ]);

        if ($existingActu) {
            // Si l'actu existe déjà, ne rien faire
            return;
        }

        
        $actu = new Actu();
        $actu->setTitle($title);
        $actu->setContent($description);
        $actu->setDate(new \DateTime($date));

        $defaultCategory = $this->categoryRepository->findOneBy([]);
        if ($defaultCategory) {
            $actu->setCategory($defaultCategory);
        } else {
            throw new \Exception('Aucune catégorie par défaut trouvée');
        }

        if ($imageUrl) {
            $uploadsDir = __DIR__ . '/../../public/uploads/images/';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }

            $imageContents = file_get_contents($imageUrl);
            if ($imageContents !== false) {
                $imageName = basename($imageUrl);
                $imagePath = $uploadsDir . $imageName;
                file_put_contents($imagePath, $imageContents);
                $actu->setImageName($imageName);
            } else {
                throw new \Exception('Impossible de télécharger l\'image à partir de l\'URL');
            }
        }

        $this->entityManager->persist($actu);
        $this->entityManager->flush();
    }
}
