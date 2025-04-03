<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            NumberField::new('price'),
            ChoiceField::new('categorie')
                ->setChoices([
                    'Entrée' => 'entree',
                    'Plat' => 'plat',
                    'Dessert' => 'dessert',
                    'Boisson' => 'boisson',
                ]),
            AssociationField::new('menus')
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('by_reference', false)
        ];
    }
}
