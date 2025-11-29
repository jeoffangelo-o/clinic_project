<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCspcClinicTables extends Migration
{
    public function up()
    {
        /**
         * USERS TABLE
         */
        $this->forge->addField([
            'user_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'role'         => ['type' => 'ENUM', 'constraint' => ['admin', 'nurse', 'student', 'staff']],
            'created_at'   => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');

        /**
         * PATIENTS TABLE
         */
        $this->forge->addField([
            'patient_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'first_name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'middle_name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'last_name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'gender'            => ['type' => 'ENUM', 'constraint' => ['male', 'female', 'other']],
            'birth_date'        => ['type' => 'DATE', 'null' => true],
            'contact_no'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'address'           => ['type' => 'TEXT', 'null' => true],
            'blood_type'        => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'allergies'         => ['type' => 'TEXT', 'null' => true],
            'medical_history'   => ['type' => 'TEXT', 'null' => true],
            'emergency_contact' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'        => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('patient_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('patients');

        /**
         * ANNOUNCEMENTS TABLE
         */
        $this->forge->addField([
            'announcement_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'content'         => ['type' => 'TEXT'],
            'posted_by'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'posted_at'       => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'posted_until'    => ['type' => 'DATETIME'],
            'url'             => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('announcement_id', true);
        $this->forge->addForeignKey('posted_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('announcements');

        /**
         * APPOINTMENTS TABLE
         */
        $this->forge->addField([
            'appointment_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'patient_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nurse_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'appointment_date'=> ['type' => 'DATETIME'],
            'purpose'         => ['type' => 'TEXT', 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'cancelled', 'completed'], 'default' => 'pending'],
            'remarks'         => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('appointment_id', true);
        $this->forge->addForeignKey('patient_id', 'patients', 'patient_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('nurse_id', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('appointments');

        /**
         * CONSULTATIONS TABLE
         */
        $this->forge->addField([
            'consultation_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'appointment_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'patient_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nurse_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'diagnosis'         => ['type' => 'TEXT'],
            'treatment'         => ['type' => 'TEXT', 'null' => true],
            'prescription'      => ['type' => 'TEXT', 'null' => true],
            'consultation_date' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
            'notes'             => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('consultation_id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'appointment_id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'patient_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('nurse_id', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('consultations');

        /**
         * MEDICAL CERTIFICATES TABLE
         */
        $this->forge->addField([
            'certificate_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'consultation_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'patient_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'issued_by'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'certificate_type'   => ['type' => 'ENUM', 'constraint' => ['fit_to_study', 'medical_leave', 'injury_report', 'others'], 'default' => 'fit_to_study'],
            'diagnosis_summary'  => ['type' => 'TEXT', 'null' => true],
            'recommendation'     => ['type' => 'TEXT', 'null' => true],
            'validity_start'     => ['type' => 'DATE', 'null' => true],
            'validity_end'       => ['type' => 'DATE', 'null' => true],
            'issued_date'        => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'file_path'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('certificate_id', true);
        $this->forge->addForeignKey('consultation_id', 'consultations', 'consultation_id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'patient_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('issued_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('medical_certificates');

        /**
         * INVENTORY TABLE
         */
        $this->forge->addField([
            'item_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'item_name'     => ['type' => 'VARCHAR', 'constraint' => 150],
            'category'      => ['type' => 'ENUM', 'constraint' => ['medicine', 'supply']],
            'quantity'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'unit'          => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'expiry_date'   => ['type' => 'DATE', 'null' => true],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'added_by'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_at'    => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('item_id', true);
        $this->forge->addForeignKey('added_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('inventory');

        /**
         * REPORTS TABLE
         */
        $this->forge->addField([
            'report_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'generated_by'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'report_type'   => ['type' => 'ENUM', 'constraint' => ['patient','consultation','appointment','inventory','announcement','comprehensive','daily','weekly','monthly'], 'null' => true],
            'report_data'   => ['type' => 'TEXT', 'null' => true],
            'generated_at'  => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'file_path'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('report_id', true);
        $this->forge->addForeignKey('generated_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('reports');

        /**
         * CONSULTATION_MEDICINES TABLE
         */
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'consultation_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'item_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity_used'   => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'unit'            => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at'      => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('consultation_id');
        $this->forge->addKey('item_id');
        $this->forge->addForeignKey('consultation_id', 'consultations', 'consultation_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'inventory', 'item_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('consultation_medicines');

        /**
         * INVENTORY_LOG TABLE
         */
        $this->forge->addField([
            'id'                       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'item_id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity_change'          => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'reason'                   => ['type' => 'ENUM', 'constraint' => ['consumption','stock_in','adjustment','rollback'], 'default' => 'adjustment'],
            'related_consultation_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'logged_by'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'notes'                    => ['type' => 'TEXT', 'null' => true],
            'created_at'               => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('item_id');
        $this->forge->addKey('related_consultation_id');
        $this->forge->addKey('logged_by');
        $this->forge->addForeignKey('item_id', 'inventory', 'item_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('related_consultation_id', 'consultations', 'consultation_id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('logged_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('inventory_log');

        /**
         * MIGRATIONS TABLE (framework style)
         */
        $this->forge->addField([
            'id'        => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'version'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'class'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'group'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'namespace' => ['type' => 'VARCHAR', 'constraint' => 255],
            'time'      => ['type' => 'INT', 'constraint' => 11],
            'batch'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('migrations');
    }

    public function down()
    {
        $this->forge->dropTable('reports', true);
        $this->forge->dropTable('inventory', true);
        $this->forge->dropTable('medical_certificates', true);
        $this->forge->dropTable('consultations', true);
        $this->forge->dropTable('appointments', true);
        $this->forge->dropTable('announcements', true);
        $this->forge->dropTable('patients', true);
        $this->forge->dropTable('users', true);
    }
}
