# Užduotis
## Situacija

Banko naudotojai gali ateiti į skyrių įnešti bei išsigryninti pinigų. Palaikomos kelios valiutos. Taip pat taikomi tam tikri komisiniai mokesčiai tiek už pinigų įnešimą, tiek ir už išgryninimą.

## Komisiniai mokesčiai

### Pinigų įnešimas

Komisinis mokestis - 0.03% nuo sumos, ne daugiau 5.00 EUR.

### Pinigų išgryninimas

Taikomi skirtingi komisiniai mokesčiai fiziniams ir juridiniams asmenims.

#### Fiziniams asmenims

Įprastas komisinis - 0.3 % nuo sumos.

1000.00 EUR per savaitę (nuo pirmadienio iki sekmadienio) galima išsiimti nemokamai.

Jei suma viršijama - komisinis skaičiuojamas tik nuo viršytos sumos (t.y. vis dar galioja 1000 EUR be komiso).

Ši nuolaida taikoma tik pirmoms 3 išėmimo operacijoms per savaitę - jei išsiimama 4-tą ir paskesnius kartus, komisinis toms operacijoms skaičiuojamas įprastai - taisyklė dėl 1000 EUR galioja tik pirmiesiems trims išgryninimams.

#### Juridiniams asmenims

Komisinis mokestis - 0.3% nuo sumos, bet ne mažiau nei 0.50 EUR.

### Komisinio mokesčio valiuta

Komisinis mokestis visuomet skaičiuojamas ta valiuta, kuria atliekama operacija (pvz. išsiimant `USD`, komisinis taip pat būna `USD` valiuta).

### Apvalinimas

Paskaičiavus komisinį mokestį, jis apvalinamas mažiausio valiutos vieneto (pvz. `EUR` valiutai - centų) tikslumu į didžiąją pusę (`0.023 EUR` apvalinasi į `3` Euro centus).

Apvalinimas atliekamas jau po konvertavimo.

## Palaikomos valiutos

Palaikomos 3 valiutos: `EUR`, `USD` ir `JPY`.

Konvertuojant valiutas, taikomi tokie konvertavimo kursai: `EUR:USD` - `1:1.1497`, `EUR:JPY` - `1:129.53`

## Įeities duomenys

Įeities duomenys pateikiami CSV faile. Faile nurodomos vykdytos operacijos. Kiekvienoje eilutėje nurodomi tokie duomenys:
- operacijos data, formatas `Y-m-d`
- naudotojo identifikatorius, skaičius
- naudotojo tipas, vienas iš `natural` (fizinis asmuo) arba `legal` (juridinis asmuo)
- operacijos tipas, vienas iš `cash_in` (įnešimas) arba `cash_out` (išgryninimas)
- operacijos suma (pvz. `2.12` ar `3`)
- operacijos valiuta, vienas iš `EUR`, `USD`, `JPY`

Visos operacijos išrikiuotos jų atlikimo tvarka, tačiau gali apimti kelių metų intervalą.

## Laukiamas rezultatas

Programa turi kaip vienintelį argumentą priimti kelią iki įeities duomenų failo.

Programa rezultatą turi pateikti į `stdout`.

Rezultatas - paskaičiuoti komisiniai mokesčiai kiekvienai operacijai. Kiekvienoje eilutėje reikia pateikti tik galutinę komisinio mokesčio sumą be valiutos.

# Pavyzdiniai duomenys

```
➜  cat input.csv
2015-01-01,1,natural,cash_out,1200.00,EUR
2015-12-31,1,natural,cash_out,1000.00,EUR
2016-01-01,1,natural,cash_out,1000.00,EUR
2016-01-05,2,natural,cash_in,200.00,EUR
2016-01-06,3,legal,cash_out,300.00,EUR
2016-01-06,2,natural,cash_out,30000,JPY
2016-01-07,2,natural,cash_out,1000.00,EUR
2016-01-07,2,natural,cash_out,100.00,USD
2016-01-10,2,natural,cash_out,100.00,EUR
2016-01-10,3,legal,cash_in,1000000.00,EUR
2016-01-10,4,natural,cash_out,1000.00,EUR
2016-02-15,2,natural,cash_out,300.00,EUR
2016-02-19,2,natural,cash_out,3000000,JPY
➜  php script.php input.csv
0.60
0.00
3.00
0.06
0.90
0
0.70
0.30
0.30
5.00
0.00
0.00
8728
```