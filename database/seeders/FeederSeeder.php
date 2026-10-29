<?php

namespace Database\Seeders;

use App\Models\DataManagement\Area;
use App\Models\DataManagement\Feeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeederSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'AREA 1' => [
                '11' => 'From Cogon Rotonda going to Fatima to Jollibee Cogon going to Fatima-GGC Port- Brgy. Naungan',
                '15' => 'From Simangan S/S, Cogon going to City Hall-Portion of Brgy. Punta',
                '21' => 'From Talisayan Substation to Mahayag, Albuera',
                '22' => 'From Talisayan Substation to Brgy. Ipil DS',
                '23' => 'Pycegas, PSPI, San Miguel',
                '71' => 'Alta Vista S/S - Bonifacio Street- Don Felipe Hotel; Part of Hermosilla Drive; J Navarro Street & Brgy. Alta Vista - Brgy. Patag, Ormoc City; Cantubo Bridge to Robinsons Mall to So. Sumag, Brgy. Cogon, Ormoc City',
                '72' => 'Alta Vista S/S - Real Street - Public Market - Brgy. Alegria-Punta; Rizal Street to BDO; Mabini Street- Portions of Brgy. Punta, Ormoc City',
                '73' => 'Alta Vista S/S - Carlota Hills - OSPA; Can-adieng to Brgy. Ipil DS',
                '74' => 'Alta Vista Substation - San Pablo Street - Old Food Park ; Lopez Jaena Street - Cor. Bonifactio - Lopez Jaena; San Pedro Street & Agua Dulce Street',
            ],
            'AREA 2' => [
                '14' => 'From ODH to San Juan DS',
                '31' => 'From Mahayag S/S to Brgy. Tabunok, Isabel',
                '32' => 'From Mahayag S/S to San Juan DS',
                '34' => 'From Mahayag S/S to LMC',
            ],
            'AREA 3' => [
                '81' => 'From Palompon S/S to Brgy. Poblacion, Palompon',
                '82' => 'From Palompon S/S to Payao Recloser & Sandayong DS',
                '83' => 'From Palompon S/S to Liberty to Tabunok Cut-out',
            ],
            'AREA 4' => [
                '13' => 'From Simangan S/S to Milagro, Cabintan, Tongonan, Ormoc, Lake Danao up to Liberty',
                '16' => 'From Simangan S/S going to Cagbuhangin, ABS',
                '61' => 'From Libongao S/S- Kananga Proper- Brgy. Lonoy, Kananga',
                '62' => 'From Libongao S/S- Entire Matag-ob, Leyte and its covered barangays',
                '63' => 'From Crossing Libongao- Brgy.Valencia, Ormoc City- Brgy. Cagbuhangin ABS',
            ],
            'AREA 5' => [
                '41' => 'From Sambulawan S/S to Poblacion, Leyte, Leyte to Culasi, Leyte, Leyte',
                '42' => 'Municipality of Calubian, Leyte and its covered barangays',
                '43' => 'From Sambulawan S/S to Brgy. Belen, Leyte, Leyte',
                '51' => 'From Tabango S/S- Proper Tabango- Payao Recloser',
                '52' => 'From Tambis S/S- Tabing, Tabango- Tugas, Tabango- Linao, San Isidro',
            ],
        ];

        foreach ($data as $areaName => $feeders) {
            $area = Area::firstOrCreate(['name' => $areaName]);

            foreach ($feeders as $feederName => $coverage) {
                Feeder::updateOrCreate(
                    ['feeder_name' => $feederName],
                    ['area_id' => $area->id, 'coverage_name' => $coverage]
                );
            }
        }
    }
}
