<?php

/**
 * Created by PhpStorm.
 * User: prrng
 * Date: 07/06/24
 * Time: 14:57
 */
class AngkaKeKata
{
    const POSTFIX_BELASAN = 'belas';
    const APPENDIX_PULUHAN = 'puluh';
    const POSTFIX_PULUHAN = self::APPENDIX_PULUHAN;
    const APPENDIX_RATUSAN = 'ratus';
    const POSTFIX_RATUSAN = self::APPENDIX_RATUSAN;
    const APPENDIX_RIBUAN = 'ribu';
    const POSTFIX_RIBUAN = self::APPENDIX_RIBUAN;
    const APPENDIX_JUTAAN = 'juta';
    const POSTFIX_JUTAAN = self::APPENDIX_JUTAAN;

    private $arrayKataAngkaSatuan = [
        '1' => 'satu',
        '2' => 'dua',
        '3' => 'tiga',
        '4' => 'empat',
        '5' => 'lima',
        '6' => 'enam',
        '7' => 'tujuh',
        '8' => 'delapan',
        '9' => 'sembilan',
        '0' => 'nol'
    ];

    private $arrayKataAngkaSpesial = [
        '10' => 'sepuluh',
        '11' => 'sebelas',
        '100' => 'seratus',
        '1000' => 'seribu'
    ];

    function __construct()
    {
    }

    /**
     * @param $var
     * @return mixed
     */
    private function konversiSatuanKeKata($var)
    {
        return $this->arrayKataAngkaSatuan[$var];
    }

    /**
     * @param $var
     * @return mixed|string
     */
    private function konversiPuluhanKeKata($var)
    {
        $var = (int)$var;
        if (strlen($var) < 2) {
            return $this->konversiSatuanKeKata($var);
        }
        if ($var == 10 || $var == 11) {
            return $this->arrayKataAngkaSpesial[$var];
        } elseif ($var > 11 && $var < 20) {
            return $this->konversiSatuanKeKata((int)substr($var, 1, 1)) . " " . self::POSTFIX_BELASAN;
        } else {
            $modulo = $var % 10;
            return $modulo == 0 ? $this->konversiSatuanKeKata((int)substr($var, 0, 1)) . " " . self::POSTFIX_PULUHAN : $this->konversiSatuanKeKata((int)substr($var, 0, 1)) . " " . self::APPENDIX_PULUHAN . " " . $this->konversiSatuanKeKata($modulo);
        }
    }

    /**
     * @param $var
     * @return mixed|string
     */
    private function konversiRatusanKeKata($var)
    {
        $var = (int)$var;
        if (strlen($var) < 3) {
            return $this->konversiPuluhanKeKata($var);
        }
        $bilanganRatusan = (int)substr($var, 0, 1);
        $bilanganSatuan = (int)substr($var, 2, 1);
        $angkaPuluhan = (int)substr($var, 1, 2);
        if ($var < 200) {
            $kata = $this->arrayKataAngkaSpesial[100];
            if ($var < 101) {
                return $kata;
            }
        } else {
            $kata = $this->konversiSatuanKeKata($bilanganRatusan) . " " . self::APPENDIX_RATUSAN;
        }
        if ($var >= 101 && $var < 110) {
            return $kata . " " . $this->konversiSatuanKeKata($bilanganSatuan);
        } elseif (($var % 100) == 0) {
            return $this->konversiSatuanKeKata($bilanganRatusan) . " " . self::POSTFIX_RATUSAN;
        } else {
            return $angkaPuluhan >= 10 ? $kata . " " . $this->konversiPuluhanKeKata($angkaPuluhan) : $kata . " " . $this->konversiSatuanKeKata($angkaPuluhan);
        }
    }

    /**
     * @param $var
     * @return mixed|string
     */
    private function konversiRibuanKeKata($var)
    {
        $var = (int)$var;
        $jumlahKarakter = strlen($var);
        if ($jumlahKarakter == 6) {
            return $this->konversiRatusanRibuanKeKata($var);
        } elseif ($jumlahKarakter == 5) {
            return $this->konversiPuluhanRibuanKeKata($var);
        } elseif ($jumlahKarakter == 4) {
            $bilanganRibuan = (int)substr($var, 0, 1);
            $moduloSeribu = (int)$var % 1000;
            if ($var < 2000) {
                $kata = $this->arrayKataAngkaSpesial[1000];
                if ($moduloSeribu == 0) {
                    return $kata;
                }
            } else {
                $kata = $this->konversiSatuanKeKata($bilanganRibuan) . " " . self::APPENDIX_RIBUAN;
                if ($moduloSeribu == 0) {
                    return $this->konversiSatuanKeKata($bilanganRibuan) . " " . self::POSTFIX_RIBUAN;
                }
            }
            return $kata . " " . $this->convert($moduloSeribu);
        } else {
            if ($jumlahKarakter < 4) {
                return $this->konversiRatusanKeKata($var);
            } else {
                die ("gawat: " . __METHOD__);
            }
        }
    }

