<?php

    namespace Wunderbaum\Utils;

    use Kassner\LogParser\LogParser;

    /**
     * Class to parse a varnish log file and extract meaningful statistics
     *
     * @package Wunderbaum\Utils
     */
    class VarnishParser extends LogParser
    {
        protected $items = null;

        protected $statistics = [];

        /**
         * VarnishParser constructor. Initializes the parser and adds some custom format parsers
         */
        public function __construct()
        {
            parent::__construct();
            $this->addPattern('%m', '(?P<requestMethod>OPTIONS|GET|HEAD|POST|PUT|PURGE|DELETE|TRACE|CONNECT)');
            $this->addPattern('%r', '(?P<request>(.+?))');
            $this->addPattern('%>u', '(?P<userAgent>.+?)');
            $this->addPattern('%R', '(?P<referrer>.+?)');
            $this->addPattern('%P', '(?P<requestProtocol>HTTP/1.(?:0|1))');
            $this->setFormat('%a %l %b %t "%m %r %P" %>s %O "%R" "%>u"');
        }

        /**
         * Parse an entire varnish log file and extract items into an array
         *
         * @param $file The full path of the file to parse
         */
        public function parseFile($file)
        {
            $log = file($file);
            $items = [];

            foreach ($log as $line) {
                $items[] = $this->parse($line);
            }

            $this->items = $items;
            $this->generateStatistics();
        }

        /**
         * Rank / sort items parsed from the log
         *
         * @see parseFile()
         */
        public function generateStatistics()
        {
            if ($this->items === null) {
                throw new \Exception('You have to parse a file first');
            }

            $this->statistics = [
                'hosts' => [],
                'files' => []
            ];

            array_walk($this->items, function ($item) {
                $url = parse_url($item->request);
                $host = $url['host'];
                $file = $host . $url['path'];

                if (isset($this->statistics['hosts'][$host])) {
                    $this->statistics['hosts'][$host] += 1;
                } else {
                    $this->statistics['hosts'][$host] = 1;
                }

                if ($item->requestMethod == 'GET' && strpos($url['path'], '.') !== false) {
                    if (isset($this->statistics['files'][$file])) {
                        $this->statistics['files'][$file] += 1;
                    } else {
                        $this->statistics['files'][$file] = 1;
                    }
                }
            });

            arsort($this->statistics['files']);
            arsort($this->statistics['hosts']);
        }

        /**
         * Retrieve an element from the generated statistics
         *
         * @param $key Name of the statistic to retrieve
         *
         * @return array An array of elements with associated statistics
         * @throws \Exception
         */
        public function get($key)
        {
            if (!isset($this->statistics[$key])) {
                throw new \Exception('This statistics is not generated');
            }

            return $this->statistics[$key];
        }

    }
