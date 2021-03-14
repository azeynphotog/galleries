/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

import Muuri from 'muuri';
import './css/collectionPreview.scss'

$(document).ready(() => {
  $('.collection-preview-grid').each((i, dom) => {
    let grid = new Muuri(dom)
    grid.refreshItems()
    grid.layout()
  })
})
