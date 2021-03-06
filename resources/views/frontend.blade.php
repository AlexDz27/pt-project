<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google-signin-client_id" content="{{ config('services.google.client_id') }}">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="/img/favicon/site.webmanifest">
  <link rel="shortcut icon" href="/img/favicon/favicon.ico">

  <link rel="stylesheet" href="/css/style.css">
  <title>PT project</title>
</head>
<body>

<div id="app"></div>

<script>
  window.API_URL = '{{ config('app.api_url') }}';
  window.GOOGLE_CLIENT_ID = '{{ config('services.google.client_id') }}';
</script>
<script src="/js/index.js"></script>
</body>
</html>
