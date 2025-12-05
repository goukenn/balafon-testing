<?php
// @author: C.A.D. BONDJE DOUE
// @filename: get_modeles.php
// @date: 20230301 09:43:45
// @desc: script
// @command: balafon --run .test/ebc/scripts/get_modeles.php
// + | --------------------------------------------------------------------
// + | retrieve marque form tags => to array for cbs project 
// + | --------------------------------------------------------------------
use IGK\System\IO\StringBuilder;
$sb = new StringBuilder;
if (!function_exists('igk_html_get_options_key')){
    function igk_html_get_options_key(string $v):?array{
            $d = igk_create_node("div");
            if ($d->load($v)){
                $reftab = [];
            array_map(function($a)use(& $reftab){
                $v = $a->getAttribute('value');
                $c = $a->getContent();
                if (is_null($v)){
                    $v = 0;
                } 
                $reftab[$c]=['key'=>$c,'value'=>$v];
            }, $d->getElementsByTagName("option"));
            return $reftab;
        }
        return null;
    }
}
$def = igk_html_get_options_key('<option value="1">9ff</option><option value="2">ABARTH</option><option value="3">AC</option><option value="4">Acura</option><option value="5">Aixam</option><option value="6">Alfa Romeo</option><option value="7">Alpina</option><option value="8">Amphicars</option><option value="9">Aston Martin</option><option value="10">Audi</option><option value="11">Austin</option><option value="12">Autobianchi</option><option value="13">Bellier</option><option value="14">Bentley</option><option value="15">BMW</option><option value="16">Bugatti</option><option value="17">Buick</option><option value="18">BYD</option><option value="19">Cadillac</option><option value="20">Casalini</option><option value="21">Caterham</option><option value="22">Changhe</option><option value="23">Chatenet</option><option value="24">Chery</option><option value="25">Chevrolet</option><option value="26">Chrysler</option><option value="27">Citroen</option><option value="28">Courb</option><option value="29">Dacia</option><option value="30">Daewoo</option><option value="31">Daihatsu</option><option value="32">Daimler</option><option value="33">Datsun</option><option value="34">De Tomaso</option><option value="35">Derways</option><option value="36">Dodge</option><option value="37">Donkervoort</option><option value="38">DR Motor</option><option value="39">DS Citroen</option><option value="40">Edran</option><option value="41">Ferrari</option><option value="42">Fiat</option><option value="43">Fisker</option><option value="44">Ford</option><option value="45">Gac Genow</option><option value="46">Geely</option><option value="47">GEM Polaris</option><option value="48">Gemballa</option><option value="49">Gillet</option><option value="50">Ginetta</option><option value="51">GMC</option><option value="52">Great Wall</option><option value="53">Haima</option><option value="54">Hamann</option><option value="55">Honda</option><option value="56">Hummer</option><option value="57">Hyundai</option><option value="58">Infiniti</option><option value="59">Innocenti</option><option value="60">Isuzu</option><option value="61">Iveco</option><option value="62">Jaguar</option><option value="63">Jeep</option><option value="64">Kia</option><option value="65">KTM X-BOW</option><option value="66">Lada</option><option value="67">Lamborghini</option><option value="68">Lancia</option><option value="69">Land Rover</option><option value="70">Lexus</option><option value="71">Ligier</option><option value="72">Lincoln</option><option value="73">Lotus</option><option value="74">Marcos</option><option value="75">Maserati</option><option value="76">Maybach</option><option value="77">Mazda</option><option value="78">McLaren</option><option value="79">Mercedes Benz</option><option value="80">Mercury</option><option value="81">MG</option><option value="82">Microcar</option><option value="83">Mini</option><option value="84">Mitsubishi</option><option value="85">Morgan</option><option value="86">MP Lafer</option><option value="87">Nash</option><option value="88">Nissan</option><option value="89">Oldsmobile</option><option value="90">Opel</option><option value="91">Pagani</option><option value="92">Panther</option><option value="93">Peugeot</option><option value="94">Pgo</option><option value="95">Piaggio</option><option value="96">Plymouth</option><option value="97">Polaris</option><option value="98">Pontiac</option><option value="99">Porsche</option><option value="100">Proton</option><option value="101">Renault</option><option value="102">Rolls-Royce</option><option value="103">Rover</option><option value="104">RUF Auto</option><option value="105">Saab</option><option value="106">Seat</option><option value="107">Skoda</option><option value="108">Smart</option><option value="109">SpeedArt Porsche</option><option value="110">Spyker</option><option value="111">Ssang Yong</option><option value="112">Subaru</option><option value="113">Suzuki</option><option value="114">T.V.R</option><option value="115">Talbot</option><option value="116">Tata</option><option value="117">Tazzari</option><option value="118">Techart</option><option value="119">Teener</option><option value="120">Tesla</option><option value="121">Toyota</option><option value="122">Trabant</option><option value="123">Triumph</option><option value="124">Vauxhall</option><option value="125">Venturi</option><option value="126">Volkswagen</option><option value="127">Volvo</option><option value="128">Westfield</option><option value="129">Wiesmann</option><option value="130">Zastava</option>');
$index = 0;
$gout = [];
foreach($def as $k=>$v){
    $index = $v['value'];
    if (!$index){
        continue;
    }
    $uri = "https://www.autoimpex.be/rechargeselect.php?marque=".$index;
    if ($content = igk_curl_post_uri($uri)){
        $r = igk_html_get_options_key($content);
        array_map(function($a)use($k, & $gout){
            if ($a["value"]==0){
                return;
            }
            if (!isset($gout[$k])){
                $gout[$k] = [];
            }
            $kt = $a['key'];
            $gout[$k][$kt] = $kt; 
        }, $r); 
        if (isset($gout[$k]))
        $gout[$k] = array_values($gout[$k]);
    } 
}
echo igk_map_array_to_str($gout);
//Logger::success('done');
exit;