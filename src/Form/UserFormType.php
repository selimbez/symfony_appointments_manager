<?php


namespace App\Form;


use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UserFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add(
            'fullName',
            TextType::class,
            [
                'constraints' => [new NotBlank()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'email',
            EmailType::class,
            [
                'constraints' => [new NotBlank(), new Email()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'password',
            PasswordType::class,
            [
                'constraints' => [new NotBlank(), new UserPassword()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'secretary',
            CheckboxType::class,
            [
                'constraints' => [new NotNull()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['class' => 'form-control btn-primary pull-right'],
                'label' => 'Register'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_form';
    }
}