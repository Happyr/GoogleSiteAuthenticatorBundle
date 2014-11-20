# Happyr Google Site Authenticator Bundle

Use this bundle as a work around for Domain-Wide Delegation of Authority.


## Install

Make sure to include routing.yml

```yml
happyr_google_site_authenticator:
    resource: "@HappyrGoogleSiteAuthenticatorBundle/Resources/config/routing.yml"
    prefix:   /admin
```

Make sure you have a redirect url like:

www.example.com/admin/authenticate-google/return-url

```php
new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle()
new Happyr\GoogleSiteAuthenticatorBundle\HappyrGoogleSiteAuthenticatorBundle(),
```

Scopes: https://developers.google.com/drive/web/scopes