# Happyr Google Site Authenticator Bundle

In some situations you want a website to make API request on the behalf of you (not your users). Example when
you want to fetch website data from Google Analytics or upload database dumps to Google Drive. The solution Google
offers for this is a [Domain-Wide Delegation of Authority](https://developers.google.com/drive/web/delegation). But
that solution requires you be a paying customer on Google Apps. I wanted a free solution so I created this bundle.

This bundle is using a normal OAuth for a web application, but it authenticates your google account (or accounts) but
**not** your users'. It saves the access token in a cache until you manually revoke it.

***Read all of this README file to understand how to get started and authenticated***

## Install

Use composer to get the bundle. You do also have to get a PSR-6 cache implementation. 

```bash
$ php composer.phar require happyr/google-site-authenticator-bundle cache/redis-adapter
```

Activate this bundle in AppKernel.

```php
new Happyr\GoogleSiteAuthenticatorBundle\HappyrGoogleSiteAuthenticatorBundle(),
```

Include the routing.yml and make sure it is protected from normal users.

```yml
// app/config/routing.yml
happyr_google_site_authenticator:
    resource: "@HappyrGoogleSiteAuthenticatorBundle/Resources/config/routing.yml"
    prefix:   /admin
```

### Get API credentials

You will find all information on the [Google Console](https://code.google.com/apis/console). Go in to the console and
click on "APIs" in the sidebar to select those API you want to use.

To retrieve the API-key and secret, click on "Credentials" in the sidebar and then "Create new ClientID". And create a
client ID for a **web application**. Make sure to specify the correct *Authorized redirect URIs*. If you used the
configuration above you should use the following url:

```
http://www.domain.com/admin/authenticate-google/return-url
```

When you are done you will get a *client id* and a *client secret*. Save those for the next section.

### Configure

This bundle will fetch an access token and save it to cache. The [PHPCacheAdapterBundle](https://github.com/php-cache/adapter-bundle)
is an excellent bundle for this. You may use one of many predefined cache providers like; file_system, apc, mongodb etc.
Read more about caching here: http://www.php-cache.com/

It also allows you to create your own cache provider. Here is an example configuration:

``` yml
cache_adapter:
  providers:
    my_redis:
      factory: 'cache.factory.redis'
```

To configure the Happyr Google Site Authenticator bundle you need to add your API credentials and select a service
extending `Psr\Cache\CacheItemInterface`. If you are using the configuration
above you could use the following values:

``` yml
happyr_google_site_authenticator:
  cache_service: 'cache.provider.my_redis'
  tokens:
    google_drive:
      client_id: '00000000000-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com'
      client_secret: 'xxxxx-xxxxx_xxxxxxxxxxxx'
      redirect_url: 'http://www.domain.com/admin/authenticate-google/return-url'
      scopes: ['https://www.googleapis.com/auth/drive']
```
You will find all available scopes [here](https://developers.google.com/oauthplayground/).

The config above will configure a token called *google_drive*. You may, of course, configure more tokens. To get a
Google_Client instance with those credentials:

``` php
$clientProvider = $this->get('happyr.google.client_provider');
$client = $clientProvider->getClient('google_drive');

// or don't use the client provider
$client = $this->get('google.client.google_drive');

// if you only have one token configured
$client = $this->get('google.client');

```

## Authenticating

To make sure you fetch an access token you need to navigate to `http://www.domain.com/admin/authenticate-google` and
click on *Authenticate*. You will be asked to sign in with your Google account and grant the permissions. The access token
retrieved will be saved by the cache service. You want to make sure this is stored for a very long time.

When you are authenticated you may use `happyr.google.client_provider` to get an authenticated client.
