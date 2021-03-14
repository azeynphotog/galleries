/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

import { createApp } from 'vue'
import Gallery from './components/Gallery'

const app = createApp({})
app.component('gallery', Gallery)
app.mount('#gallery-container')
