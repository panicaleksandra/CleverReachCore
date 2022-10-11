import template from './clever-reach-index.html.twig';

const {Component} = Shopware;

Component.register('clever-reach-index', {
    template,

    inject: [
        'loginService',
        'syncService'
    ],

    mounted: function () {
        this.getCurrentRoute({});
    },

    methods: {
        getCurrentRoute: function (query) {
            const headers = {
                Authorization: `Bearer ${this.loginService.getToken()}`
            };

            return this.syncService.httpClient
                .get('/cleverreach/router', {headers})
                .then((response) => {
                    let routeName = Shopware.Classes.ApiService.handleResponse(response).page;
                    let route = {
                        name: 'clever.reach.index',
                        params: {
                            page: routeName
                        },
                        query: query
                    };

                    this.$router.replace(route);
                }).catch(error => {
                });
        }
    }
});