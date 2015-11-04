<?php

namespace Rj\EmailBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Rj\EmailBundle\Form\Type\CallbackType;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class EmailTemplateAdmin extends Admin
{
    protected $baseRouteName = 'email_template';
    protected $baseRoutePattern = 'email_template';
    protected $locales;

    public function setLocales(array $locales)
    {
        $this->locales = $locales;
    }

    //show
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    //add
    protected function configureFormFields(FormMapper $formMapper)
    {

        // pre-define form zoning
        $formMapper
            ->tab('Primary')
                ->with('Main', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-primary'
                ])->end()
                ->with('Content', [
                    'class' => 'col-md-8',
                    'box_class' => 'box box-warning'
                ])->end()
            ->end()
            ->tab('Meta')
                ->with('Email Origin')
                ->end()
            ->end()
        ;

        $formMapper->tab('Primary')->with('Main')->add('name')->end()->end();

        foreach ($this->locales as $locale) {
            $formMapper
                ->tab('Primary')
                    ->with('Main')
                        ->add(sprintf("translationProxies_%s_subject", $locale), 'text', array(
                            'label' => "Subject ($locale)",
                            'property_path' => sprintf('translationProxies[%s].subject', $locale),
                        ))
                    ->end()
                    ->with('Content')
                        ->add(sprintf("translationProxies_%s_body", $locale), 'textarea', array(
                            'label' => "Email Body ($locale)",
                            'property_path' => sprintf('translationProxies[%s].body', $locale),
                            'attr' => array(
                                'rows' => 12
                            )
                        ))
                    ->end()
                ->end()
                ->tab('Meta')
                    ->with('Email Origin')
                        ->add(sprintf("translationProxies_%s_fromName", $locale), 'text', array(
                            'label' => "From Name ($locale)",
                            'property_path' => sprintf('translationProxies[%s].fromName', $locale),
                            'required' => false,
                        ))
                        ->add(sprintf("translationProxies_%s_fromEmail", $locale), 'email', array(
                            'label' => "From Email Address ($locale)",
                            'property_path' => sprintf('translationProxies[%s].fromEmail', $locale),
                            'required' => false,
                        ))
                    ->end()
                ->end()
            ;
        }
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('updatedAt')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                )
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if ('edit' == $action) {
            $item = $this->menuFactory->createItem('send_test', array(
                'uri' => 'javascript:void(send_test())',
                'label' => 'Send test email',
            ));
            $menu->addChild($item);
        }
    }

    public function setTemplates(array $templates)
    {
        parent::setTemplates($templates);
        $this->setTemplate('edit', 'RjEmailBundle:EmailTemplate:edit.html.twig');
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('send_test', $this->getRouterIdParameter().'/send_test');
    }
}
