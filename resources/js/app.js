import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
// Assuming you have access to conversationId
const conversationId = 1; // Replace with actual conversation ID

Echo.channel('messages.' + conversationId)
    .listen('MessageSent', (e) => {
        console.log(e.message);
        // Append the new message to the chat
        let messageHtml = `<div class="message">
                              <p><strong>${e.message.sender.name}:</strong> ${e.message.content}</p>
                           </div>`;
        document.querySelector('#messages').innerHTML += messageHtml;
    });
