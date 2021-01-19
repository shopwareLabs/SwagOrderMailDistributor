(this.webpackJsonp=this.webpackJsonp||[]).push([["swag-order-mail-distributor"],{LBWF:function(t,i){t.exports='{% block swag_order_mail_distributor_list %}\n    <sw-page class="swag-order-mail-distributor-list">\n        {% block swag_order_mail_distributor_list_search_bar %}\n            <template #search-bar>\n                <sw-search-bar initialSearchType="order_mail_distribution"\n                               :initialSearch="term"\n                               @search="onSearch">\n                </sw-search-bar>\n            </template>\n        {% endblock %}\n\n        {% block swag_order_mail_distributor_list_smart_bar_header %}\n            <template #smart-bar-header>\n\n                {% block swag_order_mail_distributor_list_smart_bar_header_title %}\n                    <h2>\n\n                        {% block swag_order_mail_distributor_list_smart_bar_header_title_text %}\n                            {{ $tc(\'swag-order-mail-distributor.list.textDistributionOverview\') }}\n                        {% endblock %}\n\n                        {% block swag_order_mail_distributor_list_smart_bar_header_amount %}\n                            <span v-if="!isLoading" class="sw-page__smart-bar-amount">\n                                ({{ total }})\n                            </span>\n                        {% endblock %}\n\n                        {% block swag_order_mail_distributor_list_smart_bar_header_deprecation %}\n                            <swag-order-mail-distributor-deprecation\n                                variant="danger">\n                            </swag-order-mail-distributor-deprecation>\n                        {% endblock %}\n\n                    </h2>\n                {% endblock %}\n\n            </template>\n        {% endblock %}\n\n        {% block swag_order_mail_distributor_list_actions %}\n            <template #smart-bar-actions>\n                {% block swag_order_mail_distributor_list_smart_bar_actions %}\n                    <sw-button :routerLink="{ name: \'swag.order.mail.distributor.create\' }" variant="primary">\n                        {{ $tc(\'swag-order-mail-distributor.list.buttonAddDistribution\') }}\n                    </sw-button>\n                {% endblock %}\n            </template>\n        {% endblock %}\n\n        <template #content>\n            {% block swag_order_mail_distributor_list_content %}\n                <div class="swag-order-mail-distributor-list__content">\n                    {% block swag_order_mail_distributor_list_grid %}\n                        <sw-entity-listing\n                            :isLoading="!distributions"\n                            :columns="distributionColumns"\n                            :repository="distributionRepository"\n                            detailRoute="swag.order.mail.distributor.detail"\n                            :items="distributions"\n                             @update-records="updateTotal">\n                    {% endblock %}\n\n                        <template #column-active="{ item, isInlineEdit }">\n                            <sw-icon v-if="item.active" name="small-default-checkmark-line-medium" small class="is--active"></sw-icon>\n                            <sw-icon v-else name="small-default-x-line-medium" small class="is--inactive"></sw-icon>\n                        </template>\n\n                        {% block swag_order_mail_distributor_list_grid_loader %}\n                            <sw-loader v-if="isLoading"></sw-loader>\n                        {% endblock %}\n                        </sw-entity-listing>\n                </div>\n            {% endblock %}\n\n        </template>\n    </sw-page>\n{% endblock %}\n'},fQoW:function(t,i){t.exports='{% block swag_order_mail_distributor_deprecation %}\n    <sw-label\n        v-bind="$attrs"\n        v-tooltip="tooltipConfig">\n        <slot>\n\n            {% block swag_order_mail_distributor_deprecation_slot_default %}\n                {{ $tc(\'swag-order-mail-distributor-deprecation.label\') }}\n            {% endblock %}\n\n        </slot>\n    </sw-label>\n{% endblock %}\n'},iXUF:function(t,i,e){"use strict";e.r(i);var r=e("LBWF"),a=e.n(r);const{Component:o,Mixin:n}=Shopware,{Criteria:s}=Shopware.Data;o.register("swag-order-mail-distributor-list",{template:a.a,inject:["repositoryFactory"],mixins:[n.getByName("listing"),n.getByName("notification")],data:()=>({distributions:null,isLoading:!0}),metaInfo(){return{title:this.$createTitle()}},computed:{distributionRepository(){return this.repositoryFactory.create("swag_order_mail_distribution")},distributionColumns:()=>[{property:"mailTo",dataIndex:"mailTo",allowResize:!0,routerLink:"swag.order.mail.distributor.detail",label:"swag-order-mail-distributor.list.columnMailTo",primary:!0},{property:"rule.name",dataIndex:"rule.name",allowResize:!0,label:"swag-order-mail-distributor.list.columnRule"},{property:"mailTemplateType.name",dataIndex:"mailTemplateType.name",allowResize:!0,label:"swag-order-mail-distributor.list.columnMail"},{property:"active",dataIndex:"active",align:"center",allowResize:!0,label:"swag-order-mail-distributor.list.columnActive"}],distributionCriteria(){const t=new s,i=this.getListingParams();return i.sortBy=i.sortBy||"mailTo",i.sortDirection=i.sortDirection||"ASC",t.setTerm(this.term),t.addSorting(s.sort(i.sortBy,i.sortDirection)),t.addAssociation("rule"),t.addAssociation("mailTemplateType"),t}},methods:{onChangeLanguage(t){this.getList(t)},getList(){return this.isLoading=!0,this.distributionRepository.search(this.distributionCriteria,Shopware.Context.api).then(t=>{this.distributions=t,this.total=t.total,this.isLoading=!1})},updateTotal({total:t}){this.total=t}}});var l=e("mUCh"),d=e.n(l);const{Criteria:c}=Shopware.Data,{Component:m,Mixin:u}=Shopware,{mapPropertyErrors:b}=Shopware.Component.getComponentHelper();m.register("swag-order-mail-distributor-detail",{template:d.a,inject:["repositoryFactory"],mixins:[u.getByName("placeholder"),u.getByName("notification"),u.getByName("discard-detail-page-changes")("distribution")],shortcuts:{"SYSTEMKEY+S":"onSave",ESCAPE:"onCancel"},props:{distributionId:{type:String,required:!1,default:null}},data:()=>({distribution:null,customFieldSets:[],isLoading:!1,isSaveSuccessful:!1}),metaInfo(){return{title:this.$createTitle(this.identifier)}},computed:{identifier(){return this.placeholder(this.distribution,"name")},distributionIsLoading(){return this.isLoading||null==this.distribution},distributionRepository(){return this.repositoryFactory.create("swag_order_mail_distribution")},tooltipSave(){return{message:this.$device.getSystemKey()+" + S",appearance:"light"}},tooltipCancel:()=>({message:"ESC",appearance:"light"}),...b("distribution",["mailTo","ruleId","mailTemplateTypeId"])},watch:{distributionId(){this.createdComponent()}},created(){this.createdComponent()},methods:{createdComponent(){this.distributionId?this.loadEntityData():(this.distribution=this.distributionRepository.create(Shopware.Context.api),this.distribution.active=!1)},createTemplateTypeCriteria(){const t=new c;return t.addFilter(c.equals("technicalName","order_confirmation_mail")),t},loadEntityData(){this.isLoading=!0,this.distributionRepository.get(this.distributionId,Shopware.Context.api).then(t=>{this.isLoading=!1,this.distribution=t})},onSave(){this.isLoading=!0,this.distributionRepository.save(this.distribution,Shopware.Context.api).then(()=>{this.isLoading=!1,this.isSaveSuccessful=!0,null!==this.distributionId?this.loadEntityData():this.$router.push({name:"swag.order.mail.distributor.detail",params:{id:this.distribution.id}})}).catch(()=>{this.isLoading=!1,this.createNotificationError({title:this.$tc("global.default.error"),message:this.$tc("global.notification.notificationSaveErrorMessage",0,{entityName:this.distribution.mailTo})})})},onCancel(){this.$router.push({name:"swag.order.mail.distributor.index"})}}});var p=e("fQoW"),g=e.n(p);const{Component:_}=Shopware;_.register("swag-order-mail-distributor-deprecation",{template:g.a,inheritAttrs:!1,data(){return{tooltipConfig:{message:this.$tc("swag-order-mail-distributor-deprecation.message"),width:200,position:"right",showDelay:300,hideDelay:0}}}});const{Module:w}=Shopware;w.register("swag-order-mail-distributor",{type:"core",name:"distribution",title:"swag-order-mail-distributor.general.mainMenuItemGeneral",description:"Manages the distribution of the application",version:"1.0.0",targetVersion:"1.0.0",color:"#A092F0",icon:"default-shopping-paper-bag",favicon:"icon-module-products.png",entity:"order_mail_distribution",routes:{index:{components:{default:"swag-order-mail-distributor-list"},path:"index"},create:{component:"swag-order-mail-distributor-detail",path:"create",meta:{parentPath:"swag.order.mail.distributor.index"}},detail:{component:"swag-order-mail-distributor-detail",path:"detail/:id",meta:{parentPath:"swag.order.mail.distributor.index"},props:{default:t=>({distributionId:t.params.id})}}},navigation:[{path:"swag.order.mail.distributor.index",label:"swag-order-mail-distributor.general.mainMenuItemList",id:"swag-order-mail-distributor",parent:"sw-order",color:"#57D9A3",position:50}]});var h=Shopware.Plugin.addBootPromise();!function t(i){var e=Shopware.State.get("notification"),r=Shopware.Application.view.root;if(function(t){return Object.keys(t.notifications).find((function(i){var e=t.notifications[i];return!!e.metadata.deprecationWarning&&"swag-order-mail-distributor"===e.metadata.deprecationWarning}))}(e))i();else{if(!r)return window.setTimeout((function(){t(i)}),10),void i();Shopware.State.dispatch("notification/createNotification",{title:r.$tc("global.unsupported-plugin-warning.order-mail-distributor.title"),message:r.$tc("global.unsupported-plugin-warning.order-mail-distributor.description"),autoClose:!1,variant:"warning",metadata:{deprecationWarning:"swag-order-mail-distributor"}}),i()}}(h)},mUCh:function(t,i){t.exports='{% block swag_order_mail_distributor_detail %}\n    <sw-page class="swag-order-mail-distributor-detail">\n\n        {% block swag_order_mail_distributor_detail_header %}\n            <template #smart-bar-header>\n                <h2>\n                    {{ placeholder(distribution, \'mailTo\', $tc(\'swag-order-mail-distributor.detail.textHeadline\')) }}\n\n                    {% block swag_order_mail_distributor_detail_header_deprecation %}\n                        <swag-order-mail-distributor-deprecation\n                            variant="danger">\n                        </swag-order-mail-distributor-deprecation>\n                    {% endblock %}\n\n                </h2>\n            </template>\n        {% endblock %}\n\n        {% block swag_order_mail_distributor_detail_actions %}\n            <template #smart-bar-actions>\n\n                {% block swag_order_mail_distributor_detail_actions_abort %}\n                    <sw-button :disabled="distributionIsLoading" v-tooltip.bottom="tooltipCancel" @click="onCancel">\n                        {{ $tc(\'swag-order-mail-distributor.detail.buttonCancel\') }}\n                    </sw-button>\n                {% endblock %}\n\n                {% block swag_order_mail_distributor_detail_actions_save %}\n                    <sw-button-process\n                            class="swag-order-mail-distributor-detail__save-action"\n                            :isLoading="isLoading"\n                            v-model="isSaveSuccessful"\n                            :disabled="isLoading"\n                            variant="primary"\n                            v-tooltip.bottom="tooltipSave"\n                            @click.prevent="onSave">\n                        {{ $tc(\'swag-order-mail-distributor.detail.buttonSave\') }}\n                    </sw-button-process>\n                {% endblock %}\n\n            </template>\n        {% endblock %}\n\n        {% block swag_order_mail_distributor_detail_content %}\n            <sw-card-view slot="content">\n                {% block swag_order_mail_distributor_detail_base_basic_info_card %}\n                    <sw-card :title="$tc(\'swag-order-mail-distributor.detail.cardTitleDistributionInfo\')" :isLoading="distributionIsLoading">\n                        <template v-if="!distributionIsLoading">\n                            <sw-container columns="repeat(auto-fit, minmax(250px, 1fr)" gap="0 30px">\n                                <sw-field type="text"\n                                    :label="$tc(\'swag-order-mail-distributor.detail.labelMailTo\')"\n                                    :placeholder="placeholder(distribution, \'mailTo\', $tc(\'swag-order-mail-distributor.detail.placeholderMailTo\'))"\n                                    name="mailTo"\n                                    validation="required"\n                                    required\n                                    :error="distributionMailToError"\n                                    v-model="distribution.mailTo">\n                                </sw-field>\n\n\n                                <sw-field\n                                    type="switch"\n                                    :label="$tc(\'swag-order-mail-distributor.detail.labelActive\')"\n                                    v-model="distribution.active">\n                                </sw-field>\n                            </sw-container>\n\n                            <sw-entity-single-select\n                                entity="rule"\n                                :label="$tc(\'swag-order-mail-distributor.detail.labelRule\')"\n                                :placeholder="placeholder(distribution, \'mailTo\', $tc(\'swag-order-mail-distributor.detail.placeholderRule\'))"\n                                :error="distributionRuleIdError"\n                                v-model="distribution.ruleId"\n                                required>\n\n                            </sw-entity-single-select>\n\n                            <sw-entity-single-select\n                                entity="mail_template_type"\n                                :label="$tc(\'swag-order-mail-distributor.detail.labelMailTemplate\')"\n                                :placeholder="placeholder(distribution, \'mailTemplateTypeId\', $tc(\'swag-order-mail-distributor.detail.placeholderMailTemplate\'))"\n                                :criteria="createTemplateTypeCriteria()"\n                                :error="distributionMailTemplateTypeIdError"\n                                v-model="distribution.mailTemplateTypeId"\n                                labelProperty="name"\n                                required>\n                            </sw-entity-single-select>\n                        </template>\n                    </sw-card>\n                {% endblock %}\n            </sw-card-view>\n        {% endblock %}\n    </sw-page>\n{% endblock %}\n'}},[["iXUF","runtime"]]]);