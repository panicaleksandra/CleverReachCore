import template from './clever-reach-dashboard.html.twig';
import './clever-reach-dashboard.scss';

const {Component} = Shopware;

Component.register('clever-reach-dashboard', {
    template,

    inject: [
        'loginService',
        'syncService'
    ],

    data() {
        return {
            syncStatus: 'In progress',
            clientId: '',
            checkStatus: ''
        };
    },

    mounted: function () {
        this.checkStatus = setInterval(this.getSyncInfo, 500);
        const button = document.getElementById("manual-sync-button");
        button.addEventListener('click', this.startManualSync);
    },

    methods: {
        getSyncInfo: function () {
            const headers = {
                Authorization: `Bearer ${this.loginService.getToken()}`
            };

            return this.syncService.httpClient
                .get('/cleverreach/getsyncstatus', {headers})
                .then((response) => {
                    this.clientId = Shopware.Classes.ApiService.handleResponse(response).clientId;
                    this.syncStatus = Shopware.Classes.ApiService.handleResponse(response).syncStatus;

                    if (this.syncStatus === 'Done' || this.syncStatus === 'Error') {
                        clearInterval(this.checkStatus);
                    }
                }).catch(error => {
                    console.log("error");
                });
        },
        startManualSync: function () {
            const headers = {
                Authorization: `Bearer ${this.loginService.getToken()}`
            };

            return this.syncService.httpClient
                .get('/cleverreach/manualsync', {headers})
                .then((response) => {
                    console.log("ok");
                }).catch(error => {
                    console.log("error");
                });
        }
    }
});