# Clean Analytics
**Clean Analytics is a privacy-focused system for logging webpage and application telemetry.** Events are recording using HTTP requests, logged to a database, queryable, and can be inspected in real time from a graphical dashboard. Clean Analytics was created to be a simpler alternative to Google Analytics that never uses cookies and has ultimate respect for user privacy.

### Features
* Stats from multiple websites can be viewed from a single dashboard
* Tracking code can be used on any HTML page, including GitHub Pages
* Telemetry can be logged from desktop applications using HTTP requests
* Live dashboard updates in real time as new pages are viewed

### Installation
* Copy the `analytics/` folder to `https://example.com/analytics/`
* Add the following HTML to any page you wish to track

```html
<script>
    fetch('https://example.com/analytics/log/v1/',
    {
        method: 'POST',
        mode: 'cors',
        body: JSON.stringify({ url: window.location.href, ref: document.referrer }),
    });
</script>
```

### Development Environment
* Testing and linting requires [Composer](https://getcomposer.org/download/)
* Download dependencies: `composer install`
* Run phpunit tests: `composer test`