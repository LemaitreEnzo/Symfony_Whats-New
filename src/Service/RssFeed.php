<?php

namespace App\Service;

use App\Entity\Actu;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
        $rssContent = file_get_contents($this->rssUrl);
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

            // Créez et persistez l'objet Actu
            $this->createAndPersistActu(
                (string) $item->title,
                (string) $item->link,
                (string) $item->pubDate,
                strip_tags($description),
                $imageUrl
            );
        }

        return $items;
    }

    private function extractImageUrl(string $description): ?string
    {
        preg_match('/<img.*?src=["\'](.*?)["\']/', $description, $matches);
        return $matches[1] ?? null;
    }

    private function createAndPersistActu(string $title, string $link, string $date, string $description, ?string $imageUrl): void
    {
        $actu = new Actu();
        $actu->setTitle($title);
        $actu->setContent($description);
        $actu->setDate(new \DateTime($date));

        // Définissez une catégorie par défaut ou aléatoire
        $defaultCategory = $this->categoryRepository->findOneBy([]); // Ou trouvez une catégorie spécifique
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
            $imagePath = $uploadsDir . basename($imageUrl);
            file_put_contents($imagePath, file_get_contents($imageUrl));

            $imageFile = new UploadedFile(
                $imagePath,
                basename($imagePath),
                'image/jpeg',
                null,
                true
            );

            $actu->setImageFile($imageFile);
        }

        $this->entityManager->persist($actu);
        $this->entityManager->flush();
    }
}
