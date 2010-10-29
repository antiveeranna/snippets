<?php
class TopFunctions extends SplHeap
{
    private $compare_key;

    public function __construct ($compare_key)
    {
        $this->compare_key = $compare_key;
    }


    protected function compare ($item1, $item2)
    {
        if ($item1[$this->compare_key] > $item2[$this->compare_key]) {
            return 1;
        }

        if ($item1[$this->compare_key] == $item2[$this->compare_key]) {
            return 0;
        }

        return -1;
    }
}

$Top = new TopFunctions('wt');

$items = unserialize(file_get_contents('4cc98a9d64d8e.xhprof_foo'));
$aggregated = array();

foreach ($items as $key => $item)
{
    $splitter = strpos($key, '==>');
    $splitter = $splitter === false ? 0 : $splitter+3;
    $realname = substr($key, $splitter);

    if (!isset($aggregated[$realname])) {
        $aggregated[$realname] = array('wt' => 0, 'ct' => 0);
    }

    $aggregated[$realname]['wt'] += $item['wt'];
    $aggregated[$realname]['ct'] += $item['ct'];
}

foreach ($aggregated as $key => $item) {
    $item['name'] = $key;
    $Top->insert($item);
}

for ($i = 0; $i <= 10; $i++) {
    var_dump($Top->extract());
}
