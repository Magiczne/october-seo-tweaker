# SeoTweaker

## Information

This is an basic SEO Plugin for October CMS. Includes support for:
- Basic CMS pages
- [RainLab.Pages (Static Pages)](http://octobercms.com/plugin/rainlab-pages)
- [RainLab.Blog](https://octobercms.com/plugin/rainlab-blog)
- [RainLab.Translate](https://octobercms.com/plugin/rainlab-translate)

## Added fields

### RainLab.Pages
- SEO Title
- SEO Description
- SEO Keywords
- Canonical URL
- Redirect URL
- Robots Index
- Robots Follow

### RainLab.Blog and Default October CMS Pages
- SEO Keywords
- Canonical URL
- Redirect URL
- Robots Index
- Robots Follow

## Components

### Seo Data

Component gives access to ```title```, ```description```, ```keywords```, ```canonicalUrl```, ```redirectUrl```, ```robotsFollow```
and ```robotsIndex``` fields that are filled dynamically depending on type of site.

#### Usage

Insert the component into layout, and then render it in the ```<head>``` section. For example:
```html
<!DOCTYPE html>
<html>
<head>
    {% component 'Seo %}

    <!-- Other tags -->
</head>
<body>
    {% page %}
</body>
</html>
```

You can choose from component options whether to include Open Graph tags, Twitter tags and JSON-LD;

You can also modify default component template or access fields directly.
