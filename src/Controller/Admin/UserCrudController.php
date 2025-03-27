<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->onlyOnIndex(),
            ArrayField::new('roles')->onlyOnIndex(),
            TextField::new('firstname'),
            TextField::new('lastname'),
            EmailField::new('email'),
            TextField::new('pathologie')->onlyOnIndex(),
            TextField::new('specialite')->onlyOnIndex(),
        ];

        $pathologies = [
            'Arthrose',
            'Spondylarthrite ankylosante',
            'Syndrome du canal carpien',
            'Tendinopathies',
            'Sclérose en plaques',
            'Maladie de Parkinson',
            'Syndrome de Guillain-Barré',
            'Paralysie faciale',
            'Rééducation post-infarctus',
            'Insuffisance cardiaque',
            'Artérite oblitérante des membres inférieurs',
            'Broncho-pneumopathie chronique obstructive (BPCO)',
            'Fibrose pulmonaire',
            "Syndrome d'apnées du sommeil",
            'Polyarthrite rhumatoïde',
            'Lupus érythémateux disséminé',
            'Fibromyalgie',
            'Rééducation après chirurgie de la hanche ou du genou',
            'Rééducation après chirurgie du rachis',
            'Paralysie cérébrale',
            'Scoliose idiopathique',
            'Syndrome de pied bot',
            'Ostéoporose avec fractures',
            "Syndrome de déconditionnement à l'effort",
            'Neuropathies diabétiques',
            'Syndrome du défilé thoracique',
            'Algoneurodystrophie',
            'Syndrome douloureux régional complexe',
            'Syndrome post-commotionnel',
            'Rééducation après traumatisme crânien sévère',
            'Rééducation post-cancer du sein',
            'Rééducation après chirurgie pour tumeur cérébrale',
        ];

        if ($pageName === Crud::PAGE_EDIT && in_array('ROLE_PATIENT', $this->getContext()->getEntity()->getInstance()->getRoles())) {
            $fields[] = ChoiceField::new('pathologie')
                ->setChoices(array_combine($pathologies, $pathologies))
                ->setRequired(false)
                ->allowMultipleChoices(false);
        }

        $specialite = [
            'Rhumatologue',
            'Neurologue',
            'Cardiologue',
            'Pneumologue',
            'Kinésithérapeute',
            'Orthopédiste',
            'Médecin de réadaptation',
            'Gériatre',
            'Pédiatre',
            'Chirurgien orthopédiste',
            'Neurochirurgien',
            'Médecin de la douleur',
            'Oncologue',
            'Endocrinologue',
            'Psychiatre',
            'Orthophoniste',
            'Ergothérapeute',
            'Podologue',
            'Médecin du sport',
            'Médecin généraliste',
        ];

        if ($pageName === Crud::PAGE_EDIT && in_array('ROLE_PROFESSIONNEL', $this->getContext()->getEntity()->getInstance()->getRoles())) {
            $fields[] = ChoiceField::new('specialite')
                ->setChoices(array_combine($specialite, $specialite))
                ->setRequired(false)
                ->allowMultipleChoices(false);
        }

        return $fields;
    }

}
