<?php

namespace App\Factory;

use App\Entity\Actu;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @extends PersistentProxyObjectFactory<Actu>
 */
final class ActuFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Actu::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'content' => self::faker()->text(255),
            'category' => CategoryFactory::random(),
            'date' => self::faker()->dateTime(),
            'title' => self::faker()->text(255),
            'imageFile' => self::getRandomImageUrl(),
        ];
    }


    private static function getRandomImageUrl(): UploadedFile
    {
        // Initialisation de Faker
        $faker = self::faker();
        // Génération d'un chemin temporaire pour l'image
        $imagePath = sys_get_temp_dir() . '/' . $faker->uuid() . '.jpg';

        // Téléchargement de l'image depuis Picsum et sauvegarde dans le répertoire temporaire
        file_put_contents($imagePath, file_get_contents('https://picsum.photos/640/480'));

        // Déplacement de l'image vers le répertoire de téléchargements
        $uploadsDir = __DIR__ . '/../../public/uploads/images/';

        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true); // Crée le dossier s'il n'existe pas
        }

        $newImagePath = $uploadsDir . basename($imagePath);
        rename($imagePath, $newImagePath);

        // Retourne un objet UploadedFile représentant l'image
        return new UploadedFile(
            $newImagePath,
            basename($imagePath),
            'image/jpeg',
            null,
            true
        );
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Actu $actu): void {})
        ;
    }
}
