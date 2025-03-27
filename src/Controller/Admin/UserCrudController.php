<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setUserPasssword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setUserPasssword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->onlyOnIndex(),
            ArrayField::new('roles')->onlyOnIndex(),
            TextField::new('login'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('password')
                ->onlyOnForms()
                ->setFormType(PasswordType::class)
                ->setRequired(false)
                ->setEmptyData('')
                ->setFormTypeOption('attr', ['autocomplete' => 'new-password']),
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

    public function setUserPasssword(User $user): void
    {
        $password = $this->getContext()->getRequest()->request->all()['User']['password'];
        if ('' != $password) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }
    }

}
