/* global jQuery */

// import local dependencies
import Router from './util/Router';

import common from './routes/common';
import pageMain from './routes/pageMain';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Main page
  pageMain,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());
