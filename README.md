# fuzzy-tsukamoto
1. installation
```
composer require kematjaya/fuzzy-tsukamoto
```
2. create curve

```
...
use Kematjaya\FuzzyTsukamoto\Curve\DownCurve;
use Kematjaya\FuzzyTsukamoto\Curve\UpCurve;
use Kematjaya\FuzzyTsukamoto\Curve\TriagleCurve;
use Kematjaya\FuzzyTsukamoto\CurveBuilder;
...
...
$value = 3000000; // value will be test
$kurvaGaji = (new CurveBuilder())
        ->addCurve('sedikit', (new DownCurve($value))->setMin(2000000)->setMax(4000000))
        ->addCurve('banyak', (new UpCurve($value))->setMin(3000000)->setMax(5000000));


$value = 4; // example in year
$kurvaMasaKerja = (new CurveBuilder())
        ->addCurve('baru', (new DownCurve($value))->setMin(2)->setMax(5))
        ->addCurve('sedang', (new TriagleCurve($value))->setMedium(5)->setMin(3)->setMax(7))
        ->addCurve('lama', (new UpCurve($value))->setMin(5)->setMax(8));

$kurvaBonus = (new CurveBuilder()) // output curve
    ->addCurve('sedikit', (new DownCurve())->setMin(300000)->setMax(600000))
    ->addCurve('banyak', (new UpCurve())->setMin(300000)->setMax(600000));
```

3. ADD curve to Tsukamoto object
```
use Kematjaya\FuzzyTsukamoto\CurveBuilder;
use Kematjaya\FuzzyTsukamoto\FuzzyTsukamoto;
...
...
$tsukamoto = new FuzzyTsukamoto();
$tsukamoto->addDecisionCurve('gaji', $kurvaGaji)
        ->addDecisionCurve('kerja', $kurvaKerja)
        ->setOutputCurve($kurvaBonus);
```

4. Create Rules and process
```
...
use Kematjaya\FuzzyTsukamoto\RuleBuilder;

...

$ruleBuilder = new RuleBuilder();
$ruleBuilder->startWhen(1)
        ->andWhen('kerja', 'baru')
        ->andWhen("gaji", 'sedikit')
        ->then('sedikit');

$ruleBuilder->startWhen(2)
        ->andWhen('kerja', 'baru')
        ->andWhen("gaji", 'banyak')
        ->then('sedikit');

$ruleBuilder->startWhen(3)
        ->andWhen('kerja', 'sedang')
        ->andWhen("gaji", 'sedikit')
        ->then('sedikit');

$ruleBuilder->startWhen(4)
        ->andWhen('kerja', 'sedang')
        ->andWhen("gaji", 'banyak')
        ->then('banyak');

$ruleBuilder->startWhen(5)
        ->andWhen('kerja', 'lama')
        ->andWhen("gaji", 'sedikit')
        ->then('banyak');

$ruleBuilder->startWhen(6)
        ->andWhen('kerja', 'lama')
        ->andWhen("gaji", 'banyak')
        ->then('banyak');

$result = $tsukamoto->calculated($ruleBuilder);
```
5. Get result
```
$tsukamoto->calculated($ruleBuilder);
$tsukamoto->getOutput();// return float
```


Reference:
https://www.academia.edu/9877842/Perhitungan_Bonus_Karyawan_dengan_Metode_Fuzzy_Tsukamoto_Berbasis_Android
