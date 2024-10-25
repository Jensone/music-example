<?php

namespace App\Form;

use App\Entity\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'row_attr' => ['class' => 'mb-3'],
                'attr' => ['class' => 'form-control'],
                'label' => 'Title of your track',
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new Length(['min' => 3, 'max' => 180])
                ],
            ])
            ->add('link', DropzoneType::class, [
                'mapped' => false,
                'label' => 'Upload your track',
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => ['audio/mpeg'],
                        'maxSizeMessage' => 'The file is too large, the maximum allowed size is {{ limit }} M',
                    ])
                ],
            ])
            ->add('cover', DropzoneType::class, [
                'mapped' => false,
                'label' => 'Upload a cover',
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'maxSizeMessage' => 'The file is too large, the maximum allowed size is {{ limit }} M',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-success mt-4',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