    /**
     * @param $var
     * @return string
     */
    private function konversiPuluhanRibuanKeKata($var)
    {
        $moduloSepuluhRibu = (int)$var % 10000;
        return $moduloSepuluhRibu == 0 ? $this->konversiPuluhanKeKata((int)substr($var, 0, 2)) . " " . self::POSTFIX_RIBUAN : $this->konversiPuluhanKeKata((int)substr($var, 0, 2)) . " " . self::APPENDIX_RIBUAN . " " . $this->konversiRatusanKeKata((int)substr($var, 2, 3));
    }

    /**
     * @param $var
     * @return string
     */
    private function konversiRatusanRibuanKeKata($var)
    {
        $moduloSeratusRibu = (int)$var % 100000;
        return $moduloSeratusRibu == 0 ? $this->konversiRatusanKeKata((int)substr($var, 0, 3)) . " " . self::POSTFIX_RIBUAN : $this->konversiRatusanKeKata((int)substr($var, 0, 3)) . " " . self::APPENDIX_RIBUAN . " " . $this->konversiRatusanKeKata((int)substr($var, 3, 3));
    }

    /**
     * @param $var
     * @return string
     */
    private function konversiJutaanKeKata($var)
    {
        $var = (int)$var;
        $jumlahKarakter = strlen($var);
        switch ($jumlahKarakter) {
            case 7:
                $modulo = $var % 1000000;
                return $modulo == 0 ? $this->konversiSatuanKeKata((int)substr($var, 0, 1)) . " " . self::POSTFIX_JUTAAN : $this->konversiSatuanKeKata((int)substr($var, 0, 1)) . " " . self::APPENDIX_JUTAAN . " " . $this->konversiRibuanKeKata((int)substr($var, 1, 6));
                break;
            case 8:
                $modulo = $var % 10000000;
                return $modulo == 0 ? $this->konversiPuluhanKeKata((int)substr($var, 0, 2)) . " " . self::POSTFIX_JUTAAN : $this->konversiPuluhanKeKata((int)substr($var, 0, 2)) . " " . self::APPENDIX_JUTAAN . " " . $this->konversiRibuanKeKata((int)substr($var, 2, 6));
                break;
            case 9:
                $modulo = $var % 100000000;
                return $modulo == 0 ? $this->konversiRatusanKeKata((int)substr($var, 0, 3)) . " " . self::POSTFIX_JUTAAN : $this->konversiRatusanKeKata((int)substr($var, 0, 3)) . " " . self::APPENDIX_JUTAAN . " " . $this->konversiRibuanKeKata((int)substr($var, 3, 6));
                break;
            default:
                die ("gawat: " . __METHOD__);
                break;
        }
    }

    /**
     * @param string $var
     * @return mixed|string
     */
    public function convert($var = "")
    {                                        //cek desimal
        if (!is_numeric($var) || $var < 0 || ($var - (int)$var) != 0) {
            return 'karakter masukan harus bilangan bulat positif';
        }

        /*TODO: available for double
        init kata after comma = ""
        if(is_double($var)){
            new var = all chars before , or .
            after koma = (int)substring after koma
            if after koma > 0 : init kata after koma + "koma" + angkaKeKata(after koma)
        }*/


        $number = is_int($var) ? $var : (int)$var;
        $jumlahKarakter = strlen($number);
        if ($jumlahKarakter == 1) {
            return $this->konversiSatuanKeKata($number);
        } elseif ($jumlahKarakter == 2) {
            return $this->konversiPuluhanKeKata($number);
        } elseif ($jumlahKarakter == 3) {
            return $this->konversiRatusanKeKata($number);
        } elseif ($jumlahKarakter >= 4 && $jumlahKarakter <= 6) {
            return $this->konversiRibuanKeKata($number);
        } elseif ($jumlahKarakter >= 7 && $jumlahKarakter <= 9) {
            return $this->konversiJutaanKeKata($number);
        } else {
            return 'angka > 999juta nunggu waktu luang di ramadhan tahun depan';
        }
    }

}