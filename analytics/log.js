/*
    Clean Analytics
    Privacy-focused website and application telemetry
    https://github.com/swharden/CleanAnalytics
*/

fetch('https://example.com/analytics/', {
    method: 'POST',
    mode: 'cors',
    body: JSON.stringify({
        url: window.location.href,
        ref: document.referrer
    })
});