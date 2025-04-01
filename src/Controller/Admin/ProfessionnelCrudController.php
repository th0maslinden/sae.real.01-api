<?php

namespace App\Controller\Admin;

use App\Entity\Professionnel;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfessionnelCrudController extends AbstractCrudController
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
        return Professionnel::class;
    }


    public function configureFields(string $pageName): iterable
    {
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

        return [
            IdField::new('id')->onlyOnIndex(),
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
            TextField::new('specialite')->onlyOnIndex(),
            ChoiceField::new('specialite')
                ->setChoices(array_combine($specialite, $specialite))
                ->setRequired(false)
                ->allowMultipleChoices(false)
                ->onlyOnForms(),
        ];
    }

    public function setUserPasssword(User $user): void
    {
        $roles = $user->getRoles();
        if (!in_array('ROLE_PROFESSIONNEL', $roles, true)) {
            $roles[] = 'ROLE_PROFESSIONNEL';
            $user->setRoles($roles);
        }

        $password = $this->getContext()->getRequest()->request->all()['Professionnel']['password'];
        if ('' != $password) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }
    }
}
