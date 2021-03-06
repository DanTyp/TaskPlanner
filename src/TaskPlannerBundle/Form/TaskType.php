<?php

namespace TaskPlannerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;



//use Symfony\Component\Form\Extension\Core\Type\TimeType;

class TaskType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name')
                ->add('description', 'textarea')
                //->add('deadline', DateType::class, array('widget' => 'choice'))
                ->add('deadline', DateType::class, array('widget' => 'single_text',
                    // do not render as type="date", to avoid HTML5 date pickers
                    'html5' => false,
                    // add a class that can be selected in JavaScript
                    'attr' => ['class' => 'js-datepicker'],))
                ->add('priority', 'choice', array('choices' => [1 => 'High', 2 => 'Medium', 3 => 'Low']))
                ->add('attach', FileType::class, ['label' => 'Attachement', 'required' => false, 'data_class' => null])
                ->add('category', 'entity', ['class' => 'TaskPlannerBundle:Category', 'query_builder' => function (EntityRepository $er) 
                    use ($options)
                    {
                        return $er->createQueryBuilder('c')
                                ->where('c.user = :userId')
                                ->setParameter('userId', $options['attr']['userId']); //zapytanie, po to żebym przy edycji widział tylko kategorie danego usera/bez dodatkowego poziomu/klucza 'attr' nie działało, nie widziało 'userId'
                    },
                    'choice_label' => 'name']);
    }

    //                ->add('deadline', DateType::class, array('widget' => 'choice'))

    /*
      use Symfony\Component\Form\Extension\Core\Type\TimeType;
      // ...

      $builder->add('startTime', TimeType::class, array('input'  => 'datetime', 'widget' => 'choice'));
     */

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'TaskPlannerBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'taskplannerbundle_task';
    }

}
