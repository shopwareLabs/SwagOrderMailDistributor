const { Component } = Shopware;

Component.extend('sw-fix-entity-single-select', 'sw-entity-single-select', {

    methods: {
        loadSelected() {
            if (this.value === '' || this.value === null) {
                return Promise.resolve();
            }

            this.isLoading = true;
            return this.repository.get(this.value, this.context, this.criteria).then((item) => {
                this.criteria.setIds([]);
                this.singleSelection = item;
                this.isLoading = false;
                return item;
            });
        }
    }
});
