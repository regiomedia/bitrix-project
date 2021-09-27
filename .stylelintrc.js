/** @type {import("stylelint").Configuration} */
module.exports = {
    extends: [
      "stylelint-config-standard",
      "stylelint-config-recess-order",
      "stylelint-config-prettier",
    ],
    plugins: ["stylelint-scss"],
    rules: {
      "at-rule-no-unknown": null,
      "scss/at-rule-no-unknown": true,
    },
  };
