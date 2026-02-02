<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ChangeMeterContractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contractors = [
            ['first_name' => 'Junerey', 'middle_name' => 'Polenio', 'last_name' => 'Nabarquez', 'address' => 'LEYECO V'],
            ['first_name' => 'Jonbel', 'middle_name' => 'Alboro', 'last_name' => 'Niez', 'address' => 'LEYECO V'],
            ['first_name' => 'Raymart', 'middle_name' => 'Armenio', 'last_name' => 'Olorvida', 'address' => 'LEYECO V'],
            ['first_name' => 'Rosalito', 'middle_name' => 'Castillo', 'last_name' => 'Abao', 'address' => 'LEYECO V'],
            ['first_name' => 'Leodegario', 'middle_name' => 'Santana', 'last_name' => 'Oloverio', 'address' => 'LEYECO V'],
            ['first_name' => 'Rodelo', 'middle_name' => 'Orbiso', 'last_name' => 'Omega', 'address' => 'LEYECO V'],
            ['first_name' => 'Robert', 'middle_name' => 'Vera', 'last_name' => 'Abuyabor', 'address' => 'LEYECO V'],
            ['first_name' => 'Nole', 'middle_name' => 'Quisquisol', 'last_name' => 'Aguirnaldo', 'address' => 'LEYECO V'],
            ['first_name' => 'Lee-Ar', 'middle_name' => 'Jaravata', 'last_name' => 'Alcala', 'address' => 'LEYECO V'],
            ['first_name' => 'Jojie', 'middle_name' => 'Lumaad', 'last_name' => 'Orevillo', 'address' => 'LEYECO V'],
            ['first_name' => 'Porferio', 'middle_name' => 'Mabulay', 'last_name' => 'Otida', 'address' => 'LEYECO V'],
            ['first_name' => 'Jake', 'middle_name' => null, 'last_name' => 'Altar', 'address' => 'LEYECO V'],
            ['first_name' => 'Rico', 'middle_name' => 'Cata-ag', 'last_name' => 'Pabio', 'address' => 'LEYECO V'],
            ['first_name' => 'Jefrey', 'middle_name' => 'Matuguina', 'last_name' => 'Andrade', 'address' => 'LEYECO V'],
            ['first_name' => 'Gregorio', 'middle_name' => 'Laude', 'last_name' => 'Parrilla', 'address' => 'LEYECO V'],
            ['first_name' => 'Jessie Jay', 'middle_name' => 'Romero', 'last_name' => 'Anoos', 'address' => 'LEYECO V'],
            ['first_name' => 'Jundy', 'middle_name' => 'Calosor', 'last_name' => 'Pecajas', 'address' => 'LEYECO V'],
            ['first_name' => 'Joshua', 'middle_name' => 'Calosor', 'last_name' => 'Pecajas', 'address' => 'LEYECO V'],
            ['first_name' => 'Christopher', 'middle_name' => 'Velarde', 'last_name' => 'Perez', 'address' => 'LEYECO V'],
            ['first_name' => 'Willie', 'middle_name' => 'Sotto', 'last_name' => 'Peteros', 'address' => 'LEYECO V'],
            ['first_name' => 'Rio Keith', 'middle_name' => 'Haluag', 'last_name' => 'Planteras', 'address' => 'LEYECO V'],
            ['first_name' => 'Arnel', 'middle_name' => 'Rojas', 'last_name' => 'Pungtan', 'address' => 'LEYECO V'],
            ['first_name' => 'Boyet', 'middle_name' => 'Casia', 'last_name' => 'Remedio', 'address' => 'LEYECO V'],
            ['first_name' => 'Jonas John', 'middle_name' => 'Roldan', 'last_name' => 'Robaro', 'address' => 'LEYECO V'],
            ['first_name' => 'Reynaldo', 'middle_name' => 'Jorado', 'last_name' => 'Roble', 'address' => 'LEYECO V'],
            ['first_name' => 'Jonas', 'middle_name' => 'Sabandal', 'last_name' => 'Rufila', 'address' => 'LEYECO V'],
            ['first_name' => 'Reden', 'middle_name' => 'Malamdag', 'last_name' => 'Rufin', 'address' => 'LEYECO V'],
            ['first_name' => 'Oliver', 'middle_name' => 'Montajes', 'last_name' => 'Serato', 'address' => 'LEYECO V'],
            ['first_name' => 'Angelito', 'middle_name' => 'Abad', 'last_name' => 'Somalo', 'address' => 'LEYECO V'],
            ['first_name' => 'Anselmo', 'middle_name' => 'Justo', 'last_name' => 'Son', 'address' => 'LEYECO V'],
            ['first_name' => 'Jessie Rex', 'middle_name' => 'Sanoria', 'last_name' => 'Suan', 'address' => 'LEYECO V'],
            ['first_name' => 'Jordan', 'middle_name' => 'Roble', 'last_name' => 'Suganob', 'address' => 'LEYECO V'],
            ['first_name' => 'Junrey', 'middle_name' => 'Tallada', 'last_name' => 'Anoos', 'address' => 'LEYECO V'],
            ['first_name' => 'Jonel', 'middle_name' => 'Mercadal', 'last_name' => 'Talaboc', 'address' => 'LEYECO V'],
            ['first_name' => 'Ram', 'middle_name' => 'Bacalso', 'last_name' => 'Tampos', 'address' => 'LEYECO V'],
            ['first_name' => 'Armando', 'middle_name' => 'Patricio', 'last_name' => 'Tandoy', 'address' => 'LEYECO V'],
            ['first_name' => 'Emmanuel', 'middle_name' => 'Palacio', 'last_name' => 'Taneo', 'address' => 'LEYECO V'],
            ['first_name' => 'Orly', 'middle_name' => 'Guno', 'last_name' => 'Taripe', 'address' => 'LEYECO V'],
            ['first_name' => 'Alexis', 'middle_name' => 'Coral', 'last_name' => 'Arnejo', 'address' => 'LEYECO V'],
            ['first_name' => 'Restito', 'middle_name' => 'Jongko', 'last_name' => 'Veleganilao', 'address' => 'LEYECO V'],
            ['first_name' => 'Jeffrey', 'middle_name' => 'Pajaron', 'last_name' => 'Zaragoza', 'address' => 'LEYECO V'],
            ['first_name' => 'Christopher', 'middle_name' => 'Salazar', 'last_name' => 'Arnejo', 'address' => 'LEYECO V'],
            ['first_name' => 'Ernesto', 'middle_name' => 'Laude', 'last_name' => 'Bang-ay', 'address' => 'LEYECO V'],
            ['first_name' => 'Aldrin', 'middle_name' => 'Donayre', 'last_name' => 'Batac', 'address' => 'LEYECO V'],
            ['first_name' => 'Martjun', 'middle_name' => 'Quilisadio', 'last_name' => 'Batan', 'address' => 'LEYECO V'],
            ['first_name' => 'Franklin', 'middle_name' => 'Suralta', 'last_name' => 'Belacho', 'address' => 'LEYECO V'],
            ['first_name' => 'Obid', 'middle_name' => 'Sabugo', 'last_name' => 'Bolondro', 'address' => 'LEYECO V'],
            ['first_name' => 'Edison', 'middle_name' => 'Beron', 'last_name' => 'Broñola', 'address' => 'LEYECO V'],
            ['first_name' => 'Niel', 'middle_name' => 'Capote', 'last_name' => 'Denoy', 'address' => 'LEYECO V'],
            ['first_name' => 'Adelfo', 'middle_name' => 'Carlosita', 'last_name' => 'Cabaña', 'address' => 'LEYECO V'],
            ['first_name' => 'Johnrel', 'middle_name' => 'Garjas', 'last_name' => 'Cabrahan', 'address' => 'LEYECO V'],
            ['first_name' => 'Kenneth', 'middle_name' => 'Pintor', 'last_name' => 'Caceres', 'address' => 'LEYECO V'],
            ['first_name' => 'Edven', 'middle_name' => 'Ysorio', 'last_name' => 'Esmero', 'address' => 'LEYECO V'],
            ['first_name' => 'Ronald', 'middle_name' => 'Yurag', 'last_name' => 'Fajardo', 'address' => 'LEYECO V'],
            ['first_name' => 'Arjey', 'middle_name' => 'Larido', 'last_name' => 'Capuyan', 'address' => 'LEYECO V'],
            ['first_name' => 'Elmer', 'middle_name' => 'Fontilla', 'last_name' => 'Felicita', 'address' => 'LEYECO V'],
            ['first_name' => 'Mark Joseph', 'middle_name' => 'Ymas', 'last_name' => 'Capuyan', 'address' => 'LEYECO V'],
            ['first_name' => 'Marjun', 'middle_name' => 'Toñada', 'last_name' => 'Calimutan', 'address' => 'LEYECO V'],
            ['first_name' => 'Nolly', 'middle_name' => 'Balate', 'last_name' => 'Demeterio', 'address' => 'LEYECO V'],
            ['first_name' => 'Jonathan', 'middle_name' => 'Garcia', 'last_name' => 'Gonzaga', 'address' => 'LEYECO V'],
            ['first_name' => 'Mario', 'middle_name' => 'Salogaol', 'last_name' => 'Hortelano', 'address' => 'LEYECO V'],
            ['first_name' => 'Joel', 'middle_name' => 'Salino', 'last_name' => 'Collera', 'address' => 'LEYECO V'],
            ['first_name' => 'Ariel', 'middle_name' => 'Senillo', 'last_name' => 'Jandoc', 'address' => 'LEYECO V'],
            ['first_name' => 'Regie', 'middle_name' => 'Longakit', 'last_name' => 'Jugar', 'address' => 'LEYECO V'],
            ['first_name' => 'Godofredo', 'middle_name' => 'Watte', 'last_name' => 'Daprosa', 'address' => 'LEYECO V'],
            ['first_name' => 'Adonis', 'middle_name' => 'Villarmino', 'last_name' => 'Laurente', 'address' => 'LEYECO V'],
            ['first_name' => 'Maximo', 'middle_name' => 'Anasco', 'last_name' => 'Llamas', 'address' => 'LEYECO V'],
            ['first_name' => 'Glenn', 'middle_name' => 'Pepito', 'last_name' => 'Loreño', 'address' => 'LEYECO V'],
            ['first_name' => 'Jorge', 'middle_name' => 'Vasquez', 'last_name' => 'Luchavez', 'address' => 'LEYECO V'],
            ['first_name' => 'Randy', 'middle_name' => 'Labe', 'last_name' => 'Madrid', 'address' => 'LEYECO V'],
            ['first_name' => 'Esperlito', 'middle_name' => 'Lamoc', 'last_name' => 'Magdadaro', 'address' => 'LEYECO V'],
            ['first_name' => 'Arnel', 'middle_name' => 'Fuentes', 'last_name' => 'Malazarte', 'address' => 'LEYECO V'],
            ['first_name' => 'Kyle Leonard', 'middle_name' => 'Largo', 'last_name' => 'Malazarte', 'address' => 'LEYECO V'],
            ['first_name' => 'Julius', 'middle_name' => 'Ybanez', 'last_name' => 'Manacap', 'address' => 'LEYECO V'],
            ['first_name' => 'Jovanie', 'middle_name' => 'Jose', 'last_name' => 'Conejos', 'address' => 'LEYECO V'],
            ['first_name' => 'Albert', 'middle_name' => 'Reyes', 'last_name' => 'Matbangon', 'address' => 'LEYECO V'],
            ['first_name' => 'Arnel', 'middle_name' => 'Pandac', 'last_name' => 'Comprado', 'address' => 'LEYECO V'],
         ];
      
         foreach ($contractors as $contractor) {
                DB::table('change_meter_contractors')->insert([
                    'first_name' => strtoupper($contractor['first_name']),
                    'middle_name' => strtoupper($contractor['middle_name']),
                    'last_name' => strtoupper($contractor['last_name']),
                    'address' => $contractor['address'],
                    'mobile_number' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
         }
    }
}
