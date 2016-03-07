# Sylius & Google Enhanced E-Commerce integration bundle

This bundle integrates [Google's Enhanced E-Commerce](https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce) (GEEC) tracking into [Sylius](http://sylius.org/).

## Documentation

###  Basic installation

  1. require the bundle with Composer:

        $ composer require webburza/sylius-google-ecommerce-bundle
  2. enable the bundle:
  
        <?php
        // app/AppKernel.php
        
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new \Webburza\Sylius\GoogleEcommerceBundle\WebburzaSyliusGoogleEcommerceBundle(),
                // ...
            );
        }
  3. add application-specific bundle configuration

        # app/config/config.yml
        
        webburza_sylius_google_ecommerce:
            key: %webburza.sylius.google_ecommerce.key%
  4. add application-specific bundle parameters (mainly, your Google Analytics key)

        # app/config/parameters.yml
        
        webburza.sylius.google_ecommerce.key: UA-12345678-1
  5. enable GEEC block rendering in your Twig layout

         <!-- app/Resources/SyliusWebBundle/views/Frontend/layout.html.twig -->
         
             <!-- add -->
             {{ google_ecommerce_render() }}
             <!-- /add -->
             </body>
         </html>

Having done this properly, you should have a functional Google Analytics tracking (without the e-commerce part). You can verify it works by
using [Google Analytics Debugger](https://chrome.google.com/webstore/detail/google-analytics-debugger/jnkmfdileelhofjcijamephohjechhna).

## License

This bundle is available under the [MIT license](LICENSE).

## Contributing

TODO
