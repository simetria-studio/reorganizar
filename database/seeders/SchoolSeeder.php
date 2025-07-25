<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Escola Estadual Prof. João Silva',
                'code' => 'ESC001',
                'cnpj' => '12345678000195',
                'phone' => '11987654321',
                'email' => 'contato@escolajoaosilva.edu.br',
                'website' => 'https://www.escolajoaosilva.edu.br',
                'description' => 'Escola pública estadual com foco em educação básica de qualidade.',
                'type' => 'estadual',
                'level' => 'fundamental',
                'active' => true,
                'street' => 'Rua das Flores',
                'number' => '123',
                'complement' => null,
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01234567',
                'country' => 'Brasil',
                'latitude' => -23.5505,
                'longitude' => -46.6333,
            ],
            [
                'name' => 'Colégio Particular Santa Maria',
                'code' => 'ESC002',
                'cnpj' => '98765432000186',
                'phone' => '21987654321',
                'email' => 'secretaria@santamaria.com.br',
                'website' => 'https://www.santamaria.com.br',
                'description' => 'Colégio particular com ensino fundamental e médio.',
                'type' => 'privada',
                'level' => 'todos',
                'active' => true,
                'street' => 'Avenida das Américas',
                'number' => '456',
                'complement' => 'Bloco A',
                'neighborhood' => 'Barra da Tijuca',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'postal_code' => '22640100',
                'country' => 'Brasil',
                'latitude' => -22.9068,
                'longitude' => -43.1729,
            ],
            [
                'name' => 'Instituto Federal de Minas Gerais',
                'code' => 'IFMG001',
                'cnpj' => '45678912000123',
                'phone' => '31987654321',
                'email' => 'contato@ifmg.edu.br',
                'website' => 'https://www.ifmg.edu.br',
                'description' => 'Instituto federal com cursos técnicos e superiores.',
                'type' => 'federal',
                'level' => 'tecnico',
                'active' => true,
                'street' => 'Rua Alameda',
                'number' => '789',
                'complement' => null,
                'neighborhood' => 'Funcionários',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'postal_code' => '30112000',
                'country' => 'Brasil',
                'latitude' => -19.9167,
                'longitude' => -43.9345,
            ],
            [
                'name' => 'Universidade de Brasília',
                'code' => 'UNB001',
                'cnpj' => '78912345000165',
                'phone' => '61987654321',
                'email' => 'reitoria@unb.br',
                'website' => 'https://www.unb.br',
                'description' => 'Universidade pública federal com diversos cursos superiores.',
                'type' => 'federal',
                'level' => 'superior',
                'active' => true,
                'street' => 'Campus Universitário Darcy Ribeiro',
                'number' => 'S/N',
                'complement' => 'Asa Norte',
                'neighborhood' => 'Asa Norte',
                'city' => 'Brasília',
                'state' => 'DF',
                'postal_code' => '70910900',
                'country' => 'Brasil',
                'latitude' => -15.7942,
                'longitude' => -47.8822,
            ],
            [
                'name' => 'EMEI Criança Feliz',
                'code' => 'EMEI001',
                'cnpj' => null,
                'phone' => '11912345678',
                'email' => 'emei.criancafeliz@prefeitura.sp.gov.br',
                'website' => null,
                'description' => 'Escola Municipal de Educação Infantil.',
                'type' => 'municipal',
                'level' => 'infantil',
                'active' => true,
                'street' => 'Rua das Crianças',
                'number' => '50',
                'complement' => null,
                'neighborhood' => 'Vila Esperança',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '08460000',
                'country' => 'Brasil',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Colégio Técnico Industrial',
                'code' => 'CTI001',
                'cnpj' => '32165498000147',
                'phone' => '47987654321',
                'email' => 'secretaria@cti.edu.br',
                'website' => 'https://www.cti.edu.br',
                'description' => 'Colégio técnico especializado em cursos industriais.',
                'type' => 'privada',
                'level' => 'tecnico',
                'active' => false,
                'street' => 'Rua Industrial',
                'number' => '1000',
                'complement' => 'Distrito Industrial',
                'neighborhood' => 'Centro Industrial',
                'city' => 'Joinville',
                'state' => 'SC',
                'postal_code' => '89201000',
                'country' => 'Brasil',
                'latitude' => -26.3045,
                'longitude' => -48.8487,
            ],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
