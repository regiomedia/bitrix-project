import vueInvoker from '../util/vueInvoker';
import vueCollection from '../vue/collection';

export default {
  init() {
    // JavaScript to be fired on all pages

    vueInvoker.init(vueCollection);
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
