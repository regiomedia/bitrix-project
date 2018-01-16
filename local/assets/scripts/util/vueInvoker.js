/**
 * Модуль для автоматической инициализации Vue компонентов на странице сайта
 *
 * Метод `init(components)` Загружает компоненты в соответствующие элементы на странице и передает в них данные
 *
 * Например вместо блока:
 *
 * <div class="vue-component" data-component="DemoApp" data-initial='{"test": "data"}'></div>
 *
 * Будет подключен компонент DemoApp (если тот присутствует в объекте-коллекции `components`)
 *
 * и в его свойство initial будет передан JSON-объект {"test": "data"}
 *
 */

import Vue from 'vue';
import logger from './logger';


export default {
  init(components, options) {
    this.options = Object.assign(this.options, options);

    const nodes = Array.from(document.querySelectorAll(this.options.selector));


    const collection = [];

    nodes.forEach((item) => {
      let initialData = item.dataset[this.options.initialDataAttr];

      if (initialData !== undefined) {
        try {
          initialData = JSON.parse(initialData);
        } catch (e) {
          logger.warn(e);
        }
      }


      if (components[item.dataset[this.options.componentDataAttr]] !== undefined) {
        collection.push(this.createComponentInstance(
          item,
          components[item.dataset[this.options.componentDataAttr]],
          initialData,
        ));
      }
    });

    return collection;
  },

  options: {
    selector: '.vue-component',
    componentDataAttr: 'component',
    initialDataAttr: 'initial',
  },


  createComponentInstance(element, component, data) {
    return new Vue({
      el: element,
      render(h) {
        return h(component, {
          props: { initial: data },
        });
      },
    });
  },

};
