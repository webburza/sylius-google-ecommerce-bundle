# Sylius & Google Enhanced E-Commerce integration bundle

[![Build Status](https://travis-ci.org/webburza/sylius-google-ecommerce-bundle.svg?branch=master)](https://travis-ci.org/webburza/sylius-google-ecommerce-bundle)

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

#### Direct action functions

These are direct responses to user doing an action.

  * `{{ google_ecommerce_impression(product, {"list": list, "position": loop.index}) }}`  
    mark a product impression in a listing.  
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

#### Action handler functions

These functions will render a handler which will react to user actions and invoke a direct action.

  * `{{ google_ecommerce_click(product, {"list": list}) }}`  
    track the click on the product in a listing.  
    Params:
    * `product`, an instance of a Sylius `Product`
    * `list`, a (string) name of the list in which the product is being displayed, ie. `"search results"`
  * `{{ google_ecommerce_cart(product, {'action': 'add', 'callable': 'function(product) {product[\'variant\'] = \'TODO: which variant?\'; return product;}'}) }}`  
    adding a product to cart.  
    Params:
    * `product`, an instance of a Sylius `Product`
    * `event`, the Javascript event to react to, defaults to `"submit"`
    * `action`, always `"add"`
    * `callable`, an optional Javascript callback which adds additional product information (like variant)
  * `{{ google_ecommerce_cart(product, {'event': 'click', 'action': 'remove', 'variant': item.vars.value.variant.__toString()}) }}`  
    removing a product from cart.  
    Params:
    * `product`, an instance of a Sylius `Product`
    * `event`, as we're using a hyperlink, this must be `"click"`
    * `action`, always `"remove"`
    * `variant`, as we know what's the product variant at render time, we do not need the JS callback as for the adding.
  * `{{ google_ecommerce_render() }}`  
    render the current GEEC block. This was the function used in [Basic installation](#basic-installation).  
    Params: none.

## License

This bundle is available under the [MIT license](LICENSE).

## Contributing

TODO
