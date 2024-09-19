<?php

namespace App\Menu;

use App\Controller\OperateCleaningController;
use App\Controller\OperateInterventionController;
use App\Controller\OperateWebCompanionController;
use App\Controller\PlanController;
use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuBuilder
{
    public const array CSS_CLASSES = [
        'ROOT' => 'nav-item',
        'MENU' => 'nav-link collapsed m-0',
        'SUBMENU' => 'nav-link-text ms-1 sidemenu-submenu collapse ps-2',
        'ITEM' => 'sidenav-normal sidemenu-item',
        'LINK' => 'nav-link m-0'
    ];

    const string MENU_ENTRY_DASHBOARD = 'Dashboard';
    const string MENU_ENTRY_USER = 'Utilisateurs';
    const string MENU_ENTRY_CLIENT = 'Client';

    private ItemInterface $menu;
    private ?string $userRole = null;

    public function __construct(
        private readonly FactoryInterface $factory,
    )
    {
        $this->menu = $this->factory->createItem('root', [
            'navbar' => true,
            'childrenAttributes' => [
                'id' => 'side-menu',
                'class' => self::CSS_CLASSES['ROOT']
            ]
        ]);
    }

    final public function createAsideMenu(TokenStorageInterface $storage): ItemInterface
    {
        $this->userRole = $storage->getToken() !== null
            ? $storage->getToken()?->getUser()?->getMainRole()
            : null;

        if ($this->userRole !== null) {
            switch ($this->userRole) {
                case User::ROLE_ADMIN:
                    $this->setEntriesForAdmin();
                    break;
                case User::ROLE_USER:
                    $this->setEntriesForUser();
                    break;
                default:
                    break;
            }
        }

        return $this->menu;
    }

    private function setEntriesForAdmin(): self
    {
        return $this
            ->addDashboardEntries()
            ->setEntriesForAdminOnly();
    }

    final public function setEntriesForUser(): self
    {
        return $this
            ->addDashboardEntries();
    }

    private function setEntriesForAdminOnly(): self
    {
        return $this
            ->addUserEntries()
            ->addClientEntries();
    }

    final public function addDashboardEntries(): self
    {
        $this->menu->addChild(self::MENU_ENTRY_DASHBOARD, [
            'route' => 'admin_dashboard',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="fa fa-solid fa-chart-pie"></i></span><span class="sidenav-normal">Dashboard</span>',
            'extras' => array('safe_label' => true)
        ]);

        return $this;
    }

    final public function addUserEntries(): self
    {
        $this->menu->addChild(self::MENU_ENTRY_USER, [
            'uri' => '#submenu-user',
            'linkAttributes' => [
                'class' => self::CSS_CLASSES['MENU'],
                'data-bs-toggle' => 'collapse'
            ],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="ni ni-single-02"></i></span><span class="sidenav-normal">Utilisateurs</span>',
            'extras' => array('safe_label' => true),
            'childrenAttributes' => [
                'id' => 'submenu-user',
                'class' => self::CSS_CLASSES['SUBMENU'],
                'data-bs-parent' => '#side-menu'
            ]
        ]);
        $this->menu[self::MENU_ENTRY_USER]->addChild('Créer', [
            'route' => 'admin_user_add',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="fa fa-solid fa-pen"></i></span><span class="sidenav-normal">Créer</span>',
            'extras' => array('safe_label' => true)
        ]);
        $this->menu[self::MENU_ENTRY_USER]->addChild('Lister', [
            'route' => 'admin_user_list',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="ni ni-bullet-list-67"></i></span><span class="sidenav-normal">Lister</span>',
            'extras' => array('safe_label' => true)
        ]);
        $this->menu[self::MENU_ENTRY_USER]->addChild('Archive', [
            'route' => 'admin_user_archive_list',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="fa fa-solid fa-box-archive"></i></span><span class="sidenav-normal">Archive</span>',
            'extras' => array('safe_label' => true)
        ]);

        return $this;
    }

    final public function addClientEntries(): self
    {
        $this->menu->addChild(self::MENU_ENTRY_CLIENT, [
            'uri' => '#submenu-client',
            'linkAttributes' => [
                'class' => self::CSS_CLASSES['MENU'],
                'data-bs-toggle' => 'collapse'
            ],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="ni ni-single-02"></i></span><span class="sidenav-normal">Client</span>',
            'extras' => array('safe_label' => true),
            'childrenAttributes' => [
                'id' => 'submenu-client',
                'class' => self::CSS_CLASSES['SUBMENU'],
                'data-bs-parent' => '#side-menu'
            ]
        ]);
        $this->menu[self::MENU_ENTRY_CLIENT]->addChild('Créer', [
            'route' => 'admin_client_create',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="fa fa-solid fa-pen"></i></span><span class="sidenav-normal">Créer</span>',
            'extras' => array('safe_label' => true)
        ]);
        $this->menu[self::MENU_ENTRY_CLIENT]->addChild('Lister', [
            'route' => 'admin_client_list',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="ni ni-bullet-list-67"></i></span><span class="sidenav-normal">Lister</span>',
            'extras' => array('safe_label' => true)
        ]);
        $this->menu[self::MENU_ENTRY_CLIENT]->addChild('Archive', [
            'route' => 'admin_client_archived',
            'attributes' => ['class' => self::CSS_CLASSES['ITEM']],
            'linkAttributes' => ['class' => self::CSS_CLASSES['LINK']],
            'label' => '<span class="sidenav-mini-icon text-xs"><i class="fa fa-solid fa-box-archive"></i></span><span class="sidenav-normal">Archive</span>',
            'extras' => array('safe_label' => true)
        ]);

        return $this;
    }
}
