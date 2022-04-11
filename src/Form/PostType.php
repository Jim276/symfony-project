<?php 

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostType extends AbstractType
{
    private $slugger;
    private $user;

    public function __construct(SluggerInterface $slugger, Security $security){
        $this->slugger = $slugger;
        $this->user = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                $post = $event->getData();
                $form = $event->getForm();
                if(!$post || null === $post->getId()){
                    $form->add('save', SubmitType::class, ['label' => 'New Post',]);
                }
            })
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('categories', EntityType::class,[
                'class' => Category::class,
                'multiple' => true, 
                'choice_label' => "name",
                'by_reference' => false, 
                'expanded' => true, 
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                
                /** @var Post */
                $post = $event->getData();

                $post->setPublicationDate(new \DateTime());

                if(null !== $post->getTitle()){
                    $post->setSlug($this->slugger->slug($post->getTitle())->lower());
                }
                if($this->user !== null){
                    $post->setAuthor($this->user); 
                }

            })
            ->add('save', SubmitType::class, ['label' => 'Update Post']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Post::class]);
    }
}