<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDashboardStatsTable extends Migration
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
            'jumlah_perangkat_daerah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'jumlah_kecamatan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'jumlah_tiuh_kampung' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'total_anggaran_apbd' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '0',
            ],
            'index_sakip' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '-',
            ],
            'index_rb' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '-',
            ],
            'tingkat_kemiskinan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '0%',
            ],
            'angka_stunting' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '0%',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kabtanggamus_dashboard_stats');
    }

    public function down()
    {
        $this->forge->dropTable('kabtanggamus_dashboard_stats');
    }
}
