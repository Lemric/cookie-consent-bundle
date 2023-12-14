# Next!
-[x] update JS to .mjs and add bundler like rollup
 -[x] no need for seperate styling scss
- emit successful submit form event
- submit form asynchronously
-[x] Set Cookie after rejecting all cookies to not display the dialog again
-[x] make form available in dialog (Fuck off, IE11!!!)
- Style dialog
- Style form
    - https://symfony.com/doc/current/form/form_themes.html
    - https://symfony.com/doc/current/forms.html#rendering-forms
- Create Symfony Flex recipe: https://github.com/symfony/recipes
- ID for cookie consent log as UUID instead of integer

# Concept
Cookie consent consists of:
- Obligatory:
    - Session-Cookie
    - CSRF-Token
- optional:
    - Functional, marketing, social media, whatever