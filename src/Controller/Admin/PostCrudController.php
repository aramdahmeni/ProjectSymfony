<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Form\type\FileUploadType;
use Symfony\Component\DomCrawler\Field\FileFormField;
class PostCrudController extends AbstractCrudController
{
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::EDIT);
    }
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('contenu')->setLabel('Content'),
            DateTimeField::new('published')->setLabel('Published At'),
            AssociationField::new('user')->setLabel('Author'),
            Field::new('files', 'PDF File')->onlyOnForms()->setFormType(FileType::class),


            // IntegerField::new('commentsCount')
            //     ->setLabel('Number of Comments')
            //     ->formatValue(fn ($value, $entity) => $entity->getCommentsCount()),
            // IntegerField::new('likesCount')
            //     ->setLabel('Number of Likes')
            //     ->formatValue(fn ($value, $entity) => $entity->getLikesCount())
        ];
    }
    
}
