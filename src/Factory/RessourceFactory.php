<?php

namespace App\Factory;

use App\Entity\Ressource;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Ressource>
 */
final class RessourceFactory extends PersistentProxyObjectFactory
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
        return Ressource::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {

        $ressources = [
            'Tapis de course' => 'Équipement cardiovasculaire',
            'Vélo stationnaire' => 'Équipement cardiovasculaire',
            'Poids et haltères' => 'Équipement de musculation',
            'Ballon de gymnastique' => 'Équipement de stabilité',
            'Barres parallèles' => 'Équipement de marche et d\'équilibre',
            'Salle de kinésithérapie' => 'Espace de traitement',
            'Piscine de rééducation' => 'Espace aquatique',
            'Salle de balnéothérapie' => 'Espace de soins par l\'eau',
            'Cabinet d\'ergothérapie' => 'Espace d\'activités de la vie quotidienne',
            'Appareil d\'électrothérapie' => 'Appareil de stimulation électrique',
            'Ultrasons thérapeutiques' => 'Appareil de thérapie par ondes sonores',
            'Appareil de cryothérapie' => 'Appareil de thérapie par le froid',
            'Système de réalité virtuelle' => 'Technologie de rééducation interactive',
            'Consultation en diététique' => 'Service de nutrition',
            'Atelier de gestion de la douleur' => 'Service de soutien psychologique',
            'Programme de réadaptation cardiaque' => 'Service de rééducation spécifique',
            'Service de transport adapté' => 'Service de logistique pour patients',
            'Fauteuils roulants' => 'Équipement de mobilité',
            'Déambulateurs' => 'Aide à la marche',
            'Orthèses et attelles' => 'Dispositifs de soutien articulaire'
        ];

        $nom = array_rand($ressources);

        return [
            'nom' => $nom,
            'type' => $ressources[$nom],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Ressource $ressource): void {})
        ;
    }
}
