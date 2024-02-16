// prettier.config.js

module.exports = {
  semi: false, // Example of a global rule
  overrides: [
    {
      files: '*.vue',
      options: {
        semi: true, // Example of a Vue-specific rule
        singleQuote: true, // Example of a Vue-specific rule
        // Add more Vue-specific formatting rules as needed
      },
    },
  ],
};