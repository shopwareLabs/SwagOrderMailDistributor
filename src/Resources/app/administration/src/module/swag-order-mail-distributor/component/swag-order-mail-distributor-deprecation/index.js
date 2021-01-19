import template from './swag-order-mail-distributor-deprecation.html.twig';

const { Component } = Shopware;

Component.register('swag-order-mail-distributor-deprecation', {
    template,
    inheritAttrs: false,

    data() {
        return {
            tooltipConfig: {
                message: this.$tc('swag-order-mail-distributor-deprecation.message'),
                width: 200,
                position: 'right',
                showDelay: 300,
                hideDelay: 0
            }
        };
    }
});
