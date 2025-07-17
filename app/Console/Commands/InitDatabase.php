<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InitDatabase extends Command
{
    protected $signature = 'db:init {--force : Forçar execução mesmo se tabelas existirem}';
    protected $description = 'Inicializa o banco de dados com a estrutura necessária';

    public function handle()
    {
        $this->info('🚀 Inicializando banco de dados...');

        // Verificar se as tabelas já existem
        if (!$this->option('force')) {
            try {
                DB::table('users')->count();
                $this->info('✅ Tabelas já existem. Use --force para recriar.');
                return 0;
            } catch (\Exception $e) {
                $this->info('📋 Tabelas não encontradas. Criando estrutura...');
            }
        }

        // Ler e executar o script SQL
        $sqlPath = base_path('docker/postgresql/init.sql');

        if (!File::exists($sqlPath)) {
            $this->error('❌ Arquivo init.sql não encontrado em: ' . $sqlPath);
            return 1;
        }

        $sql = File::get($sqlPath);

        try {
            DB::unprepared($sql);
            $this->info('✅ Banco de dados inicializado com sucesso!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao executar script: ' . $e->getMessage());
            return 1;
        }
    }
}
