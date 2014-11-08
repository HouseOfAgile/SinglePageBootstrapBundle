<?php

/*
 * This file is part of the SinglePageBootstrapBundle.
 *
 * (c) Jean-Christophe Meillaud <jc@houseofagile.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HOA\Bundle\SinglePageBundle\Menu;

use HOA\Bundle\SinglePageBundle\Services\Manager\SPAConfigManager;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Dacorp\ExtraBundle\Menu\MenuBuilder;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;
use JMS\TranslationBundle\Annotation\Ignore;



class FrontendMenuBuilder
{
    /**
     * Menu factory.
     *
     * @var SPAConfigManager
     */
    protected $spaConfigManager;
    /**
     * Menu factory.
     *
     * @var FactoryInterface
     */
    protected $factory;
    /**
     * Translator instance.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    protected $defaultLang;

    /* @var $menuBuilder MenuBuilder */
    protected $menuBuilder;

    protected $requestStack;


    public function __construct( SPAConfigManager $spaConfigManager,RequestStack $requestStack,MenuFactory $menuFactory,TranslatorInterface $translator, MenuBuilder $menuBuilder, $defaultLang)
    {
        $this->spaConfigManager = $spaConfigManager;
        $this->requestStack = $requestStack;
        $this->factory = $menuFactory;
        $this->translator = $translator;
        $this->defaultLang = $defaultLang;

        $this->menuBuilder = $menuBuilder;


    }

    public function createLangMenu($availableLangs)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav navbar-right'
            )
        ));


        $languageDropDown = $menu->addChild('lang', array(
            'caret' => false,
            'dropdown' => true,
            'class' => ' navbar-left',
            'extras' => array('safe_label' => true)
        ));

        $currLang=$this->requestStack->getCurrentRequest()->getSession()->get('_locale', $this->defaultLang);
        $languageDropDown->setLabel('<i class="fa fa-flag"></i> '.$currLang.' <i class="fa fa-chevron-down"></i>');
        //create the childs
        foreach ($availableLangs as $ilang) {
            if ($ilang != $currLang) {
                $languageDropDown->addChild($ilang, array(
                    'route' => 'change_lang',
                    'extras' => array(
                        'icon' => 'flag',
                    ),
                    'routeParameters' => array('newlang' => $ilang)));
            }
        }
        return $menu;
    }

}
