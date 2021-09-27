module.exports = {
    root: true,
    env: {
        node: true
    },
    parserOptions: {
        parser: 'babel-eslint'
    },
    extends: ['plugin:vue/recommended', 'eslint:recommended', 'plugin:prettier/recommended'],
    rules: {
        'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off'
    }
};
