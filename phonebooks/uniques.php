<?php
// @author: C.A.D. BONDJE DOUE
// @filename: uniques.php
// @date: 20251221 14:08:43
// @desc: test uniques all phone book
// @command: balafon --run .test/phonebooks/uniques.php
use IGK\Database\DbQueryCondition;
use IGK\Database\Macros\PhoneBooksMacros;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\Models\PhoneBookEntries;
use IGK\Models\PhoneBooks;
use IGK\Models\PhoneBookTypes;
use IGK\Services\IAppService;
use IGK\System\Console\Logger;
use IGK\System\Database\IPhoneBookDetailVisitor;

/**
* auto generate doc.
*/
class MyVisitor implements IPhoneBookDetailVisitor
{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $name;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $d;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $x;
    /**
    * .ctr
    * @param mixed $t
    */
    function __construct($t)
    {
        $this->name = $t;
    }
    /**
    * auto generate doc.
    * @param string $propertyName
    * @param mixed $value
    * @param mixed $oldvalue
    * @param null|mixed $p
    */
    public function visit(string $propertyName, $value, $oldvalue, $p = null)
    {
        $s = $this->d . ' ' . $this->x;
        return 'aa';
    }
}
/**
* auto generate doc.
*/
class V2D implements IPhoneBookDetailVisitor, IAppService
{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $x;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $y;
    /**
    * auto generate doc.
    * @return array
    */
    public function getConfigurableProperties(): array
    {
        return [
            'x',
            'y'
        ];
    }
    /**
    * auto generate doc.
    * @param null|mixed $configs
    * @return bool
    */
    public function init($configs = null): bool
    {
        if ($configs) {
            list($x, $y) = igk_extract($configs, 'x|y');
            $this->x = $x;
            $this->y = $y;
        }
        return true;
    }
    /**
    * auto generate doc.
    * @param string $propertyName
    * @param mixed $value
    * @param mixed $oldvalue
    * @param null|mixed $cardinality
    */
    public function visit(string $propertyName, $value, $oldvalue, $cardinality = null)
    {
        $v = $value;
        $n = $propertyName;
        if (isset($oldvalue)) {
            $g = $oldvalue;
            if (!is_array($g)) {
                $g = [$g];
            }
            $g[] = $v;
            if ($cardinality > 0) {
                if (count($g) > $cardinality) {
                    igk_die('detail exceeds');
                }
            }
            $v = $g;
        }
        return $this->x.':'.$v;
    }
}
IGKServices::Register(IPhoneBookDetailVisitor::class, V2D::class);
$uniques = [];
$T1 = PhoneBooks::class;
$cond = [];
foreach (explode("|", 'gsm|tel|phone') as $t) {
    if ($r = PhoneBookTypes::GetCache(PhoneBookTypes::FD_NAME, $t)) {
        $bt_id = $r->Id;
        $cond[] = [$T1::FD_TYPE, $bt_id];
    }
}
$rows = PhoneBooks::select_all(
    [
        DbQueryCondition::Create($cond, DbQueryCondition::OP_OR)
    ]
);
$duplicate = [];
foreach ($rows as $r) {
    $v = $r->Value;
    if ($v && preg_match("/^\+\\d+/", $v)) {
        if (!isset($uniques[$v])) {
            $uniques[$v] = [];
        } else {
            if (!isset($duplicate[$v])) {
                $duplicate[$v] = (object)[
                    'first' => [$uniques[$v][0]->EntryGuid => PhoneBooksMacros::getPhoneDetails($uniques[$v][0])],
                    'count' => 0,
                    'owner' => [],
                    'ids' => [],
                ];
            }
            $duplicate[$v]->count++;
            $duplicate[$v]->ids[$r->EntryGuid] = PhoneBooksMacros::getPhoneDetails($r);
        }
        $uniques[$v][] = $r; 
    } else {
        Logger::danger('missing. ');
        $r->delete();
    }
    $d = $r->getPhoneDetails();
    igk_wln_e($d);
}
ksort($duplicate);
$bsic = [];
foreach ($duplicate as $c) {
    $q = $c->first;
    $g = key($q);
    $v = $q[$g];
    foreach ($c->ids as $key => $value) {
        if ($value == $v) {
            Logger::warn(sprintf('remove duplicate [%s]', $value->display()));
            $s = PhoneBooks::delete([
                PhoneBooks::FD_ENTRY_GUID => $key
            ]);
            PhoneBookEntries::delete([
                PhoneBookEntries::FD_GUID => $key
            ]);
        } else {
            Logger::info('missing value ' . $value->display() . ' for ' . $key . '=>' . JSon::Encode($value, JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $bsic[$key] = $value;
        }
    }
}
Logger::success('done');
exit;