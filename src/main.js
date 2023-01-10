import "../node_modules/bulma/css/bulma.css";
import "@fontsource/source-sans-pro";
import "@fortawesome/fontawesome-free/css/all.css";

// import * as Sentry from "@sentry/vue";
// import { Integrations } from "@sentry/tracing";
import * as Sentry from "@sentry/browser";
import * as Integrations from "@sentry/integrations";
import getEnv from './utils/env';

import { createApp } from 'vue'
import App from './App.vue'
import router from './router.js'

const app = createApp(App)

if (getEnv('USE_SENTRY') === 'true') {
  Sentry.init({
    app,
    dsn: getEnv('SENTRY_DSN'),
    // integrations: [
    //   new Integrations.BrowserTracing({
    //     attachProps: true,
    //     logErrors: true,
    //     routingInstrumentation: Sentry.vueRouterInstrumentation(router),
    //     tracingOrigins: ["localhost", window.location.hostname, /^\//],
    //   }),
    // ],
    // Set tracesSampleRate to 1.0 to capture 100%
    // of transactions for performance monitoring.
    // We recommend adjusting this value in production
    // tracesSampleRate: 1.0,
    integrations: [new Integrations.Vue({
      attachProps: true,
      logErrors: true,
    })]
  });
}

app.use(router)
app.mount('#app')
