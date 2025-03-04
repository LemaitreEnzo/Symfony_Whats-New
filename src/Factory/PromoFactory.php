<?php

namespace App\Factory;

use App\Entity\Promo;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Promo>
 */
final class PromoFactory extends PersistentProxyObjectFactory
{
    private static $promos = [
        'B1',
        'B2',
        'B3',
    ];

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
        return Promo::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'name' => null,
        ];
    }

    public static function createPromo(): void
    {
        // Parcours de la liste des catégories et création de chaque catégorie.
        foreach (self::$promos as $promoName) {
            // Création d'une catégorie avec le nom et persistance dans la base de données.
            self::createOne(['name' => $promoName]);
        }
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Promo $promo): void {})
        ;
    }
}
