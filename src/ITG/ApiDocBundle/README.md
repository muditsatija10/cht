# IT Girnos Mill Framework Documentation Bundle

This bundle is responsible for changing the design of Nelmio ApiDoc Bundle documentation view to the one branded for IT Girnos

## Getting started

1. Add this repository as a submodule

        git submodule add --name ApiDocBundle git@bitbucket.org:itgirnos/apidocbundle.git src/ITG/ApiDocBundle

2. Make sure you have [Nelmio ApiDoc Bundle](https://github.com/nelmio/NelmioApiDocBundle) configured and running in your project
3. Add module to your `AppKernel.php`

        class AppKernel extends Kernel
        {
            public function registerBundles()
            {
                $bundles = [
                    // ...
                    new ITG\ApiDocBundle\ITGApiDocBundle(),
                ];
            }
        }
        
4. Copy IT Girnos logo from `src/ITG/ApiDocBundle/Resources/public/images/girnos_logo_white.svg` to your projects root image folder `images/girnos_logo_white.svg`
