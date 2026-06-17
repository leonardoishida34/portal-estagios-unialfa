<?php
// Faz a ponte entre a VIEW e o SERVICE.
// Alterações em relação à versão anterior:
// - aluno_id (int) → aluno_ra (string) ← chave do banco é o RA
// - removido campo 'carta' ← não existe no banco
// - sessão usa 'perfil' em vez de 'tipo'

require_once BASE_PATH . '/src/Services/CandidaturaService.php';
require_once BASE_PATH . '/src/Models/Candidatura.php';

class CandidaturaController {

    private CandidaturaService $candidaturaService;

    public function __construct() {
        $this->candidaturaService = new CandidaturaService();
    }

    // ── Listar candidaturas do aluno logado ──
    // Chamado pela view: aluno/candidaturas.php
    // Usa o RA do aluno da sessão para filtrar
    public function listarPorAluno(): array {
        // Pega o RA do aluno logado da sessão
        $alunoRa = $_SESSION['usuario']['ra'] ?? '';

        if (empty($alunoRa)) return [];

        return $this->candidaturaService->listarPorAluno($alunoRa);
    }

    // ── Listar candidatos de uma vaga ──
    // Chamado pela view: empresa/candidatos.php
    // Recebe o ID da vaga via $_GET['vaga_id']
    public function listarPorVaga(): array {
        $vagaId = (int)($_GET['vaga_id'] ?? 0);

        if ($vagaId === 0) return [];

        return $this->candidaturaService->listarPorVaga($vagaId);
    }

    // ── Enviar candidatura ──
    // Chamado pela view: aluno/candidatar.php
    // Pega o RA da sessão e o vaga_id do $_POST
    // Não tem carta — campo não existe no banco!
    public function candidatar(): array {
        // RA vem da sessão — não do formulário
        // assim o aluno não pode se candidatar como outro aluno
        $alunoRa = $_SESSION['usuario']['ra'] ?? '';
        $vagaId  = (int)($_POST['vaga_id']    ?? 0);

        // Validações
        if (empty($alunoRa)) {
            return ['erro' => 'Você precisa estar logado para se candidatar.'];
        }

        if ($vagaId === 0) {
            return ['erro' => 'Vaga não encontrada.'];
        }

        // Chama o service que chama a API
        // POST /candidaturas com aluno_ra e vaga_id
        return $this->candidaturaService->candidatar($alunoRa, $vagaId);
    }

    // ── Atualizar status da candidatura ──
    // Chamado pela view: empresa/candidatos.php
    // A empresa aprova ou reprova uma candidatura
    // PATCH /candidaturas/:id/status
    public function atualizarStatus(): array {
        $id     = (int)($_POST['candidatura_id'] ?? 0);
        $status = trim($_POST['status']          ?? '');

        // Só aceita status válidos do banco
        $statusValidos = ['pendente', 'aprovado', 'reprovado'];

        if ($id === 0) {
            return ['erro' => 'Candidatura não encontrada.'];
        }

        if (!in_array($status, $statusValidos)) {
            return ['erro' => 'Status inválido.'];
        }

        return $this->candidaturaService->atualizarStatus($id, $status);
    }

    // ── Remover candidatura ──
    // Chamado pela view: aluno/candidaturas.php
    // DELETE /candidaturas/:id
    public function remover(): array {
        $id = (int)($_POST['candidatura_id'] ?? 0);

        if ($id === 0) {
            return ['erro' => 'Candidatura não encontrada.'];
        }

        return $this->candidaturaService->remover($id);
    }
}
