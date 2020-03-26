import template from './swag-order-mail-distributor-list.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('swag-order-mail-distributor-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing')
    ],

    data() {
        return {
            distributions: null,
            isLoading: true
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        distributionRepository() {
            return this.repositoryFactory.create('order_mail_distribution');
        },

        distributionColumns() {
            return [{
                property: 'mailTo',
                dataIndex: 'mailTo',
                allowResize: true,
                routerLink: 'swag.order.mail.distributor.detail',
                label: 'swag-order-mail-distributor.list.columnMailTo',
                primary: true
            }, {
                property: 'rule.name',
                dataIndex: 'rule.name',
                allowResize: true,
                label: 'swag-order-mail-distributor.list.columnRule'
            },{
                property: 'mailTemplateType.name',
                dataIndex: 'mailTemplateType.name',
                allowResize: true,
                label: 'swag-order-mail-distributor.list.columnMail'
            }, {
                property: 'active',
                dataIndex: 'active',
                align: 'center',
                allowResize: true,
                label: 'swag-order-mail-distributor.list.columnActive'
            }];
        },

        distributionCriteria() {
            const criteria = new Criteria();
            const params = this.getListingParams();

            // Default sorting
            params.sortBy = params.sortBy || 'mailTo';
            params.sortDirection = params.sortDirection || 'ASC';

            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(params.sortBy, params.sortDirection));

            criteria.addAssociation('rule');
            criteria.addAssociation('mailTemplateType');

            return criteria;
        }
    },

    methods: {
        onChangeLanguage(languageId) {
            this.getList(languageId);
        },

        getList() {
            this.isLoading = true;

            return this.distributionRepository.search(this.distributionCriteria, Shopware.Context.api)
                .then((searchResult) => {
                    this.distributions = searchResult;
                    this.total = searchResult.total;
                    this.isLoading = false;
                });
        },

        updateTotal({ total }) {
            this.total = total;
        }
    }
});
