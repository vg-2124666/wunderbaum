<?php

    namespace Wunderbaum\Controllers;

    use Silex\Application,
        Silex\ControllerProviderInterface,
        Symfony\Component\HttpFoundation\Request,
        Wunderbaum\Utils\VarnishParser;


    /**
     * Main controller for the Wunderbaum application
     *
     * @package Wunderbaum\Controllers
     */
    class Main implements ControllerProviderInterface
    {

        use Application\TwigTrait;

        public function connect(Application $app)
        {
            $factory = $app['controllers_factory'];

            $factory->get(
                '/',
                '\\Wunderbaum\\Controllers\\Main::home'
            )->bind('home');

            $factory->get(
                '/highfive',
                '\\Wunderbaum\\Controllers\\Main::highfive'
            )->bind('highfive');

            $factory->get(
                '/news-rss',
                '\\Wunderbaum\\Controllers\\Main::newsRss'
            )->bind('news-rss');

            $factory->get(
                '/news-json',
                '\\Wunderbaum\\Controllers\\Main::newsJson'
            )->bind('news-json');

            return $factory;
        }

        /**
         * Wunderbaum landing page
         *
         * @param Request $request
         * @param Application $app
         */
        public function home(Request $request, Application $app)
        {
            return $app['twig']->render('main/home.twig');
        }

        /**
         * "Top 5" charts page, files and hosts from a varnish log file
         *
         * @param Request $request
         * @param Application $app
         */
        public function highfive(Request $request, Application $app)
        {
            $parser = new VarnishParser();
            $parser->parseFile($app['resources']['varnish_log']);

            return $app['twig']->render('main/highfive.twig', [
                'hosts' => $parser->get('hosts'),
                'files' => $parser->get('files')
            ]);
        }

        /**
         * RSS news page
         *
         * @param Request $request
         * @param Application $app
         */
        public function newsRss(Request $request, Application $app)
        {
            $simplePie = new \SimplePie();
            $simplePie->set_feed_url($app['resources']['news_rss']);
            $simplePie->init();

            $items = $simplePie->get_items();

            return $app['twig']->render('main/news-rss.twig', [
                'items' => $items
            ]);
        }

        /**
         * Takes a weirdly formatted norwegian date and time and transforms it into a sortable time string
         *
         * @param $malformed_date Date formed as "<day> <norwegian_month_name> <year> <time>"
         *
         * @return string An understandable time string "<year><month><day><time>"
         */
        protected function extractSortableTimeFromMalformedNorwegianDate($malformed_date)
        {
            $months = array_flip(['Januar', 'Februar', 'Mars', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Desember']);
            list ($day, $month, $year, $time) = explode(' ', $malformed_date);

            return $year.$months[$month].$day.$time;
        }

        /**
         * JSON news page
         *
         * @param Request $request
         * @param Application $app
         */
        public function newsJson(Request $request, Application $app)
        {
            $items = json_decode(file_get_contents($app['resources']['news_json']));

            usort($items, function ($item_a, $item_b) {
                $a = $this->extractSortableTimeFromMalformedNorwegianDate($item_a->date.' '.$item_a->time);
                $b = $this->extractSortableTimeFromMalformedNorwegianDate($item_b->date.' '.$item_b->time);

                if ($a == $b) {
                    return 0;
                }
                return ($a > $b) ? -1 : 1;
            });

            return $app['twig']->render('main/news-json.twig', [
                'items' => $items
            ]);
        }

    }
