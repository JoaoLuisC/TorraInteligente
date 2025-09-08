<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testando queries corrigidas da dashboard do analista...\n";

try {
    // Teste das estatísticas
    $pendentes = DB::table('solicitacoes_prova as sp')
        ->leftJoin('analise_sensorial as a', 'sp.id', '=', 'a.solicitacao_id')
        ->where('sp.status', 'Pendente')
        ->whereNull('a.id')
        ->count();

    echo "Análises pendentes: " . $pendentes . "\n";

    // Teste da query corrigida
    $analistaId = 5; // ID do analista de teste

    $totalMes = DB::table('analise_sensorial as a')
        ->join('solicitacoes_prova as sp', 'a.solicitacao_id', '=', 'sp.id')
        ->where('sp.analista_id', $analistaId)
        ->whereRaw('EXTRACT(year FROM a.created_at) = ?', [now()->year])
        ->whereRaw('EXTRACT(month FROM a.created_at) = ?', [now()->month])
        ->count();

    echo "Total análises este mês para analista {$analistaId}: " . $totalMes . "\n";

    // Teste de análises recentes
    $analisesRecentes = DB::table('analise_sensorial as a')
        ->join('solicitacoes_prova as sp', 'a.solicitacao_id', '=', 'sp.id')
        ->join('torras as t', 'sp.torra_id', '=', 't.id')
        ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
        ->where('sp.analista_id', $analistaId)
        ->select(
            'a.*',
            't.nome as torra_nome',
            't.variedade',
            't.finalidade',
            'produtor.nome as produtor_nome',
            'a.created_at as data_analise'
        )
        ->orderBy('a.created_at', 'desc')
        ->limit(5)
        ->get();

    echo "Análises recentes encontradas: " . $analisesRecentes->count() . "\n";

    echo "Todas as queries funcionaram corretamente!\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
