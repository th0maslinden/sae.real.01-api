<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class SeanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Seance::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $types = ['Consultation de routine',
            'Bilan médical',
            'Consultation de suivi', ];

        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('date')->setFormat('dd/MM/YYYY'),
            TimeField::new('heureDebut')->setFormat('HH:mm'),
            TimeField::new('heureFin')->setFormat('HH:mm'),
            TextField::new('Raison'),
            AssociationField::new('patient')->formatValue(function ($value, $entity) {
                return $entity->getPatient() ? $entity->getPatient()->getLogin() : null;
            }),
            AssociationField::new('professionnel')->formatValue(function ($value, $entity) {
                return $entity->getProfessionnel() ? $entity->getProfessionnel()->getLogin() : null;
            }),
            ChoiceField::new('Raison')
                ->setChoices(array_combine($types, $types))
                ->setRequired(false)
                ->allowMultipleChoices(false)
                ->onlyOnForms(),


        ];
    }

}
