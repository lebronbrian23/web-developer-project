module.exports = [
  {
    ignores: ["node_modules/", "vendor/", "cypress/", "storage/"],
  },
  {
    files: ["public/js/**/*.js"],
    languageOptions: {
      ecmaVersion: 2024,
      sourceType: "module",
      globals: {
        window: "readonly",
        document: "readonly",
        console: "readonly",
      },
    },
    rules: {
      "no-var": "error",
      "prefer-const": "warn",
      "no-unused-vars": "warn",
      "no-console": "off",
      "eqeqeq": "warn",
      "no-trailing-spaces": "error",
      "indent": ["warn", 4],
      "quotes": ["warn", "single"],
      "semi": ["warn", "always"],
    },
  },
];
