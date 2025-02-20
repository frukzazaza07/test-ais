// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader

const customeTheme = {
  dark: false,
}

const defaultInput = {
  variant: 'outlined',
  density: 'comfortable',
  hideDetails: 'auto',
}

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'customeTheme',
    themes: {
      customeTheme,
    },
  },
  icons: {
    defaultSet: 'mdi',
  },
  display: {},
  defaults: {
    VTextField: defaultInput,
    VTextarea: defaultInput,
    VSelect: defaultInput,
    VAutocomplete: defaultInput,
    VFileInput: {
      ...defaultInput,
      label: 'Chose file',
    },
    VBtn: {
      minHeight: '40px',
    },
  },
  ssr: true,
})

export default vuetify
