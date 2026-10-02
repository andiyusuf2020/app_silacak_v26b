<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRealisasiPendapatanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tahun_anggaran' => [
                'type'       => 'YEAR',
            ],
            'bulan' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['Pendapatan', 'Belanja'],
            ],
            'kode_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'uraian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'anggaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'realisasi' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kabtanggamus_realisasi_pendapatan');
    }

    public function down()
    {
        $this->forge->dropTable('kabtanggamus_realisasi_pendapatan');
    }
}
