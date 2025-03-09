<?php

namespace App\Factory;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Patient>
 *
 * @method        Patient|Proxy                              create(array|callable $attributes = [])
 * @method static Patient|Proxy                              createOne(array $attributes = [])
 * @method static Patient|Proxy                              find(object|array|mixed $criteria)
 * @method static Patient|Proxy                              findOrCreate(array $attributes)
 * @method static Patient|Proxy                              first(string $sortedField = 'id')
 * @method static Patient|Proxy                              last(string $sortedField = 'id')
 * @method static Patient|Proxy                              random(array $attributes = [])
 * @method static Patient|Proxy                              randomOrCreate(array $attributes = [])
 * @method static PatientRepository|ProxyRepositoryDecorator repository()
 * @method static Patient[]|Proxy[]                          all()
 * @method static Patient[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static Patient[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static Patient[]|Proxy[]                          findBy(array $attributes)
 * @method static Patient[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static Patient[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class PatientFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    protected $transliterator;

    public function __construct()
    {
        $this->transliterator = \Transliterator::create('Latin-ASCII');
    }

    public static function class(): string
    {
        return Patient::class;
    }

    protected function normalizeName(string $name): string
    {
        $normalized = $this->transliterator->transliterate($name);
        $normalized = preg_replace('/[^a-zA-Z0-9]+/', '-', $normalized);

        return strtolower($normalized);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $pathologies = [
            "Arthrose",
            "Spondylarthrite ankylosante",
            "Syndrome du canal carpien",
            "Tendinopathies",
            "Sclérose en plaques",
            "Maladie de Parkinson",
            "Syndrome de Guillain-Barré",
            "Paralysie faciale",
            "Rééducation post-infarctus",
            "Insuffisance cardiaque",
            "Artérite oblitérante des membres inférieurs",
            "Broncho-pneumopathie chronique obstructive (BPCO)",
            "Fibrose pulmonaire",
            "Syndrome d'apnées du sommeil",
            "Polyarthrite rhumatoïde",
            "Lupus érythémateux disséminé",
            "Fibromyalgie",
            "Rééducation après chirurgie de la hanche ou du genou",
            "Rééducation après chirurgie du rachis",
            "Paralysie cérébrale",
            "Scoliose idiopathique",
            "Syndrome de pied bot",
            "Ostéoporose avec fractures",
            "Syndrome de déconditionnement à l'effort",
            "Neuropathies diabétiques",
            "Syndrome du défilé thoracique",
            "Algoneurodystrophie",
            "Syndrome douloureux régional complexe",
            "Syndrome post-commotionnel",
            "Rééducation après traumatisme crânien sévère",
            "Rééducation post-cancer du sein",
            "Rééducation après chirurgie pour tumeur cérébrale"
        ];

        $firstName = self::faker()->firstName();
        $lastName = self::faker()->lastName();
        $normalizedFirstname = $this->normalizeName($firstName);
        $normalizedLastname = $this->normalizeName($lastName);
        $login = strtolower($normalizedFirstname).'.'.strtolower($normalizedLastname).self::faker()->numberBetween(001, 999);
        $pathology = self::faker()->randomElement($pathologies);

        return [
            'login' => $login,
            'nom' => $normalizedLastname,
            'prenom' => $normalizedFirstname,
            'pathologie' => $pathology,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */

}

