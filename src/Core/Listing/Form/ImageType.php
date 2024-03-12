<?php

namespace App\Core\Listing\Form;

use App\Core\Listing\Entity\Draft;
use App\Core\Listing\Entity\Image;
use App\Core\Listing\Form\Transformer\ImageModelTransformer;
use App\Shared\Service\FileUploader\FileUploaderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType implements DataMapperInterface
{
    public function __construct(private FileUploaderInterface $fileUploader)
    {
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        return;
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        $imgPath = iterator_to_array($forms)['image']->getData()->getRealPath();

        $res = $this->fileUploader
            ->getUploader()
            ->uploadApi()
            ->upload($imgPath, [
                'use_filename' => false,
                'eager' => [
                    'width' => 480,
                    'height' => 480,
                    'crop' => 'pad',
                    'gravity' => 'center',
                ],
            ]);

        $image = new Image();
        $image->setId($res['public_id']);
        $image->setUrl($res['secure_url']);
        $image->setThumbnailUrl($res['eager'][0]['secure_url']);

        $viewData = $image;
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('image', FileType::class, [
                'required' => true,
                'mapped' => false,
            ])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('empty_data', null);

        $resolver->setDefaults([
            'data_class' => Image::class,
            'csrf_protection' => false,
        ]);
    }
}
