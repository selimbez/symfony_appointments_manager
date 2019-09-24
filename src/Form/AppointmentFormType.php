<?php


namespace App\Form;


use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Time;

class AppointmentFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'Titel',
                'constraints' => [new NotBlank()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'customer',
            TextType::class,
            [
                'label' => 'PatientIn',
                'constraints' => [new NotBlank()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'date',
            DateType::class,
            [
                'label' => 'Datum',
                'constraints' => [new Date()],
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text'
            ]
        )->add(
            'startTime',
            TimeType::class,
            [
                'label' => 'Startzeit',
                'constraints' => [new Time()],
                'attr' => ['class' => 'form-control'],
                'widget' => 'choice',
                'with_seconds' => false
            ]
        )->add(
            'endTime',
            TimeType::class,
            [
                'label' => 'Endzeit',
                'constraints' => [new Time()],
                'attr' => ['class' => 'form-control'],
                'widget' => 'choice',
                'with_seconds' => false
            ]
        )->add(
            'assignee',
            EntityType::class,
            [
                'label' => 'Arzt',
                'class' => User::class,
                'choice_label' => 'fullName',
                'query_builder' => function(EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('d')
                        ->where('d.secretary = false')
                        ->orderBy('d.fullName', 'DESC');
                },
                'constraints' => [new NotNull()],
                'attr' => ['class' => 'form-control']
            ]
        )->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['class' => 'form-control btn-primary pull-right'],
                'label' => 'Erstellen'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Appointment'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'appointment_form';
    }
}