import template from './swag-order-mail-distributor-detail.html.twig';

const { Criteria } = Shopware.Data;
const { Component, Mixin } = Shopware;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('swag-order-mail-distributor-detail', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('placeholder'),
        Mixin.getByName('notification'),
        Mixin.getByName('discard-detail-page-changes')('distribution')
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onCancel'
    },

    props: {
        distributionId: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            distribution: null,
            customFieldSets: [],
            isLoading: false,
            isSaveSuccessful: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        identifier() {
            return this.placeholder(this.distribution, 'name');
        },

        distributionIsLoading() {
            return this.isLoading || this.distribution == null;
        },

        distributionRepository() {
            return this.repositoryFactory.create('swag_order_mail_distribution');
        },

        tooltipSave() {
            const systemKey = this.$device.getSystemKey();

            return {
                message: `${systemKey} + S`,
                appearance: 'light'
            };
        },

        tooltipCancel() {
            return {
                message: 'ESC',
                appearance: 'light'
            };
        },

        ...mapPropertyErrors('distribution', ['mailTo', 'ruleId', 'mailTemplateTypeId'])
    },

    watch: {
        distributionId() {
            this.createdComponent();
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            if (this.distributionId) {
                this.loadEntityData();
                return;
            }

            this.distribution = this.distributionRepository.create(Shopware.Context.api);
            this.distribution.active = false;
        },

        createTemplateTypeCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('technicalName', 'order_confirmation_mail'));

            return criteria;
        },

        loadEntityData() {
            this.isLoading = true;

            this.distributionRepository.get(this.distributionId, Shopware.Context.api).then((distribution) => {
                this.isLoading = false;
                this.distribution = distribution;
            });
        },

        onSave() {
            this.isLoading = true;

            this.distributionRepository.save(this.distribution, Shopware.Context.api).then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
                if (this.distributionId === null) {
                    this.$router.push({ name: 'swag.order.mail.distributor.detail', params: { id: this.distribution.id } });
                    return;
                }

                this.loadEntityData();
            }).catch(() => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('global.default.error'),
                    message: this.$tc(
                        'global.notification.notificationSaveErrorMessage', 0, { entityName: this.distribution.mailTo }
                    )
                });
            });
        },

        onCancel() {
            this.$router.push({ name: 'swag.order.mail.distributor.index' });
        }
    }
});
