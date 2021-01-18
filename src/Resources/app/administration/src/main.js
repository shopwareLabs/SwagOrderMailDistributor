import './module/swag-order-mail-distributor';

const bootPromiseResolve = Shopware.Plugin.addBootPromise();
const NOTIFICATION_IDENTIFIER = 'swag-order-mail-distributor';

function findDeprecationNotification(state) {
    return Object.keys(state.notifications).find((key) => {
        const notification = state.notifications[key];

        if (!notification.metadata.deprecationWarning) {
            return false;
        }

        return notification.metadata.deprecationWarning === NOTIFICATION_IDENTIFIER;
    });
}

function initializeDeprecationNotification(resolve) {
    const state = Shopware.State.get('notification');
    const root = Shopware.Application.view.root;

    if (findDeprecationNotification(state)) {
        resolve();
        return;
    }

    if (!root) {
        window.setTimeout(() => {
            initializeDeprecationNotification(resolve);
        }, 10);
        resolve();

        return;
    }

    Shopware.State.dispatch('notification/createNotification', {
        title: root.$tc('global.unsupported-plugin-warning.order-mail-distributor.title'),
        message: root.$tc('global.unsupported-plugin-warning.order-mail-distributor.description'),
        autoClose: false,
        variant: 'warning',
        metadata: {
            deprecationWarning: NOTIFICATION_IDENTIFIER
        }
    });

    resolve();
}

initializeDeprecationNotification(bootPromiseResolve);
