import template from './clever-reach-landing.html.twig';
import './clever-reach-landing.scss';

const {Component} = Shopware;

Component.register('clever-reach-landing', {
    template,

    inject: [
        'loginService',
        'syncService'
    ],

    data() {
        return {
            authStatus: false,
            popup: '',
            url: '',
        };
    },

    mounted: function () {
        const button = document.getElementById("connect-button");
        this.getUrl();
        button.addEventListener('click',
            () => {
                this.authStatus = setInterval(this.getAuthStatus, 500);
                this.popup = window.open(this.url, 'popup', 'location=yes,height=570,width=900,scrollbars=yes,status=yes');
            }
        );
    },

    methods: {
        getAuthStatus: function () {
            const headers = {
                Authorization: `Bearer ${this.loginService.getToken()}`
            };

            return this.syncService.httpClient
                .get('/cleverreach/status', {headers})
                .then((response) => {
                    if (Shopware.Classes.ApiService.handleResponse(response).status) {
                        let route = {
                            name: 'clever.reach.index',
                            params: {
                                page: 'dashboard'
                            },
                        };

                        this.$router.replace(route);
                        clearInterval(this.authStatus);
                        this.popup.close();
                    }
                }).catch(error => {
                    console.log("error");
                });
        },
        getUrl: function () {
            const headers = {
                Authorization: `Bearer ${this.loginService.getToken()}`
            };

            return this.syncService.httpClient
                .get('/cleverreach/geturl', {headers})
                .then((response) => {
                    this.url = Shopware.Classes.ApiService.handleResponse(response).returnUrl;
                }).catch(error => {
                });
        }
    }
});