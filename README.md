# Kirby Panel Sort Files by Date

This plugin adds the ability for the Kirby Panel to sort files by date. Within your Kirby project, save to `site/plugins/sort-files-by-date.php`.

Blueprint settings for a page that will allow for file sorting:

```
files:
  fields:
    date:
      label: Date
      type: date
      required: true
    time:
      label: Time
      type: time
      interval: 1
      required: true
  sortable: true
```


To access your file collection in a template use `$page->files()->sortBy('sort', 'asc')`.
