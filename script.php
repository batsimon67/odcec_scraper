<?php
require __DIR__ . '/vendor/autoload.php';
use PHPHtmlParser\Dom;

class Scraper {
    private $page = 96;
    private $list = [];

    public function run() {
        $dom = new Dom;

        do {
            $url = "https://www.odcec.ct.it/iscritto/scroll_list/?p={$this->page}";
            $dom->loadFromUrl($url);

            if ($this->checkRecords($dom)) {
                foreach ($dom->find('article') as $article) {
                    $profile_url = "https://www.odcec.ct.it" . $article->find('a')[0]->href;
                    $profile_dom = new Dom;
                    $profile_dom->loadFromUrl($profile_url);

                    $scheda = $profile_dom->find('.iscritto');
                    if ($scheda) {
                        $array = [
                            'name' => $scheda->find('p')[1] ? $scheda->find('p')[1]->text : '',
                            'birth_date' => $scheda->find('span')[2] ? $scheda->find('span')[2]->text : '',
                            'title' => $scheda->find('span')[3] ? $scheda->find('span')[3]->text : '',
                            'status' => $scheda->find('span')[9] ? $scheda->find('span')[9]->text : '',
                            'address' => $scheda->find('span')[11] ? $scheda->find('span')[11]->text : '',
                            'phone' => $scheda->find('span')[12] ? $scheda->find('span')[12]->text : '',
                            'email' => $scheda->find('span')[14] ? $scheda->find('span')[14]->text : '',
                            'pec' => $scheda->find('span')[15] ? $scheda->find('span')[15]->text : '',
                        ];
                        $this->list[] = $array;
                    }
                }
            } else {
                break;
            }
            $this->page++;

        } while ($this->checkRecords($dom));

        print_r($this->list);
    }

    private function checkRecords($dom) {
        return $dom && $dom->find('article') && isset($dom->find('article')[0]);
    }
}

$scraper = new Scraper();
$scraper->run();
?>
