<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnggaranKasTable extends Migration
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
            'perangkat_daerah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tahun_anggaran' => [
                'type'       => 'YEAR',
            ],
            'sub_kegiatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'pagu_anggaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            // Field Rincian Anggaran Kas per Bulan
            'januari'   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'februari'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'maret'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'april'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'mei'       => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'juni'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'juli'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'agustus'   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'september' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'oktober'   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'november'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'desember'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],

            'total_anggaran_kas' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('anggaran_kas');
    }

    public function down()
    {
        $this->forge->dropTable('anggaran_kas');
    }
}
