import './page/clever-reach-index';
import './page/clever-reach-dashboard';
import './page/clever-reach-landing';

Shopware.Module.register('clever-reach', {
    type: 'plugin',
    name: 'cleverreach-sync.pluginTitle',
    title: 'clever-reach-sync.general.mainMenuItemGeneral',
    description: 'clever-reach-sync.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'default-chart-heart-puls',

    routes: {
        index: {
            component: 'clever-reach-index',
            path: ':page',
        },
    },

    navigation: [
        {
            label: 'CleverReach',
            color: '#ffa2ef',
            path: 'clever.reach.index',
            parent: 'sw-marketing',
        },
    ],
});
