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

### Enabling the e-commerce integration

To enable the e-commerce part of the bundle, we need to tell it what the user is doing. We do this by using prepared Twig functions.

There are seven of these functions and we can group them in two distinct groups:

  * **direct action**, user did an action like    
    * product impression
    * product details impression
    * order checkout
    * order purchase
  * **action handler**, rendering a Javascript handler which will invoke a direct action like  
    * click on a product in a certain list of products
    * add product to cart
    * remove product from cart

#### Direct action functions

  * `{{ google_ecommerce_impression(product, {"list": list, "position": loop.index}) }}`  
    where ever we're displaying a product on any page **except on single product details view for that product**, mark the product impression.  
    Params:
    * `product`, an instance of a Sylius `Product`
    * `list`, a (string) name of the list in which the product is being displayed, ie. `"search results"`
    * `position`, the position of the product in that list, starting from 1
  * `{{ google_ecommerce_details(product) }}`  
    used only to indicate we're viewing a single product details view.  
    Params:
    * `product`, an instance of a Sylius `Product`
  * `{{ google_ecommerce_checkout(order, {'step': 2}) }}`  
    indicate the progression of a checkout. 
    Params:
    * `order`, an instance of a Sylius `Order`
    * `step`, which step are we currently on? Make sure to [configure the checkout funnel](https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#measuring-checkout), as described in the documentation.
  * `{{ google_ecommerce_purchase(order) }}`
    indicate a successful transaction.
    Params:
    * `order`, an instance of a Sylius `Order`

## License

This bundle is available under the [MIT license](LICENSE).

## Contributing

TODO
