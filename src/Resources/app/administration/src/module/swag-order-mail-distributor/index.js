import './page/swag-order-mail-distributor-list';
import './page/swag-order-mail-distributor-detail';

import './component/swag-order-mail-distributor-deprecation';

const { Module } = Shopware;

Module.register('swag-order-mail-distributor', {
    type: 'core',
    name: 'distribution',
    title: 'swag-order-mail-distributor.general.mainMenuItemGeneral',
    description: 'Manages the distribution of the application',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#A092F0',
    icon: 'default-shopping-paper-bag',
    favicon: 'icon-module-products.png',
    entity: 'order_mail_distribution',

    routes: {
        index: {
            components: {
                default: 'swag-order-mail-distributor-list'
            },
            path: 'index'
        },
        create: {
            component: 'swag-order-mail-distributor-detail',
            path: 'create',
            meta: {
                parentPath: 'swag.order.mail.distributor.index'
            }
        },
        detail: {
            component: 'swag-order-mail-distributor-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'swag.order.mail.distributor.index'
            },
            props: {
                default(route) {
                    return {
                        distributionId: route.params.id
                    };
                }
            }
        }
    },

    navigation: [{
        path: 'swag.order.mail.distributor.index',
        label: 'swag-order-mail-distributor.general.mainMenuItemList',
        id: 'swag-order-mail-distributor',
        parent: 'sw-order',
        color: '#57D9A3',
        position: 50
    }]
});
