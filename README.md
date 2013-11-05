SPAllocineApiBundle
===================


Allocine API client

## Installation

In composer.json:

```
"require": {
	...
    "sp/allocine-api-bundle": "dev-master"
}
```

Then run ```composer update sp/allocine-api-bundle```

Add the bundle to your AppKernel:

```
public function registerBundles()
{
    $bundles = array(
        ...
        new SP\AllocineBundle\SPAllocineBundle(),
    );
 ```
 
## Examples

```
$allocineApi = $this->container->get('allocine.api');

// Example 1: search "walking dead" with tvseries filter
$results = $allocineApi->search('walking dead', array('tvseries'));
  
// returns an array of results
Array
(
    [feed] => Array
        (
            [page] => 1
            [count] => 10
            [results] => Array
                (
                    [0] => Array
                        (
                            [type] => tvseries
                            [$] => 3
                        )

                )

            [totalResults] => 3
            [tvseries] => Array
                (
                    [0] => Array
                        (
                            [code] => 7330
                            [originalTitle] => The Walking Dead
                            [castingShort] => Array
                                (
                                    [creators] => Frank Darabont
                                    [actors] => Andrew Lincoln, Steven Yeun, Chandler Riggs, Norman Reedus, David Morrissey
                                )

                            [yearStart] => 2010
                            [statistics] => Array
                                (
                                    [pressRating] => 4.42857
                                    [userRating] => 4.51771
                                )

                            [poster] => Array
                                (
                                    [path] => /medias/nmedia/18/78/35/82/20303823.jpg
                                    [href] => http://fr.web.img5.acsta.net/medias/nmedia/18/78/35/82/20303823.jpg
                                )

                            [link] => Array
                                (
                                    [0] => Array
                                        (
                                            [rel] => aco:web
                                            [href] => http://www.allocine.fr/series/ficheserie_gen_cserie=7330.html
                                        )

                                )

                        )
                        [1] => Array
                        ...


// Example 2: find movie informations by his name
$result = $allocineApi->findMovie('Gravity');

// returns 
Array
(
    [movie] => Array
        (
            [code] => 178496
            [movieType] => Array
                (
                    [code] => 4002
                    [$] => Long-métrage
                )

            [originalTitle] => Gravity
            [title] => Gravity
            [productionYear] => 2013
            [nationality] => Array
                (
                    [0] => Array
                        (
                            [code] => 5002
                            [$] => U.S.A.
                        )

                    [1] => Array
                        (
                            [code] => 5004
                            [$] => Grande-Bretagne
                        )

                )

            [genre] => Array
                (
                    [0] => Array
                        (
                            [code] => 13021
                            [$] => Science fiction
                        )

                )

            [release] => Array
                (
                    [releaseDate] => 2013-10-23
                )

            [runtime] => 5400
            ...

```

Thanks for @gromez which has provided the query algorithm.

More informations: http://wiki.gromez.fr/dev/api/allocine_v3